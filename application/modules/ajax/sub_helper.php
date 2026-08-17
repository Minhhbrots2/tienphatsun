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
function helper_start_debug(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	vnSessionSetVar('dev', 1);
	echo json_encode(array('msg' => 1)); die();
}
function helper_set_stock_field(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsStock = new Stock();
	$msg = "_error";
	$stock_id = (int) Input::post('stock_id', 0);
	$p_field = Input::post('p_field');
	$p_value = Input::post('p_value', 0);
	if($stock_id > 0){
		$more = array();
		$more_information = $clsStock->getOneField('more_information', $stock_id);
		$more_information = $clsISO->to_array_json($more_information);
		$more_information[$p_field] = $p_value;
		// $clsISO->print_pre($more_information); die();
		if($p_field == 'is_sp_mech'){
			if((int) $p_value == 1){
				$show_website = '|user.fh|';
			} else {
				$show_website = '|user.fh||MOC||partner.fh|';
			}
			$more['show_website'] = $show_website;
		}
		if($clsStock->updateOne($stock_id, array_merge($more, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		)))){
			$msg = "_success";
		}
	}
	// Return
	echo $msg; die();
}
function helper_toggle_wishlist(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsStock = new Stock();
	$clsPolicy = new Policy();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	###
	$action = Input::post('action', 'add');
	$stock_id = (int) Input::post('stock_id', 0);
	$wishlist = $clsProfile->getOneField('wishlist', $profile_id);
	$wishlist_arrs = $clsISO->to_array_json($wishlist);
	if($action=='add'){
		$wishlist_arrs[] = $stock_id;
	} else if($action=='delete') {
		$wishlist_arrs = array_diff($wishlist_arrs, array($stock_id));
	}
	###
	$msg = "_error";
	if($clsProfile->updateOne($profile_id, array(
		'wishlist' => json_encode($wishlist_arrs, JSON_UNESCAPED_UNICODE)
	))){
		$msg = "_success";
	}
	// return
	echo $msg; die();
}
function helper_sync_clicked(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsProjectMeta = new ProjectMeta();
	$project_meta_id = (int) Input::post('id', 0);
	#
	$msg = "_error";
	if($clsProjectMeta->updateOne($project_meta_id, "total_click=total_click+1")){
		$msg = "_success";
	}
	// Return
	echo $msg; die();
}
function helper_sync_stock_type(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	$stock_type = (int) Input::post('stock_type', _BLOCK_TYPE_HIGHLEVEL_SALE);
	vnSessionSetVar('_ss_stock_type', $stock_type);
	// Return
	echo(1); die();
}
function helper_search_stock(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO;
	global $deviceType,$profile_id,$oneProfile,$clsConfiguration;
	$clsStock = new Stock();
	$clsPolicy = new Policy();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsRequestPTG = new RequestPTG();
	###
	$uid = $clsISO->getUniqid();
	$tp = Input::post('tp', '_search');
	if($tp=="_search"){
		$ms_code = Input::post('ms_code');
	} else if($tp == '_open') {
		$stock_id = (int) Input::post('stock_id', 0);
	}
	###
	$html = "_not_found"; $html_more_stock = "";
	if(($tp == '_search' && !empty($ms_code)) || ($tp=='_open' && $stock_id > 0)){
		if($tp == '_search'){
			$ms_code = preg_replace('/\s+/', '', $ms_code);
			$stock_code = trim(str_replace('.','', $ms_code));
			$stock_code = str_replace("-","",$stock_code);
			$stock_code = strtoupper($stock_code);
			$cond = "`is_trash`=0 AND `status_id`<>'"._STOCK_STATUS_NON_ID."'";
			if(!$clsISO->checkPermission('view_stock_resource')){
				$cond.= " AND `status_id`>0";
			}
			if(!$clsISO->checkPermission('view_stock_globe')){
				$cond.= " AND `show_website` like '%|user.fh|%'";
			}
			if(defined('_STOCK_SEARCH_HIDE_ENABLE') && _STOCK_SEARCH_HIDE_ENABLE==0){
				$cond.= " AND `status_id`<>'"._STOCK_STATUS_HIDDEN_ID."'";
			}
			$tmp = $clsStock->getAll("{$cond} AND (`ms_code`='{$ms_code}' OR `mscode`='{$stock_code}')");
			if(!empty($tmp)){
				if(!$clsISO->checkPermission('view_stock_hidden')){
					$stock_type = Input::post("stock_type",_BLOCK_TYPE_HIGHLEVEL_SALE);
					if($stock_type==_BLOCK_TYPE_HIGHLEVEL_SALE){
						$agency_hidden_stock_FH = $clsConfiguration->getValue('agency_hidden_stock_FH');
						$agency_hidden_stock_FH = $clsISO->to_array_json($agency_hidden_stock_FH);
						if(!empty($agency_hidden_stock_FH)){
							foreach ($agency_hidden_stock_FH as $key => $val) {
								$hid_block_id = (int) $val['block_id'];
								if($hid_block_id > 0 && !in_array($hid_block_id, $arr_hid_blocks)) {
									$arr_hid_blocks[] = $hid_block_id;	
								}		
							}
						}
						foreach ($tmp as $k_stock => $v_stock) {
							$oneProperty = $clsProperty->getOne($v_stock["agency_id"], "property_code,title,more_information");	
							$more_information_ag = $oneProperty['more_information'];
							$more_information_ag = $clsISO->to_array_json($more_information_ag);
							if(!in_array($v_stock["block_id"], $arr_hid_blocks)){
								// Ẩn quỹ VIN
								$hide_stock_vin_MOC = $core->get_field($more_information_ag, 'FH_stock_vin', 0);
								if((int) $hide_stock_vin_MOC==1 && !in_array($v_stock["block_id"], $arr_hid_blocks)){
									unset($tmp[$k_stock]);
								}
							} else {
								// Ẩn quỹ MAS
								$hid_field = sprintf('MOC_%s_%s', $v_stock["agency_id"], $v_stock["block_id"]);
								if((int) $core->get_field($more_information_ag, $hid_field, 0) == 1){
									unset($tmp[$k_stock]);
								}
							}
						}
					}
				}
				$tmp = array_values($tmp);
				$total_stocks = count($tmp);
				if($total_stocks > 1) {
					echo json_encode(array(
						'stt' => 1,
						'uid' => $uid,
						'html' => "_not_found"
					)); die();
				}
				$oneStock = $tmp[0];
				if($total_stocks > 1){
					foreach($tmp as $key => $val){
						if($val['status_id'] > 0 && $val['status_id'] <> _STOCK_STATUS_SOLD_ID){
							$oneStock = $val;
							break;
						}
					}
					$html_more_stock.= '<div class="d-flex gap-1 mb-1 align-items-center justify-content-center">
						<span class="text-muted">XEM THÊM</span>';
					for($i=0; $i<$total_stocks; $i++){
						$stock_id = $tmp[$i][$clsStock->pkey];
						$block_id = $tmp[$i]['block_id'];
						$project_id = $tmp[$i]['project_id'];
						if($stock_id != $oneStock[$clsStock->pkey]){
							$html_more_stock.= '<a href="javascript:void(0)" class="border py-1 px-2 text-link rounded-2" onClick="$Core.helper.hide_stock(this, event, \''.$stock_id.'\')">'.$tmp[$i]['ms_code'].', '.$clsProperty->getTitle($block_id).', '.$clsProject->getCode($project_id).'</a>';
						}
					}
					$html_more_stock.= '</div>';
				}
			} else {
				echo json_encode(array(
					'stt' => 1,
					'uid' => $uid,
					'html' => "_not_found"
				)); die();
			}	
		} else {
			$oneStock = $clsStock->getOne($stock_id);
		}
		if(!empty($oneStock)){
			$ms_code	= $oneStock['ms_code'];
			$stock_id 	= (int) $oneStock['stock_id'];
			$stock_type  = (int) $oneStock['stock_type'];
			$agency_id  = (int) $oneStock['agency_id'];
			$project_id  = (int) $oneStock['project_id'];
			$block_id 	 = (int) $oneStock['block_id'];
			$building_id = (int) $oneStock['building_id'];
			$status_id 	 = (int) $oneStock['status_id'];
			$bedroom_id = (int) $oneStock['bedroom_id'];
			$home_direction_id = (int) $oneStock['home_direction_id'];
			$view_id = (int) $oneStock['view_id'];
			$type_id = (int) $oneStock['type_id'];
			$more_information = $oneStock['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$total_price_vat = $core->get_money_field($more_information, "total_price_vat", 0);
			$price_sheets = $core->get_field($more_information, "price_sheets", []);
			$list_attributes = $core->get_field($more_information, "properties", []);
			$hide_price_sheets = (int) $core->get_field($more_information, "hide_price_sheets", 0);
			$invest_fund_id = (int) $core->get_field($more_information, "invest_fund_id", 0);
			$bank_id = (int) $core->get_field($more_information, "bank_id", 0);
			if($type_id==0 && (int) $core->get_field($more_information, "type_id", 0) > 0){
				$type_id = (int) $more_information['type_id'];
			}
			$oneTemplate = $oneBlock = $oBuilding = $oneProperty = $oneBedroom = array();
			$oneType = $oneView = $oneBank = $oneInvestFund = $oneHomeDirection = $arr_property_ids = array();
			$oneStatus = array('title' => 'Đã bán', 'bgcolor'=>'#FFF', 'textcolor' => '#566a7f');
			if($block_id > 0) $arr_property_ids[] = $block_id;
			if($building_id > 0) $arr_property_ids[] = $building_id;
			if($agency_id > 0) $arr_property_ids[] = $agency_id;
			if($status_id > 0) $arr_property_ids[] = $status_id;
			if($type_id > 0) $arr_property_ids[] = $type_id;
			if($view_id > 0) $arr_property_ids[] = $view_id;
			if($bedroom_id > 0) $arr_property_ids[] = $bedroom_id;
			if($home_direction_id > 0) $arr_property_ids[] = $home_direction_id;
			if($invest_fund_id > 0) $arr_property_ids[] = $invest_fund_id;
			if($bank_id > 0) $arr_property_ids[] = $bank_id;
			$field = "{$clsProperty->pkey},`title`,`property_code`,`more_information`,`textcolor`,`bgcolor`";
			$tmp = $clsProperty->getAll("{$clsProperty->pkey} in (".implode(",", $arr_property_ids).")", $field);
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					if($building_id > 0 && $val[$clsProperty->pkey] == $building_id)
						$oBuilding = $val;
					else if($block_id > 0 && $val[$clsProperty->pkey] == $block_id)
						$oneBlock = $val;
					else if($agency_id > 0 && $val[$clsProperty->pkey] == $agency_id)
						$oneProperty = $val;
					else if($type_id > 0 && $val[$clsProperty->pkey] == $type_id)
						$oneType = $val;
					else if($status_id > 0 && $val[$clsProperty->pkey] == $status_id)
						$oneStatus = $val;
					else if($view_id > 0 && $val[$clsProperty->pkey] == $view_id)
						$oneView = $val;
					else if($bank_id > 0 && $val[$clsProperty->pkey] == $bank_id)
						$oneBank = $val;
					else if($invest_fund_id > 0 && $val[$clsProperty->pkey] == $invest_fund_id)
						$oneInvestFund = $val;
					else if($bedroom_id > 0 && $val[$clsProperty->pkey] == $bedroom_id)
						$oneBedroom = $val;
					else if($home_direction_id > 0 && $val[$clsProperty->pkey] == $home_direction_id)
						$oneHomeDirection = $val;
				}
				unset($tmp);
			}
			$more_information_ag = $oneProperty['more_information'];
			$more_information_ag = $clsISO->to_array_json($more_information_ag);
			$group_zalo = $core->get_field($more_information_ag, 'group_zalo');
			if(!$clsISO->checkPermission('view_stock_hidden')){
				if($stock_type==_BLOCK_TYPE_HIGHLEVEL_SALE){
					$agency_hidden_stock_FH = $clsConfiguration->getValue('agency_hidden_stock_FH');
					$agency_hidden_stock_FH = $clsISO->to_array_json($agency_hidden_stock_FH);
					if(!empty($agency_hidden_stock_FH)){
						foreach ($agency_hidden_stock_FH as $key => $val) {
							$hid_block_id = (int) $val['block_id'];
							if($hid_block_id > 0 && !in_array($hid_block_id, $arr_hid_blocks)) {
								$arr_hid_blocks[] = $hid_block_id;	
							}		
						}
					}
					if(!in_array($block_id, $arr_hid_blocks)){
						// Ẩn quỹ VIN
						$hide_stock_vin_MOC = $core->get_field($more_information_ag, 'FH_stock_vin', 0);
						if((int) $hide_stock_vin_MOC==1 && !in_array($block_id, $arr_hid_blocks)){
							echo json_encode(array(
								'stt' => 1,
								'uid' => $uid,
								'html' => "_not_found"
							)); die();
						}
					} else {
						// Ẩn quỹ MAS
						$hid_field = sprintf('MOC_%s_%s', $agency_id, $block_id);
						if((int) $core->get_field($more_information_ag, $hid_field, 0) == 1){
							echo json_encode(array(
								'stt' => 2,
								'uid' => $uid,
								'html' => "_not_found"
							)); die();
						}
					}
				}
			}
			#- Start Log
			$clsLog = new Log();
			if(!in_array($profile_id, _PROFILE_NOT_LOG)){
				if($tp=='_search'){
					$clsLog->insertAction('search',sprintf('Tra cứu căn hộ <strong>%s</strong>', $ms_code), $stock_id, $block_id);
				} else {
					$clsLog->insertAction('view_stock',sprintf('Xem thông tin căn hộ <strong>%s</strong>',$ms_code), $stock_id, $block_id);
				}
			}
			#- End Log
			$oneProject = $clsProject->getOne($project_id, "code,title");
			$block_information = $oneBlock['more_information'];
			$block_information = $clsISO->to_array_json($block_information);
			$vr_link = $core->get_field($block_information, 'vr_link', "");
			$vr_source = $core->get_field($block_information, 'vr_source', "");
			###
			$html_price_more = ""; $total_price_early = $has_price_early = 0;
			$price_field_configs = $core->get_field($block_information, "price_field_configs", []);
			if($clsRequestPTG->checkRequest($stock_id)) {
				$html_request_ptg = '<button type="button" class="btn btn-sm btn-success" style="cursor:no-drop">
					<i class="fa fa-check"></i> <span>Đã yêu cầu PTG</span>
				</button>';		
			}else{
				$html_request_ptg = '<button type="button" onclick="$Core.helper.requestPTG(this,event)" 
				action="_OPEN" stock_id="'.$stock_id.'" id="request_'.$stock_id.'" class="btn btn-sm btn-warning">
					<i class="bx bx-vector"></i> <span>Yêu cầu PTG</span>
				</button>';		
			}
			#so sánh 
			$html_compare = '<button class="btn-add-can btn btn-sm bg-main text-white" title="Thêm so sánh" onclick="$Core.global.compare.add_stock_compare(this,event)" _tp="pop" action="add" stock_id="'.$stock_id.'" ><i class="bx bx-git-compare"></i> So sánh</button>';
			$html_zalo = $clsProject->getHtmlContactZaloProject($block_id,$oneBlock,"modal");
			if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE){
				if(!empty($price_field_configs)){
					$total_cols = 0;
					foreach($price_field_configs as $key => $val){
						if(isset($val['status']) && $val['status']==1 
							&& isset($more_information[$key]) && !empty($more_information[$key])){
							$total_cols += 1;
							if($key=='total_price_early'){
								$total_price_early = $more_information[$key];
							}
						}
					}
					if($total_cols > 0){ $ii= 0;
						$html_price_more.= '<hr class="my-2" />
							<div class="form-row align-items-center justify-content-center">';
						foreach($price_field_configs as $key => $val){
							if(isset($val['status']) && $val['status']==1 
								&& isset($more_information[$key]) && !empty($more_information[$key])){
								$price = $core->get_money_field($more_information, $key, 0);
								if($block_id == _PROJECT_BLOCK_MLS_ID 
									&& $clsStock->checkStockFundType($stock_id, $building_id, $oneStock)) {
									if(in_array($key, array('total_price_early', 'total_price_bank'))){
										$html_price_more.= '<div class="text-center col-xxl-2 col-4">
											<p class="mb-0">'.$val['title'].'</p>
											<div class="text-main fs-16 fw-bold">'.$clsISO->priceFormatV2($price,3).'tỷ</div>
										</div>';
									}
								}else{
									$html_price_more.= '<div class="text-center col-xxl-2 col-'.($total_cols==3?'4':'3').'">
										<p class="mb-0">'.$val['title'].'</p>
										<div class="text-main fs-16 fw-bold">'.$clsISO->priceFormatV2($price,3).'tỷ</div>
									</div>';
								}
								++$ii;
							}
						}
						$html_price_more.= '</div>
						<hr class="my-2" />';
					}
				}
			} else {
				if(!empty($price_field_configs)){
					$total_cols = 0;
					foreach($price_field_configs as $key => $val){
						if(isset($val['status']) && $val['status']==1 
							&& isset($more_information[$key]) && !empty($more_information[$key])){
							$total_cols += 1;
						}
					}
					if($total_cols > 0){
						$html_price_more.= '<hr class="my-2" />
							<div class="form-row">';
						foreach($price_field_configs as $key => $val){
							if(isset($val['status']) && $val['status']==1 
								&&isset($more_information[$key]) && !empty($more_information[$key])){
								$price = isset($more_information[$key]) ? $clsISO->processSmartNumber($more_information[$key]) : 0;
								$html_price_more.= '<div class="text-center col-'.($total_cols==3?'4':'3').'">
									<p class="mb-0 text-nowrap">'.$val['title'].'</p>
									<div class="text-main fs-14 fw-bold">'.$clsISO->priceFormatV2($price,3).' tỷ</div>
								</div>';
							}
						}
						$html_price_more.= '</div>';
					}
				}
				if(empty($total_price_vat) && !empty($more_information['total_price_early'])){
					$has_price_early = 1;
					$total_price_vat = $more_information['total_price_early'];
				}
			}
			#- Template
			$building_information = $oBuilding['more_information'];
			$building_information = $clsISO->to_array_json($building_information);
			$floor_specical = !empty($building_information['floor_specical']) ? $building_information['floor_specical'] : array();
			if(!empty($floor_specical)) {
				$key=array_search($oneStock["floor"],$floor_specical);
				$template_specical = !empty($building_information['template_specical'][$key]) ? $building_information['template_specical'][$key] : array();
				if(!empty($template_specical)) {
					foreach($template_specical as $key => $val){
						if($oneStock['code'] == $val['code']){
							$oneTemplate = $val;
							break;
						}
					}
				}
			}
			if(empty($oneTemplate)) {
				$list_templates = $core->get_field($building_information, "template", []);
				if(!empty($list_templates)){
					foreach($list_templates as $key => $val){
						if($oneStock['code'] == $val['code']){
							$oneTemplate = $val;
							break;
						}
					}
				}
			}
			$stock_template = $building_information['stock_template'];
			$stock_template = str_replace('[MaToa]',$oBuilding['property_code'], $stock_template);
			$stock_template = str_replace('[Tang]', 'XX', $stock_template);
			$regex_search = str_replace('[CanHo]', $oneStock['code'], $stock_template);
			$csbh = $core->get_field($more_information, "csbh", "");
			$DT_TT = $core->get_field($more_information, "DT_TT", $oneTemplate['DT_TT']);
			$DT_Tim = $core->get_field($more_information, "DT_Tim", $oneTemplate['DT_Tim']);
			if($home_direction_id == 0 && (int) $core->get_field($oneTemplate, "home_direction_id", 0) > 0){
				$home_direction_id = $oneTemplate['home_direction_id'];
			}
			if($bedroom_id == 0 && (int) $core->get_field($oneTemplate, "bedroom_id", 0) > 0){
				$bedroom_id = (int) $oneTemplate['bedroom_id'];
			}
			#- Price Sheets
			$html_image_price_sheets = $html_price_sheets = $img_price_sheets = "";
			$stock_posters = $core->get_field($more_information, "stock_poster", []);
			if(!empty($stock_posters)){
				$html_price_sheets.= '<li><i class="material-icons-outlined" style="color:#696cff">image</i> POSTER: ';
				foreach($stock_posters as $okey => $oval){
					$html_price_sheets.= '<a class="btn btn-xs rounded-pill btn-outline-default mr-1" data-fancybox data-preload="true" href="'.$clsISO->getGoogleUrl($oval['image']).'">'.$oval['title'].'</a>';
				}
				$html_price_sheets.= '</li>';
			}else if(!empty($oneTemplate["layout"])) {
				$html_price_sheets.= '<li><i class="material-icons-outlined" style="color:#696cff">image</i> POSTER: ';
				$html_price_sheets.= '<a class="btn btn-xs rounded-pill btn-outline-default mr-1" data-fancybox data-preload="true" href="'.$clsISO->getGoogleUrl($oneTemplate["layout"]).'">Trục căn '.$oneStock['code'].'</a>';
				$html_price_sheets.= '</li>';
			}
			$stock_poster_video = $core->get_field($more_information, "stock_poster_video", []);
			if(!empty($stock_poster_video)){
				$html_price_sheets.= '<li><i class="material-icons-outlined" style="color:#696cff">video_camera_back</i> VIDEO: ';
				foreach($stock_poster_video as $okey => $oval){
					$html_price_sheets.= '<a class="btn btn-xs rounded-pill btn-outline-primary mr-1" target="_blank" href="'.$oval['video'].'">'.$oval['title'].'</a>';
				}
				$html_price_sheets.= '</li>';
			}
			if(!empty($oneTemplate) && !empty($oneTemplate['layout_ns'])){
				if($deviceType=='phone'){
					$html_price_sheets.='<li><a target="_blank" href="'.$oneTemplate['layout_ns'].'">
						<i class="material-icons-outlined">flip_to_front</i> Layout chi tiết căn hộ</a></li>';
				} else {
					$html_image_price_sheets.= '<tr>
						<th class="bg-lighter">Layout căn hộ<span class="label bg-success">IMG</span></th>
						<td><a target="_blank" href="'.$oneTemplate['layout_ns'].'">Xem Layout</a></td>
					</tr>';
				}
			}
			if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE){
				/* $bedroom_information = $clsProperty->getOneField('more_information', $bedroom_id);
				$bedroom_information = $clsISO->to_array_json($bedroom_information);
				$folder_interior_ns = isset($bedroom_information['folder_interior_ns']) ? $bedroom_information['folder_interior_ns'] : array();
				if(!empty($folder_interior_ns)){ $ii = 0;
					$html_price_sheets.= '<li><i class="material-icons-outlined">image</i> NỘI THẤT: ';
					foreach($folder_interior_ns as $pkey => $pval){
						if(!empty($pval['title']) && !empty($pval['link'])){
							$gg_id = $clsISO->getGoogleId($pval['link']);
							$html_price_sheets.= '<span class="gr-link mr-1">
								<a data-fancybox data-type="iframe" data-preload="true" href="/viewer/'.$gg_id.'">'.$pval['title'].'</a>';
							if(!empty($pval['video'])){
								$html_price_sheets.= ' | <a data-fancybox data-type="iframe" href="'.$pval['video'].'">video</a>';
							}
							$html_price_sheets.='</span>';
						}
						++$ii;
					}
					$html_price_sheets.= '</li>';
				} */
				if(!empty($price_sheets) && $hide_price_sheets==0){
					$price_sheets = @array_reverse($price_sheets);
					$oneSheet = reset($price_sheets);
					$img_price_sheets.= '<fieldset class="ixZrPaFqgl radius-4 my-2 bg-main">
						<legend class="fs-12 mb-0 px-1 bg-main text-white">Phiếu tính giá'.(!empty($csbh) ? sprintf(' %s', $csbh) : '').' <a href="javascript:;" data-link="'.$clsStock->getLink($ms_code, 'PTG').'" onClick="$Core.util.copyToClipboard(this, event)" class="btn btn-xs bg-white text-main btn-icon btn-outline-default"><i class=\'bx bx-copy fs-12\'></i></a></legend>
						<div class="d-flex p-2">';
						foreach($oneSheet['sheets'] as $val){
							$img_price_sheets.= '<div class="flex-fill text-center">
								<a class="text_ptg" target="_blank" href="'.$val['image'].'" download>'.$val['title'].'</a>
							</div>';
						}
						$img_price_sheets.= '</div>
					</fieldset>';
				}
			} else {
				if(!empty($price_sheets)){
					$price_sheets = @array_reverse($price_sheets);
					$oneSheet = reset($price_sheets);
					$img_price_sheets.= '<fieldset class="radius-4 ixZrPaFqgl my-2 bg-main">
						<legend class="fs-12 mb-0 px-1 bg-main text-white">Phiếu tính giá'.(!empty($csbh) ? sprintf(' %s', $csbh) : '').' <a href="javascript:;" data-link="'.$clsStock->getLink($ms_code, 'PTG').'" onClick="$Core.util.copyToClipboard(this, event)" class="btn btn-xs bg-white text-main btn-icon btn-outline-default"><i class=\'bx bx-copy fs-12\'></i></a></legend>
						<div class="d-flex p-2">';
						foreach($oneSheet['sheets'] as $val){
							$img_price_sheets.= '<div class="flex-fill text-center">
								<a class="text_ptg" target="_blank" href="'.$val['image'].'" download>'.$val['title'].'</a>
							</div>';
						}
						$img_price_sheets.= '</div>
					</fieldset>';
				} else if(isset($more_information['price_temporary_ns']) && !empty($more_information['price_temporary_ns'])){
					$img_price_sheets.= '<fieldset class="radius-4 ixZrPaFqgl my-2 bg-main">
						<legend class="fs-12 mb-0 px-1 bg-main text-white">Phiếu tính giá'.(!empty($csbh) ? sprintf(' %s', $csbh) : '').'</legend>
						<div class="d-flex p-2">';
						$img_price_sheets.= '<div class="flex-fill text-center"><a class="text_ptg" target="_blank" class="text-upper" href="'.$more_information['price_temporary_ns'].'" download>PTG TẠM TÍNH</a></div>';
						$img_price_sheets.= '</div>
					</fieldset>';
				}
			}
			#--- CSBH
			if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE){
				$regex = sprintf('%s_%s', $project_id, $block_id);
			} else {
				$regex = sprintf('%s_%s_%s', $project_id, $block_id, $building_id);
			}
			$condPolicy = "`block_type`='{$stock_type}' and `ms_date`<='".time()."' and `scope_slash` like '%|{$regex}|%' ";
			#CSBH tang thu cap
			$floor_hierarchy = $core->get_field($building_information, "floor_hierarchy", []);
			if($clsStock->checkStockFundType($stock_id,$building_id,$oneStock,$oBuilding)) {
				$condPolicy .= " AND `applicable_fund_type`='1'";
			}else{
				$condPolicy .= " AND `applicable_fund_type`='0'";
			}
			$onePolicy = $clsPolicy->getByCond($condPolicy." order by ms_date DESC limit 0,1");
			if(!empty($onePolicy)){
				$html_price_sheets.='<li><p class="text-muted mb-1">
					<a target="_blank" href="'.$onePolicy['link_ns'].'"><i class="material-icons-outlined">policy</i> Chính sách bán hàng('.$clsISO->convertTimeToText($onePolicy['ms_date']).')</a><br>
				</li>
				<li><p class="text-muted mb-1">
					<a target="_blank" href="'.$onePolicy['link_ms'].'"><i class="material-icons-outlined">paid</i> Phiếu tính giá mẫu('.$clsISO->convertTimeToText($onePolicy['ms_date']).')</a>
				</li>';
			}
			$agency_name = $html_last_updated = $html_button_price_sheets = "";
			if($oneStock['agency_id'] > 0){
				if($clsISO->checkPermission('view_stock_resource') || $clsISO->check_view_resource($stock_type, $block_id, $oneBlock)){
					$agency_name = sprintf(' - %s', $oneProperty['title']);
					$html_button_group_zalo = !empty($group_zalo) ? sprintf('<a class="zalo_group-link" href="%s" target="_blank">'.$clsISO->makeIcon('bx-search', 'Check').'</a>', $group_zalo) : "";
					$folder_price_sheets = $core->get_field($more_information_ag, "folder_price_sheets", []);
					if(!empty($folder_price_sheets)){
						foreach($folder_price_sheets as $key => $val){
							if(!empty($val['title']) && !empty($val['link'])){
								$html_button_price_sheets.= '<a class="btn btn-xs btn-outline-default mr-1" href="'.$val['link'].'" target="_blank">'.((mb_strlen($val['title']) > 4) ? $clsISO->truncate($val['title'],3,"") : $val['title']).'</a>';
							}
						}
					}
					if($clsISO->checkPermissionGroup('ADMIN_PROJECT') || $clsISO->checkPermissionGroup('DIRECTOR')) {
						$html_price_sheets.= sprintf('<li><a href="/admin/?mod=setting&act=property&group=stock&agency='.$agency_id.'" target="_blank"><i class="material-icons-outlined">home_work</i> %s %s %s</a></li>', $oneProperty['title'], $html_button_price_sheets, $html_button_group_zalo);
					}else{
						$html_price_sheets.= sprintf('<li><i class="material-icons-outlined">home_work</i> %s %s %s</li>', $oneProperty['title'], $html_button_price_sheets, $html_button_group_zalo);
					} 					
				}
			}
			if($clsISO->checkPermission('log_search_stock')){
				$html_price_sheets.= '<li><a href="'.PCMS_URL.'/logs-sale.html?stock_id='.$stock_id.'" target="_blank">
					<i class="material-icons-outlined">history</i> Lịch sử tra cứu 
					<strong class="text-black">('.$clsLog->getTotalSearch($stock_id).')</strong>
				</a></li>';
			}
			#- Status
			if($status_id > 0){
				$status_name= $clsProperty->getTitle($status_id, $oneStatus);
			} else{
				$status_name = "Đã bán";
				$status_id = _STOCK_STATUS_SOLD_ID;
				$oneStatus= $clsProperty->getOne($status_id,"bgcolor,textcolor");
			}
			#- Liked
			$liked = $clsProfile->checkInWishlist($stock_id);
			#- Cập nhật L.Cuối
			if($oneStock['upd_date'] > 0){
				$html_last_updated .= "<div class=\"text-center\">
					Cập nhật lần cuối: ".$clsISO->convertTimeToText($oneStock['upd_date'], true)."
				</div>";
			}
			#
			$price_m2 = 0; 
			if($clsISO->convertToNumber($DT_TT) > 0) {
				if(!empty($total_price_early)){
					$price_m2 = $clsISO->processSmartNumber($total_price_early) / $clsISO->convertToNumber($DT_TT);
				} else if(!empty($total_price_vat)){
					$price_m2 = $clsISO->processSmartNumber($total_price_vat) / $clsISO->convertToNumber($DT_TT);
				}	
			}				
			#- tìm kiếm tòa, tầng, trục căn
			$html_search = '<div class="w-full mb-3 d-flex">
				<div class="x-box flex-fill text-center">
					<p class="text-muted mb-1"><i class="bx bx-building-house"></i> Tòa</p>
					<h4 class="fs-14 mb-0"><a class="text-primary  text-nowrap" href="'.$clsStock->getLinkSearch($building_id,$stock_id,"building").'" target="_blank">Tòa '.$clsProperty->getCode($building_id, $oBuilding).'<i class="bx bx-link-external fs-6 ml-1"></i></a></h3>
				</div>
				<div class="x-box flex-fill text-center">
					<p class="text-muted mb-1"><i class="bx bxl-heroku"></i> Tầng</p>
					<h4 class="fs-14 mb-0"><a class="text-primary  text-nowrap" href="'.$clsStock->getLinkSearch($building_id,$stock_id,"floor").'" target="_blank">Tầng '.$oneStock["floor"].'<i class="bx bx-link-external fs-6 ml-1"></i></a></h3>
				</div>
				<div class="x-box flex-fill text-center">
					<p class="text-muted mb-1"><i class="bx bx-collection"></i> Trục</p>
					<h4 class="fs-14 mb-0"><a class="text-primary  text-nowrap" href="'.$clsStock->getLinkSearch($building_id,$stock_id,"code").'" target="_blank">Trục '.$oneStock["code"].'<i class="bx bx-link-external fs-6 ml-1"></i></a></h3>
				</div>
			</div>';
			// Permiss
			$permiss_upload_poster = $clsISO->checkPermission('upload_poster') ? 1 : 0;
			$permiss_hide_stock_globe = $clsISO->checkPermission('hide_stock_globe') ? 1 : 0;
			$permiss_edit_stock_advanced = $clsISO->checkPermission('edit_stock_advanced') ? 1 : 0;
			$permis_edit_stock_mechanism = $clsISO->checkPermissionGroup('ADMIN_PROJECT') || $clsISO->checkPermissionGroup('DIRECTOR') ? 1 : 0;
			#poster
			$html_button_more = '<div class="btn-group">
				<button type="button" class="btn btn-sm btn-outline-default btn-icon dropdown-toggle hide-arrow" 
					data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
				<ul class="dropdown-menu dropdown-menu-end no-hidden">
					'.($permiss_edit_stock_advanced?'<li><a class="dropdown-item" stock_id="'.$stock_id.'" tp="sheet_price" 
						onClick="$Core.helper.open_quick_stock(this,event)">'.$core->makeIcon('plus', 'Thêm PTG').'</a></li>
					<li><a class="dropdown-item" stock_id="'.$stock_id.'" tp="update_status" 
						onClick="$Core.helper.open_quick_stock(this,event)">'.$core->makeIcon('check','Cập nhật T.trạng').'</a></li>':'').'
					'.($permiss_upload_poster==1?'<li><a class="dropdown-item" onClick="$Core.helper.open_quick_stock(this,event)" stock_id="'.$stock_id.'" tp="poster"><i class="bx bx-image"></i> Thêm postter</a></li>
					<li><a class="dropdown-item" onClick="$Core.helper.open_quick_stock(this,event)" stock_id="'.$stock_id.'" tp="video"><i class="bx bx-video"></i> Thêm Video</a></li>':'').'
				</ul>
			</div>';
			$html_poster = "";
			if((!empty($oneTemplate["layout"]) || !empty($stock_posters)) && $deviceType != 'phone' ) {
				$html_poster = '<div class="position-absolute right-0 zindex-1" style="top:18%">
				<div class="btn-group dropend">
				  <button type="button" class="btn bg-white text-main dropdown-toggle hide-arrow px-1 py-3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="box-shadow: 0px 0px 2px"><i class="bx bxs-right-arrow"></i></button>
				  <ul class="dropdown-menu dropdown-menu-center w-px-350" style="">';	
					if(!empty($stock_posters)){
						$i=0;
						foreach($stock_posters as $okey => $oval){
							$html_poster.= '<a class="'.($i>0 ?"d-none":"" ).'" data-fancybox="poster_stock_'.$uid.'" data-preload="true" href="'.$clsISO->getGoogleUrl($oval['image']).'" data-caption="'.$oval['title'].'" ><img src="'.$clsISO->getGoogleUrl($oval['image']).'" class="w-100 h-100 object-contain" ></a>';
							++$i;
						}
					}else if(!empty($oneTemplate["layout"])) {
						$html_poster.= '<a class="" data-fancybox="poster_stock_'.$uid.'" data-preload="true" href="'.$clsISO->getGoogleUrl($oneTemplate["layout"]).'" data-caption="'.$oval['title'].'" ><img src="'.$clsISO->getGoogleUrl($oneTemplate["layout"]).'" width="300" class="w-100" ></a>';
					}
				$html_poster .= '</ul>
						</div>
					</div>';
			}
			$html = '<div class="modal-dialog'.($deviceType=='phone'?' modal-dialog-centered':'').'">
				<div class="modal-content">'.$html_poster.'
					<div class="modal-header">
						<h5 class="modal-title">Thông tin '.$ms_code.'</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						'.$html_more_stock.'
						<div class="rounded-3 overflow-hidden">
							<div class="bg-'.($agency_id==_AGENCY_CNCN_ID?'purple':'main').' p-3 w-full w-100 text-center">
								<h3 class="text-white mb-0 fs-18 fw-bold"><a class="text-white" href="'.$clsStock->getLink($oneStock['ms_code']).'" target="_blank">'.$oneStock['ms_code'].($stock_type==_BLOCK_TYPE_LOWFLOOR_SALE ? sprintf(', %s',$oneProject['code']) : sprintf(', %s',$oneBlock['property_code'])).' <i class="bx bx-link-external"></i></a> 
								<a class="text-white ml-1" href="/tim-kiem/'.$regex_search.'.html"><i class="bx bx-search"></i></a> 
								'.(!empty($vr_link)?'<a class="ml-1" onClick="$Core.helper.close_webui_popver(this, event)" title="Xem VR360" href="'.$vr_link.'" data-fancybox data-type="iframe" data-caption="'.$vr_source.'"><img src="'.URL_IMAGES.'/vr-w-360.png" class="w-px-30"></a>':'').'<button class="btn btn-icon text-white" onclick="$Core.global.share_stock(this,event)" stock_id="'.$stock_id.'" >
									<svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24" >
									<path d="M5.5 15.5c1.07 0 2.02-.5 2.67-1.26l6.87 3.87c-.01.13-.04.26-.04.39 0 1.93 1.57 3.5 3.5 3.5s3.5-1.57 3.5-3.5-1.57-3.5-3.5-3.5c-1.07 0-2.02.5-2.67 1.26l-6.87-3.87c.01-.13.04-.26.04-.39s-.02-.26-.04-.39l6.87-3.87C16.47 8.5 17.42 9 18.5 9 20.43 9 22 7.43 22 5.5S20.43 2 18.5 2 15 3.57 15 5.5c0 .13.02.26.04.39L8.17 9.76A3.48 3.48 0 0 0 5.5 8.5C3.57 8.5 2 10.07 2 12s1.57 3.5 3.5 3.5m13 1.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5m0-13c.83 0 1.5.67 1.5 1.5S19.33 7 18.5 7 17 6.33 17 5.5 17.67 4 18.5 4m-13 6.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5S4 12.83 4 12s.67-1.5 1.5-1.5"></path>
									</svg>
								</button></h3>
							</div>
							<div class="py-2 px-3 bg-grayter">
								<div class="text-center">
									<p class="mb-0 text-main fs-24 fw-bold">
										<i class="material-icons-outlined fs-24 mr-1">shopping_cart</i>
										'.(!empty($total_price_vat) ? ($stock_type== _BLOCK_TYPE_LOWFLOOR_SALE ? sprintf('%s tỷ', $clsISO->formatPriceV2($total_price_vat)) : $clsISO->formatPriceV2($total_price_vat)) : 'Check admin').'
									</p>
									<p class="mb-0">'.($has_price_early?'Giá trên sử dụng phương án <strong>TTS</strong>':'Giá đã bao gồm VAT & KPBT').'</p>
									'.(!empty($csbh) ? '<p class="text-main fs-13">Chính sách bán hàng áp dụng: <strong>'.$csbh.'</strong></p>' : '').'
								</div>
								<div class="d-flex flex-wrap gap-1 fs-12 mt-2 justify-content-center">
									'.($stock_type==_BLOCK_TYPE_HIGHLEVEL_SALE?'<a class="btn btn-icon btn-sm btn-outline-default bg-white px-2" title="Biểu đồ giá" href="javascript:void(0);" onClick="$Core.helper.open_stock_chart(this,event)" stock_id="'.$stock_id.'">'.$clsISO->makeIcon('bx-bar-chart-alt fs-14','').'</a>':'').' 
									<a class="btn btn-sm btn-icon btn-outline-default bg-white px-2" title="Tính lãi vay" href="javascript:void(0);" onClick="$Core.calculator.open(this,event)" stock_id="'.$stock_id.'">'.$clsISO->makeIcon('bx bx-calculator fs-14','').'</a>
									'.($clsISO->checkDEV()?'<a class="btn btn-sm btn-icon btn-outline-default bg-white px-2" title="Hiệu suất đầu tư" href="javascript:void(0);" onClick="$Core.performance.open(this,event)" stock_id="'.$stock_id.'">'.$clsISO->makeIcon('bx-line-chart fs-14','').'</a>':'').(($clsISO->checkSale()) ? $html_request_ptg : "").$html_compare.'
								</div>
								'.$html_price_more.'
								'.$img_price_sheets.'
								'.($permiss_edit_stock_advanced || $permiss_hide_stock_globe || $permiss_upload_poster ? '
								<div class="d-flex flex-wrap gap-1 fs-12 mt-2 justify-content-center">
									'.((!$clsISO->checkSale()) ? $html_request_ptg : "").'
									'.$html_button_more.'
								</div>':'').'
							</div>'.$html_zalo.'
							'.($stock_type==_BLOCK_TYPE_HIGHLEVEL_SALE ? '
							<div class="py-3 mb-2 bg-lighter">
								<div class="w-full mb-3 d-flex">
									<div class="x-box flex-fill text-center">
										<p class="text-muted mb-1"><i class="d-inline-block re__icon-bedroom--sm"></i> Loại căn</p>
										<h4 class="fs-14 mb-0"><a class="text-primary  text-nowrap" href="'.$clsStock->getLinkSearch($building_id,$stock_id,"bedroom").'" target="_blank">'.$clsProperty->getTitle($bedroom_id).'<i class="bx bx-link-external fs-6 ml-1"></i></a></h4>
									</div>
									<div class="x-box flex-fill text-center">
										<p class="text-muted mb-1"><i class="d-inline-block re__icon-ying-yang--xl"></i> Hướng</p>
										<h4 class="fs-14 mb-0"><a class="text-primary text-nowrap" href="'.$clsStock->getLinkSearch($building_id,$stock_id,"direction").'" target="_blank">'.$clsProperty->getTitleQR($home_direction_id).'<i class="bx bx-link-external fs-6 ml-1"></i></a></h4>
									</div>
									<div class="x-box flex-fill text-center">
										<p class="text-muted mb-1"><i class="d-inline-block re__icon-size--sm"></i> Thông thuỷ</p>
										<h4 class="fs-14 mb-0">'.$DT_TT.'m2</h4>
									</div>
									<div class="x-box flex-fill text-center d-none d-lg-block">
										<p class="text-muted mb-1"><i class="d-inline-block re__icon-size--sm"></i> Tim tường</p>
										<h4 class="fs-14 mb-0">'.$DT_Tim.'m2</h4>
									</div>
								</div>
								'.$html_search.'
								<div class="w-full d-flex">
									<div class="x-box flex-fill text-center">
										<p class="text-muted mb-1"><i class="d-inline-block re__icon-radio-checked--sm"></i> Tình trạng</p>
										<h4 class="fs-14 mb-0">
											<span class="label" style="background:'.$oneStatus['bgcolor'].'; color:'.$oneStatus['textcolor'].'">'.$status_name.'</span>
										</h4>
									</div>
									<div class="x-box flex-fill text-center">
										<p class="text-muted mb-1"><i class="d-inline-block re__icon-sun--sm"></i> Loại hình</p>
										<h4 class="fs-14 mb-0">'.($clsStock->checkStockFundType($stock_id,$building_id,$oneStock,$oBuilding) ? '<span class="label" style="background:#28ff00; color:#333">Thứ cấp</span>' : ('<span class="label" style="background:'.$oneType['bgcolor'].';color:'.$oneType['textcolor'].'">'.$clsProperty->getLoaiHinh($type_id, $oneType).'</span>')).'</h4>
									</div>
									<div class="x-box flex-fill text-center d-none d-lg-block">
										<p class="text-muted mb-1"><i class="d-inline-block re__icon-benefit--sm"></i> View</p>
										<h4 class="fs-14 mb-0">'.($view_id > 0 ? $clsProperty->getLoaiHinh($view_id, $oneView) : '--').'</h4>
									</div>
									<div class="x-box flex-fill text-center">
										<p class="text-muted mb-1"><i class="d-inline-block re__icon-money--sm"></i> Giá/m2</p>
										<h4 class="fs-13 mb-0"><span>'.$clsISO->shortNumber($price_m2).'</span></h4>
									</div>
								</div>
								<ul class="mb-1 pl-2 list-unstyled">
									'.$html_price_sheets.'
								</ul>
								<hr class="my-0" />
							</div>':'<div class="w-full py-2 d-flex">
								<div class="x-box flex-fill text-center border-end">
									<p class="text-muted mb-1"><i class="d-inline-block re__icon-bedroom--sm"></i> Loại hình</p>
									<h4 class="fs-14">'.$clsProperty->getTitle($type_id, $oneType).'</h4>
								</div>
								<div class="x-box flex-fill text-center border-end">
									<p class="text-muted mb-1"><i class="d-inline-block re__icon-ying-yang--xl"></i> Hướng</p>
									<h4 class="fs-14 mb-0">'.($home_direction_id > 0 ? $clsProperty->getTitle($home_direction_id) : '---').'</h4>
								</div>
								<div class="x-box flex-fill text-center border-end">
									<p class="text-muted mb-1"><i class="d-inline-block re__icon-size--sm"></i>  Diện tích đất</p>
									<h4 class="fs-14 mb-0">'.$DT_TT.'m<sup>2</sup></h4>
								</div>
								<div class="x-box flex-fill text-center">
									<p class="text-muted mb-1"><i class="d-inline-block re__icon-size--sm"></i> Diện tích XD</p>
									<h4 class="fs-14 mb-0">'.$DT_Tim.'m<sup>2</sup></h4>
								</div>
							</div>
							<hr class="my-2" />
							<div class="w-full py-2 d-flex">
								<div class="x-box flex-fill text-center border-end">
									<p class="text-muted mb-1"><i class="d-inline-block re__icon-radio-checked--sm"></i> Tình trạng</p>
									<h4 class="fs-14 mb-0">
										<span class="label" style="background:'.$oneStatus['bgcolor'].'; color:'.$oneStatus['textcolor'].'">'.$status_name.'</span>
									</h4>
								</div>
								<div class="x-box flex-fill text-center border-end">
									<p class="text-muted mb-1"><i class="d-inline-block re__icon-sun--sm"></i> Phân khu</p>
									<h4 class="fs-14 mb-0">'.$clsProperty->getTitle($block_id, $oneBlock).'</h4>
								</div>
								<div class="x-box flex-fill text-center">
									<p class="text-muted mb-1"><i class="d-inline-block re__icon-sun--sm"></i> Dãy</p>
									<h4 class="fs-14 mb-0">'.$clsProperty->getTitle($building_id, $oBuilding).'</h4>
								</div>
							</div>
							<div class="w-full py-1 d-flex">
								<div class="x-box flex-fill text-center">
									<p class="text-muted mb-1">
										<i class="d-inline-block re__icon-villa--sm"></i> Quỹ đầu tư
									</p>
									<h4 class="fs-14 mb-0">'.$clsProperty->getTitle($invest_fund_id, $oneInvestFund).'</h4>
								</div>
								<div class="x-box flex-fill text-center">
									<p class="text-muted mb-1">
										<i class="d-inline-block re__icon-office--sm"></i> Giỏ bank
									</p>
									<h4 class="fs-14">'.$clsProperty->getTitle($bank_id, $oneBank).'</h4>
								</div>
							</div>
							<ul class="mb-1 pl-2 list-unstyled">
								'.$html_price_sheets.'
							</ul>
							<hr class="my-2" />').'
							<div class="text-muted text-center">
								<span class="fs-12">'.$html_last_updated.'</span>
							</div>
						</div>
					</div>
				</div>
			</div>';	
		}
	}
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function helper_open_stock_sold(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	global $deviceType;
	$clsStock = new Stock();
	$clsPolicy = new Policy();
	$clsProject = new Project();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$uid = $clsISO->getUniqid();
	##
	$field = "{$clsProject->pkey},`title`,`more_information`";
	$list_ad_projects = $clsProject->getAll("`is_trash`=0", $field);
	if(!empty($list_ad_projects)){
		foreach($list_ad_projects as $key => $val){
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$list_admin_id = isset($more_information['list_admin_id']) 
				? $more_information['list_admin_id'] : array();
			$admin_arrs = array();
			if(!empty($list_admin_id)){
				foreach($list_admin_id as $ad_arrs){
					if(!empty($ad_arrs)){
						foreach($ad_arrs as $admin_id){
							if(!in_array($admin_id, $admin_arrs)){
								$admin_arrs[] = $admin_id;
							}
						}
					}
				}
			}
			if(!in_array($profile_id, $admin_arrs)){
				unset($list_ad_projects[$key]);
			} else {
				unset($list_ad_projects[$key]['more_information']);
			}
		}
	}
	// $clsISO->print_pre($list_ad_projects); die();
	$smarty->assign('list_ad_projects', $list_ad_projects);
	// Return
	$smarty->assign('uid', $uid);
	$html = $core->build('helper'.DS.'_ajax.stock_sold.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function helper_do_update_stock_sold(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	global $deviceType;
	#cache log sold
	require_once (DIR_INCLUDES.'/json_master/autoload.php');
	require_once(DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
	$encoder = new Webmozart\Json\JsonEncoder();
	$decoder = new Webmozart\Json\JsonDecoder();
	$cachedFile = DIR_CACHE_JSON.'/crawl/stock/stock_sold_admin.json';
	$arrCacheSold = $decoder->decodeFile($cachedFile);
	$arrCacheSold = !empty($arrCacheSold) ? $arrCacheSold : array();
	#
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	$content = Input::post('content');
	$project_arrs = Input::post('project_arrs');
	###
	$total_updated = 0;
	if(!empty($content)){
		$tmp = preg_split("/\r\n|\n|\r/", $content);
		$sql_string = "`is_trash`=0 AND `status_id`<>'"._STOCK_STATUS_NON_ID."' AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."'";
		if(!empty($project_arrs)) {
			$sql_string.= " AND `project_id` in (".implode(',', $project_arrs).")";
		}
		if(!empty($tmp)){
			foreach($tmp as $val){
				if(!empty($val)){
					$pattern = '/([A-Z0-9]+(?:[A-Z0-9]+))[-\.]?\d{2}[-\.]?\d{2}[A-Z]?$/u';
					@preg_match($pattern, strtoupper($val), $matches);
					if(empty($matches[0])){
						$pattern = '/([A-Z0-9ÁÊÔĐÀÝÈÌ]{1,8}(?:[.-][A-Z0-9ÁÊÔĐÀÝÈÌ]+)*)-\d{1,3}/u'; 
						@preg_match($pattern, strtoupper($val), $matches);
					}
					if(!empty($matches)){
						$ms_code = $matches[0];
//						 $clsISO->print_pre($ms_code); die();
						$field = "{$clsStock->pkey},`status_id`,`more_information`,`stock_type`,`project_id`,`block_id`,`agency_id`";
						$oStock = $clsStock->getByCond("{$sql_string} AND `ms_code`='".strtoupper($ms_code)."'", $field);
//						$clsISO->print_pre($oStock); die();
						if(!empty($oStock)){
							$list_logs = array();
							$more_information = $oStock['more_information'];
							$more_information = $clsISO->to_array_json($more_information);							
							#cache log sold
							if($oStock["stock_type"] == _BLOCK_TYPE_HIGHLEVEL_SALE) {
								$arrCacheSold[$oStock["stock_type"]][$oStock["agency_id"]][$oStock["block_id"]][] = strtoupper($ms_code);
							}else{
								$arrCacheSold[$oStock["stock_type"]][$oStock["agency_id"]][$oStock["project_id"]][] = strtoupper($ms_code);
							}
							###
							$m_field = "{$clsStockMeta->pkey},`logs`"; 
							$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='{$oStock[$clsStock->pkey]}'", $m_field);
							if(!empty($oneStockMeta)){
								$logs = $oneStockMeta['logs'];
								$list_logs = $clsISO->to_array_json($logs);
							} else {
								$clsStockMeta->insert(array(
									'stock_id' => $oStock[$clsStock->pkey],
									'reg_date' => time(),
									'upd_date' => time()
								));
								$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$oStock[$clsStock->pkey]."'", $m_field);
							}
							// $clsISO->print_pre(oStock); die();
							$more_information['user_id_update_sold'] = $profile_id;
							$more_information['status_id'] = _STOCK_STATUS_SOLD_ID;
							$list_logs[$clsISO->getUniqid()] = array(
								'reg_date' => time(), 
								'user_id' => $profile_id,
								'from_id' => $oStock['status_id'],
								'to_id' => _STOCK_STATUS_SOLD_ID,
								'field' => 'status_id'
							);
							if($clsStock->updateOne($oStock[$clsStock->pkey], array(
								'ms_date' => time(),
								'status_id' => _STOCK_STATUS_SOLD_ID,
								'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
							))){
								$total_updated+= 1;
								if(!empty($oneStockMeta) && !empty($logs)){
									$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
										'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
										'upd_date' => time()
									));	
								}
							}
						}
					}
				}
			}
			#save cache log sold
			$encoder->encodeFile($arrCacheSold, $cachedFile);
			#activity log			
			$clsActivityLog = new ActivityLog();
			$log = $clsActivityLog->addActivityLog("Stock","update");
		}
	}
	// Return
	echo $total_updated; die();
}
function helper_load_list_notes(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsCampaign = new Campaign();
	$clsTable = Input::post('clsTable');
	$for_id = (int) Input::post('for_id', 0);
	$clsClassTable = new $clsTable();
	$notes = $clsClassTable->getOneField('notes', $for_id);
	$list_notes = $clsISO->to_array_json($notes);
	// $clsISO->print_pre($list_notes); die();
	$html = '';
	if(!empty($list_notes) && is_array($list_notes)){
		$list_notes = @array_reverse($list_notes);
		$arr_profile_cached = array();
		$html.= '<div class="timeline">';
		foreach($list_notes as $key => $val){
			$user_id = $val['user_id'];
			if($profile_id!=$user_id){
				$arrProfile = $clsProfile->getOne($user_id, "full_name,avatar");
			} else {
				$arrProfile = $oneProfile;
			}
			if(isset($arr_profile_cached[$user_id])){
				$avatar = $arr_profile_cached[$user_id];
			} else {
				$arr_profile_cached[$user_id] = $clsProfile->getAvatar($user_id, $arrProfile);
			}
			$html_campaign = "";
			if($clsTable == "DataCentral") {
				$oneCampaign = $clsCampaign->getOne($val["campaign_id"],"title");
				if(!empty($oneCampaign)) {
					$html_campaign = '<small class="activity-body">
						<span class="mb-0">Chiến dịch: </span>
						<span class="mb-0 text-warning fw-bold">'.$oneCampaign["title"].'</span>
					</small>';
				}				
			}
			$html.= '<div class="timeline-item ">
				<div class="timeline-badge">
					<img class="avatar avatar-sm mr-2 rounded-pill" src="'.$arr_profile_cached[$user_id].'" 
					onerror="this.src=\''.URL_IMAGES.'/no-avatar.jpg\'" />
				</div>
				<div class="timeline-body">
					'.$html_campaign.'
					<div class="timeline-body-head d-flex justify-content-between">
						<div class="timeline-body-head-caption d-flex align-items-center">
							<span class="timeline-body-alerttitle font-green-haze mr-2">
								'.$arrProfile['full_name'].'</span>
							<small>'.$clsISO->getTimeAgo($val['reg_date']).'</small>
						</div>
						<div clas="timeline-body-head-action">
							<div class="dropdown dropdown-action">
								<button class="btn p-0 hide-arrow dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
									<i class="bx bx-dots-vertical-rounded"></i>
								</button> 
								<div class="dropdown-menu">';
								if(($user_id==$profile_id) && (($val['reg_date'] + 60*60) > time())){
									$html.= '<a href="javascript:void(0);" class="dropdown-item" onClick="$Core.helper.edit_notes(this,event);" note_id="'.$key.'" for_id="'.$for_id.'"><span>Sửa</span></a>
									<a href="javascript:void(0);" onClick="$Core.helper.delete_notes(this,event);" clsTable="'.$clsTable.'" class="dropdown-item" note_id="'.$key.'" for_id="'.$for_id.'"><span>Xóa</span></a>';
								}
								$html .= '</div>
							</div>
						</div>
					</div>
					<div class="timeline-body-content font-grey-cascade">
						<div class="timeline-body-content__'.$key.'">'.nl2br($val['content']).'</div>
						<div class="timeline-body-edit__'.$key.' d-none">
							<form class="frmIssue" name="" action="">
								<textarea class="form-control" name="content" rows="3" placeholder="Nhập ghi chú">'.$val['content'].'</textarea>
								<div class="clearfix mt-2">
									<button type="button" onClick="$Core.helper.cancel_notes(this,event)" class="btn btn-sm btn-outline-danger" for_id="'.$for_id.'" note_id="'.$key.'"><i class="icon-ok icon-white"></i> Hủy</button>
									<button type="button" onClick="$Core.helper.save_notes(this,event)" clsTable="'.$clsTable.'" tp="_update" class="btn btn-sm btn-outline-primary" for_id="'.$for_id.'" note_id="'.$key.'"><i class="icon-ok icon-white"></i> Lưu</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>';
		}
		$html .= '</div>';
	} else {
		$html.= '<div class="p-3 no-result text-center">
			<img src="'.URL_IMAGES.'/notes.png" width="60px" />
			<p class="text-muted mt-2">Không có ghi chú nào được tạo</p>
		</div>';
	}
	// return
	echo $html; die();
}
function helper_save_notes(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	$clsTable = Input::post('clsTable');
	$for_id = (int) Input::post('for_id', 0);
	$clsClassTable = new $clsTable();
	$action = Input::post('action', "");
	$note_id = Input::post('note_id', "");
	$notes = $clsClassTable->getOneField('notes', $for_id);
	$list_notes = $clsISO->to_array_json($notes);
	###
	if(!empty($note_id)){
		if(!empty($list_notes) && @array_key_exists($note_id, $list_notes)){
			if($action=="_delete"){
				unset($list_notes[$note_id]);	
				$_action = "delete";
			} else {
				$_action = "update";
				$_POST["notes"] = $list_notes[$note_id]['content'];
				$content = Input::post('content');
				$list_notes[$note_id]['content'] = $content;
				$list_notes[$note_id]['upd_date'] = time();
				$list_notes[$note_id]['user_id_update'] = $profile_id;
			}
		}
	} else {
		$content = Input::post('content');
		$list_notes[$clsISO->getUniqid()] = array(
			'content' => $content,
			'reg_date' => time(),
			'upd_date' => time(),
			'user_id' => $profile_id,
			'user_id_update' => $profile_id
		);
		$_action = "insert";
		$_POST["notes"] = $content;
	}
	$oneItem = $clsClassTable->getOne($for_id);
	$msg = '_error';
	if($clsClassTable->updateOne($for_id, array(
		'notes' => json_encode($list_notes, JSON_UNESCAPED_UNICODE)
	))){
		$msg = '_success';	
		if(get_class($clsClassTable) == "Billing" ) {
			#activity log			
			$clsActivityLog = new ActivityLog();
			$log = $clsActivityLog->addActivityLog("Billing","update");
		}	
	}
	// Return
	echo $msg; die();
}
function helper_delete_file(){
	global $core,$_frontIsLoggedin_user_id,$clsISO;
	$for_id  = Input::post('for_id');
	$file_id = Input::post('file_id');
	$clsTable = Input::post('clsTable');
	###
	$clsClassTable = new $clsTable();
	$files = $clsClassTable->getOneField('files', $for_id);
	$list_files = !empty($files) 
		? json_decode(html_entity_decode($files), true) : array();
	###
	$msg = "_error";
	if(!empty($file_id) && !empty($list_files) 
		&& array_key_exists($file_id, $list_files)){
		unset($list_files[$file_id]);
		if($clsClassTable->updateOne($for_id, array(
			'files' => json_encode($list_files, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
			if(get_class($clsClassTable) == "Billing" ) {
				#activity log			
				$clsActivityLog = new ActivityLog();
				$log = $clsActivityLog->addActivityLog("Billing","update");
			}
		}	
	}
	// Return
	echo $msg; die();
}
function helper_uploadImage(){
	global $core,$_frontIsLoggedin_user_id,$clsISO,$profile_id;
	$image = '';
	$tp = Input::post('tp', 'sheet_price');
	if(is_uploaded_file($_FILES['image']['tmp_name'])){
		$clsUploadFile = new UploadFile();
		if($tp == "video") {
			$image = $clsUploadFile->uploadItem($_FILES["image"],"/video",EXTENSION_VIDEO_UPLOAD);
		}else{
			$image = $clsUploadFile->uploadItem($_FILES["image"],"/PTG",EXTENSION_FILE_UPLOAD);
		}
		if(!empty($image) && file_exists(ROOTPATH . $image)){
			// Set the file metadata for drive
			$title = $_FILES["image"]["name"];
			$mimeType = $_FILES["image"]["type"];
			$clsGoogleDrive = new GoogleDrive();
			if($tp=='gd'){
				$createdFile = $clsGoogleDrive->upload($title, $mimeType, ROOTPATH.$image, GOOGLE_DRIVE_FOLDER_TTDG_ID);
			} else if($tp=='sheet_price'){
				$createdFile = $clsGoogleDrive->upload($title, $mimeType, ROOTPATH.$image);
			} else if($tp=='video'){
				$createdFile = $clsGoogleDrive->upload($title, $mimeType, ROOTPATH.$image, GOOGLE_DRIVE_FOLDER_POSTER_VIDEO_ID);
			} else {
				$createdFile = $clsGoogleDrive->upload($title, $mimeType, ROOTPATH.$image, GOOGLE_DRIVE_FOLDER_POSTER_ID);
			}
			@unlink(ROOTPATH . $image);
			$image = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
		}
	}
	// Return
	echo $image; die();
}
function helper_soldout_stock(){
	global $_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO,$profile_id,$oneProfile,$deviceType;
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	$clsNotify = new Notify();
	$clsFcmToken = new FcmToken();
	$clsProperty = new Property();
	$clsRequestPTG = new RequestPTG();
	#
	$msg = "_error";
	$stock_id = Input::post('stock_id', 0);
	$request_ptg_id = Input::post('request_ptg_id', 0);
	$oStock = $clsStock->getOne($stock_id, "stock_type,ms_code,type_id,status_id,more_information");
	$oneRequest = $clsRequestPTG->getOne($request_ptg_id);
	#
	$m_field = "{$clsStockMeta->pkey},`logs`";
	$oneStockMeta = $clsStockMeta->getByCond("stock_id='{$stock_id}'", $m_field);
	if(!empty($oneStockMeta)){
		$logs = $oneStockMeta['logs'];
	} else {
		$clsStockMeta->insert(array(
			'stock_id' => $stock_id,
			'reg_date' => time(),
			'upd_date' => time()
		));
		$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='{$stock_id}'", $m_field);
		$logs = $oneStockMeta['logs'];
	}
	$type_id = $oStock['type_id'];
	$more_information = $oStock['more_information'];
	$logs = $clsISO->to_array_json($logs);
	$more_information = $clsISO->to_array_json($more_information);
	if($oStock['status_id'] != _STOCK_STATUS_SOLD_ID){
		$logs[$clsISO->getUniqid()] = array(
			'is_fontpage' => 1,
			'reg_date' => $profile_id,
			'from_id' => $oStock['status_id'],
			'to_id' => _STOCK_STATUS_SOLD_ID,
			'field' => 'status_id'
		);
	}
	// $clsISO->print_pre($oneRequest); die();
	$more_information['status_id'] = _STOCK_STATUS_SOLD_ID;
	if($clsStock->updateOne($stock_id, array(
		'status_id' => 	_STOCK_STATUS_SOLD_ID,
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))){
		$msg = "_success";
		if(!empty($oneStockMeta) && !empty($logs)){
			$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
				'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
				'upd_date' => time()
			));
		}
		$clsRequestPTG->updateOne($request_ptg_id, array(
			'status_id' => 1,
			'user_updated_id' => $profile_id,
			'upd_date' => time()
		));
		if($oStock['stock_type'] == _BLOCK_TYPE_HIGHLEVEL_SALE){
			$titleNoty = sprintf("Căn hộ [%s] đã bán", $oStock['ms_code']);
		} else {
			$titleNoty = sprintf("%s [%s] đã bán", $clsProperty->getTitle($type_id), $oStock['ms_code']);
		}
		if($oneRequest["_from"] == 1) {
			$list_request_notify = array();
			$clsNotify->insertNotify('Stock',$clsStock->pkey, $stock_id, $titleNoty, time(), $oneRequest['user_id']);
			$clsNotify->sendEmailRequestPTG($oneRequest['user_id'],$oStock['ms_code'],"soldout");
			$list_request_notify = $clsNotify->getAll("`tbl`='RequestPTG' and `pkey`='id' and `pval`='{$request_ptg_id}'");
			if(!empty($list_request_notify)){
				foreach($list_request_notify as $key => $val){
					$clsNotify->updateOne($val[$clsNotify->pkey], array(
						'list_user_read' => $val['list_user_slash']
					));
				}
				unset($list_request_notify);
			}
			$subscribers = array();
			$tmp = $clsFcmToken->getAll("`push_type`='_pushalert' 
				and `user_id`='{$oneRequest['user_id']}' and `token`<>''", "token");
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					if(!in_array($val['token'], $subscribers)){
						$subscribers[] = $val['token'];
					}
				}
				$clsNotify->send_subscriber_notification(array(
					'title' => "Cập nhật phiếu tính giá",
					'message' => $titleNoty,
					'url' => PCMS_URL
				), $subscribers);
			}
			#thong bao app
			$clsNotification = new Notification();
			$params = [
				'title' => "Cập nhật phiếu tính giá",
				'body' => strip_tags($titleNoty),
				'link' => PCMS_URL
			];
			$clsNotification->doPushMessagingUser($params,[$oneRequest['user_id']]);
		}else{
			$clsNotifyMOC = new NotifyMOC();
			$clsNotifyMOC->insertNotify('Stock',$clsStock->pkey, $stock_id, $titleNoty, time(), $oneRequest['user_id']);
			$list_request_notify_MOC = array();
			$list_request_notify_MOC = $clsNotifyMOC->getAll("`tbl`='RequestPTG' and `pkey`='id' and `pval`='{$request_ptg_id}'");
			if(!empty($list_request_notify_MOC)){
				foreach($list_request_notify_MOC as $key => $val){
					$clsNotifyMOC->updateOne($val[$clsNotifyMOC->pkey], array(
						'list_user_read' => $val['list_user_slash']
					));
				}
				unset($list_request_notify_MOC);
			}
			$clsNotifyMOC->sendEmailRequestPTG($oneRequest['user_id'],$oStock['ms_code'],"soldout");
		}
		#activity log	
		$clsActivityLog = new ActivityLog();
		$log = $clsActivityLog->addActivityLog("Stock","update",["tp"=> "status","ms_code"=>$oStock['ms_code']]);
	}
	// Return
	echo $msg; die();
}
function helper_open_quick_stock(){
	global $_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsISO,$profile_id,$oneProfile,$deviceType;
	$clsStock = new Stock();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$uid = $clsISO->getUniqid();
	$tp = Input::post('tp', "");
	$from = Input::post('from', "");
	$stock_id = (int) Input::post('stock_id', 0);
	###
	if($tp == "sheet_price") {
		$titlePage = 'Phiếu tính giá';
	}else if($tp == "poster") {
		$titlePage = 'Poster';
	}else if($tp == "video") {
		$titlePage = 'Video';
	}else{
		$titlePage = 'Tình trạng';
	}
	// $titlePage = ($tp=='sheet_price' ? 'Phiếu tính giá' : ($tp=='poster' ? 'Poster': 'Tình trạng')) ;
	$html = '<div class="modal-dialog'.($tp=='sheet_price'?'':' modal-sm').'">
	<form class="d-none" enctype="multipart/form-data">
		<input id="select_image_'.$uid.'" accept="image/jpeg,image/jpg,image/png,application/pdf" 
		type="file" onchange="$Core.helper.select_image_upload(this,event)" charset="UTF-8" name="image" />
		<input id="select_video_'.$uid.'" accept="video/*" 
		type="file" tp="video" onchange="$Core.helper.select_image_upload(this,event)" charset="UTF-8" name="image" />
	</form>
	<form method="POST" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Cập nhật '.$titlePage.'<br />
				<span class="text-danger fs-13">
					'.$clsISO->makeIcon('bx-user-plus', 'Người cập nhật: ').'
					'.$clsProfile->getFullName($profile_id,$oneProfile).'
				</span>
			</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">';
		if($tp=='sheet_price'){
			$html.='<div class="form-group mb-2">
				<div class="form-floating">
					<input type="text" class="form-control datepicker" name="csbh" id="ipn_'.$uid.'" placeholder="dd/mm/yy" value="">
					<label for="ipn_'.$uid.'">CSBH ngày</label>
				</div>
			</div>';
			if($deviceType=='phone'){
				$html.='<div class="gbox tr_stock_price_'.$uid.' mb-2">
					<div class="gbox-body">
						<div class="form-group mb-2">
							<label class="col-form-label mr-2">Tên phiên bản</label>
							<input type="text" placeholder="Nhập tên..." name="stock_price['.$uid.'][title]" class="form-control required" maxlength="255" value="Tiêu chuẩn" />
						</div>			
						<div class="form-group mb-2">
							<label class="col-form-label mr-2">File upload</label>
							<div class="input-group">
								<input type="text" placeholder="Nhập ảnh..." name="stock_price['.$uid.'][image]" class="form-control required stock_price_image_'.$uid.'" maxlength="255" uid="'.$uid.'" tp="'.$tp.'" onPaste="$Core.helper.upload_image_clipboard(this, event)" />
								<button type="button" toId="select_image_'.$uid.'" uid="'.$uid.'" onClick="$Core.helper.select_image(this, event)" tp="'.$tp.'" stock_id="'.$stock_id.'" class="btn btn-outline-default">'.$core->makeIcon('upload','Chọn ảnh').'</button>
							</div>
						</div>
					</div>
				</div>';
			} else {
				$html.= '<div class="table-wrapper mb-2">
				<table class="table table-vertical table-bordered" width="100%">
					<thead><tr>
						<th width="30%" class="text-left">Tên phiên bản</th>
						<th class="text-left">File upload</th>
						<th width="60px"></th>
					</tr></thead>
					<tr class="tr_stock_price_'.$uid.'">
						<td class="text-left">
							<input type="text" placeholder="Nhập tên..." name="stock_price['.$uid.'][title]" class="form-control required" maxlength="255" value="PTG TẠM TÍNH" />
						</td>
						<td class="text-left">
							<div class="input-group">
								<input type="text" placeholder="Nhập ảnh..." name="stock_price['.$uid.'][image]" class="form-control required stock_price_image_'.$uid.'" onPaste="$Core.helper.upload_image_clipboard(this, event)" tp="'.$tp.'" uid="'.$uid.'" maxlength="255" onPaste="$Core.helper.upload_image_clipboard(this, event)" />
								<button type="button" toId="select_image_'.$uid.'" uid="'.$uid.'" onClick="$Core.helper.select_image(this, event)" stock_id="'.$stock_id.'" tp="'.$tp.'" class="btn btn-outline-default">'.$core->makeIcon('upload','Chọn ảnh').'</button>
							</div>
						</td>
						<td class="text-center"></td>
					</tr>
				</table></div>';
			}
			$html.= '<div class="d-flex">
				<button type="button" onClick="$Core.helper.stock_price_addline(this, event)" 
				stock_id="'.$stock_id.'" toId="'.$uid.'" tp="'.$tp.'" class="btn btn-default">+ Thêm phiên bản</button>
			</div>';
		} else if($tp=='update_status'){
			$field = "{$clsStock->pkey},stock_type,block_id,status_id,agency_id,more_information";
			$oneStock = $clsStock->getOne($stock_id, $field);
			$block_id = (int) $oneStock['block_id'];
			$stock_type = (int) $oneStock['stock_type'];
			$more_information = $oneStock['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$html.= '<div class="form-floating w-100 mb-2">
				<input type="text" name="price_field[total_price_vat]" value="'.(!empty($more_information['total_price_vat'])?$more_information['total_price_vat']:0).'" class="form-control numberonly price-In" />
				<label>Giá Full VAT</label>
			</div>';
			if($stock_type==_BLOCK_TYPE_LOWFLOOR_SALE){
				$block_information = $clsProperty->getOneField('more_information', $block_id);
				$block_information = $clsISO->to_array_json($block_information);
				$price_field_configs = isset($block_information['price_field_configs']) 
					? $block_information['price_field_configs'] : array();
				if(!empty($price_field_configs)){
					foreach($price_field_configs as $key => $val){
						if(isset($val['status']) && $val['status']==1){
							$html.= '<div class="form-floating w-100 mb-2">
								<input type="text" name="price_field['.$key.']" value="'.(!empty($more_information[$key])?$more_information[$key]:0).'" class="form-control numberonly price-In" />
								<label>'.$val['title'].'</label>
							</div>';
						}
					}
				}
			} else {
				$block_information = $clsProperty->getOneField('more_information', $block_id);
				$block_information = $clsISO->to_array_json($block_information);
				$price_field_configs = isset($block_information['price_field_configs']) 
					? $block_information['price_field_configs'] : array();
				if(!empty($price_field_configs)){
					foreach($price_field_configs as $key => $val){
						if(isset($val['status']) && $val['status'] == 1){
							$html.= '<div class="form-floating w-100 mb-2">
								<input type="text" name="price_field['.$key.']" value="'.(!empty($more_information[$key])?$more_information[$key]:0).'" 
								class="form-control numberonly price-In" />
								<label>'.$val['title'].'</label>
							</div>';
						}
					}
				}
			}
			$html.= '<hr class="my-2" />
			<div class="form-floating w-100 mb-2">
				<select class="form-control w-100 form-select" name="agency_id">
					'.$clsProperty->getSelectSingleProperty('_AGENCY', 0, $oneStock['agency_id']).'
				</select>
				<label>Đại lý</label>
			</div>
			<div class="form-floating w-100 mb-2">
				<select class="form-control w-100 form-select" name="status_id">
					'.$clsProperty->getSelectSingleProperty('_STATUS', 0, $oneStock['status_id']).'
				</select>
				<label>Tình trạng</label>
			</div>';
		} else if($tp=='video') {
			$more_information = $clsStock->getOneField('more_information', $stock_id);
			$more_information = $clsISO->to_array_json($more_information);
			$list_stock_poster_video = isset($more_information['stock_poster_video']) 
				? $more_information['stock_poster_video'] : array();
			if(!empty($list_stock_poster_video)){ $ii = 0;
				foreach($list_stock_poster_video as $gId => $val){
					$html.= '<div class="gbox tr_stock_poster_video_'.$uid.' tr_stock_poster_video_'.$gId.' mb-2">
						<div class="gbox-body">
							<div class="form-group mb-2">
								<input type="text" placeholder="Tiêu đề..." name="stock_poster_video['.$gId.'][title]" class="form-control required" maxlength="255" value="'.$val['title'].'" />
							</div>			
							<div class="form-group'.($ii>0?' mb-2':'').'">
								<div class="input-group">
									<input type="text" placeholder="Tải video..." name="stock_poster_video['.$gId.'][video]" class="form-control required stock_poster_video_'.$gId.'" maxlength="255" value="'.$val['video'].'" onPaste="$Core.helper.upload_image_clipboard(this, event)" tp="'.$tp.'" uid="'.$uid.'" />
									<button type="button" toId="select_video_'.$uid.'" uid="'.$gId.'" onClick="$Core.helper.select_video(this, event)" stock_id="'.$stock_id.'" tp="'.$tp.'" class="btn btn-icon btn-outline-default">'.$core->makeIcon('upload').'</button>
								</div>
							</div>
							'.($ii>0?'<div class="form-group">
								<button type="button" onClick="$Core.helper.stock_poster_deleteline(this, event)" uid="'.$gId.'" toId="'.$stock_id.'" class="btn btn-outline-default">'.$clsISO->makeIcon('bx-trash-alt', 'Xóa dòng').'</button>
							</div>':'').'
						</div>
					</div>';
					++$ii;
				}
			} else {
				$html.= '<div class="gbox tr_stock_poster_video_'.$uid.' mb-2">
					<div class="gbox-body">
						<div class="form-group mb-2">
							<input type="text"  name="stock_poster_video['.$uid.'][title]" class="form-control required" 
							placeholder="Tiêu đề..." maxlength="255" value="Video chỉ căn" />
						</div>			
						<div class="form-group">
							<div class="input-group">
								<input type="text" onPaste="$Core.helper.upload_image_clipboard(this, event)" name="stock_poster_video['.$uid.'][video]" placeholder="Tải video..." tp="'.$tp.'" uid="'.$uid.'" class="form-control required stock_poster_video_'.$uid.'" maxlength="255" />
								<button type="button" toId="select_video_'.$uid.'" uid="'.$uid.'" onClick="$Core.helper.select_image(this, event)" stock_id="'.$stock_id.'" tp="'.$tp.'" class="btn btn-icon btn-outline-default">'.$core->makeIcon('upload').'</button>
							</div>
						</div>
					</div>
				</div>';
			}
		} else {
			$more_information = $clsStock->getOneField('more_information', $stock_id);
			$more_information = $clsISO->to_array_json($more_information);
			$list_stock_poster = isset($more_information['stock_poster']) 
				? $more_information['stock_poster'] : array();
			if(!empty($list_stock_poster)){ $ii = 0;
				foreach($list_stock_poster as $gId => $val){
					$html.= '<div class="gbox tr_stock_poster_'.$uid.' tr_stock_poster_'.$gId.' mb-2">
						<div class="gbox-body">
							<div class="form-group mb-2">
								<input type="text" placeholder="Tiêu đề..." name="stock_poster['.$gId.'][title]" class="form-control required" maxlength="255" value="'.$val['title'].'" />
							</div>			
							<div class="form-group'.($ii>0?' mb-2':'').'">
								<div class="input-group">
									<input type="text" placeholder="Tải ảnh..." name="stock_poster['.$gId.'][image]" class="form-control required stock_poster_image_'.$gId.'" maxlength="255" value="'.$val['image'].'" onPaste="$Core.helper.upload_image_clipboard(this, event)" tp="'.$tp.'" uid="'.$gId.'" />
									<button type="button" toId="select_image_'.$uid.'" uid="'.$gId.'" onClick="$Core.helper.select_image(this, event)" stock_id="'.$stock_id.'" tp="'.$tp.'" class="btn btn-icon btn-outline-default">'.$core->makeIcon('upload').'</button>
								</div>
							</div>
							'.($ii>0?'<div class="form-group">
								<button type="button" onClick="$Core.helper.stock_poster_deleteline(this, event)" uid="'.$gId.'" toId="'.$stock_id.'" class="btn btn-outline-default">'.$clsISO->makeIcon('bx-trash-alt', 'Xóa dòng').'</button>
							</div>':'').'
						</div>
					</div>';
					++$ii;
				}
			} else {
				$html.= '<div class="gbox tr_stock_poster_'.$uid.' mb-2">
					<div class="gbox-body">
						<div class="form-group mb-2">
							<input type="text"  name="stock_poster['.$uid.'][title]" class="form-control required" 
							placeholder="Tiêu đề..." maxlength="255" value="Poster Chuẩn" />
						</div>			
						<div class="form-group">
							<div class="input-group">
								<input type="text" onPaste="$Core.helper.upload_image_clipboard(this, event)" name="stock_poster['.$uid.'][image]" placeholder="Tải ảnh..." tp="'.$tp.'" uid="'.$uid.'" class="form-control required stock_poster_image_'.$uid.'" maxlength="255" />
								<button type="button" toId="select_image_'.$uid.'" uid="'.$uid.'" onClick="$Core.helper.select_image(this, event)" stock_id="'.$stock_id.'" tp="'.$tp.'" class="btn btn-icon btn-outline-default">'.$core->makeIcon('upload').'</button>
							</div>
						</div>
					</div>
				</div>';
			}
			$html.= '<div class="d-flex">
				<button type="button" onClick="$Core.helper.stock_poster_addline(this, event)" 
				stock_id="'.$stock_id.'" toId="'.$uid.'" tp="'.$type.'" class="btn btn-outline-default">+ Thêm Poster</button>
			</div>';
		}
		$html.= '</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" onClick="$Core.helper.update_quick_stock(this, event)" tp="'.$tp.'" 
			stock_id="'.$stock_id.'" uid="'.$uid.'" from="'.$from.'" class="btn btn-primary">Lưu lại</button>
		</div>
	</form></div>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function helper_stock_price_addline(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO,$deviceType;
	###
	$tp = Input::post('tp');
	$toId = Input::post('toId');
	$uid = $clsISO->getUniqid();
	$stock_id = (int) Input::post('stock_id', 0);
	if($deviceType=='phone'){
		$html = '<div class="gbox tr_stock_price_'.$toId.' tr_stock_price_'.$uid.' mb-2">
			<div class="gbox-body">
				<div class="form-group mb-2">
					<label class="col-form-label mr-2">Tên phiên bản</label>
					<input type="text" placeholder="Nhập tên..." name="stock_price['.$uid.'][title]" class="form-control required" maxlength="255" value="Tiêu chuẩn" />
				</div>			
				<div class="form-group mb-2">
					<label class="col-form-label mr-2">File upload</label>
					<div class="input-group">
						<input type="text" placeholder="Nhập ảnh..." name="stock_price['.$uid.'][image]" class="form-control required stock_price_image_'.$uid.'" uid="'.$uid.'" tp="'.$tp.'" onPaste="$Core.helper.upload_image_clipboard(this, event)" />
						<button type="button" toId="select_image_'.$toId.'" uid="'.$uid.'" onClick="$Core.helper.select_image(this, event)" stock_id="'.$stock_id.'" tp="sheet_price" class="btn btn-outline-default">'.$core->makeIcon('upload','Chọn ảnh').'</button>
					</div>
				</div>
				<div class="form-group">
					<button type="button" onClick="$Core.helper.stock_price_deleteline(this, event)" uid="'.$uid.'" toId="'.$stock_id.'" class="btn btn-outline-default">'.$clsISO->makeIcon('bx-trash-alt', 'Xóa dòng').'</button>
				</div>
			</div>
		</div>';
	} else {
		$html = '<tr class="tr_stock_price_'.$toId.' tr_stock_price_'.$uid.'">
			<td class="text-left">
				<input type="text" placeholder="Nhập tên..." name="stock_price['.$uid.'][title]" 
				class="form-control required" maxlength="255" />
			</td>
			<td class="text-left">
				<div class="input-group">
					<input type="text" placeholder="Nhập ảnh..." name="stock_price['.$uid.'][image]" 
					class="form-control required stock_price_image_'.$uid.'" tp="'.$tp.'" uid="'.$uid.'" maxlength="255" onPaste="$Core.helper.upload_image_clipboard(this, event)" />
					<button type="button" toId="select_image_'.$toId.'" uid="'.$uid.'" onClick="$Core.helper.select_image(this, event)" stock_id="'.$stock_id.'" class="btn btn-outline-default">'.$core->makeIcon('upload','Chọn ảnh').'</button>
				</div>
			</td>
			<td class="text-center">
				<button type="button" tp="'.$tp.'" onClick="$Core.helper.stock_price_deleteline(this, event)" uid="'.$uid.'" toId="'.$stock_id.'" class="btn p-2 btn-default">'.$clsISO->makeIcon('bx-trash-alt').'</button>
			</td>
		</tr>';
	}
	// return
	echo $html; die();
}
function helper_stock_poster_addline(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO,$deviceType;
	###
	$toId = Input::post('toId');
	$uid = $clsISO->getUniqid();
	$stock_id = (int) Input::post('stock_id', 0);
	$html = '<div class="gbox tr_stock_poster_'.$toId.' tr_stock_poster_'.$uid.' mb-2">
		<div class="gbox-body">
			<div class="form-group mb-2">
				<input type="text" placeholder="Nhập tên..." name="stock_poster['.$uid.'][title]" 
					class="form-control required" maxlength="255" />
			</div>			
			<div class="form-group mb-2">
				<div class="input-group">
					<input type="text" placeholder="Nhập ảnh..." name="stock_poster['.$uid.'][image]" class="form-control required stock_poster_image_'.$uid.'" tp="poster" uid="'.$uid.'" onPaste="$Core.helper.upload_image_clipboard(this, event)" />
					<button type="button" toId="select_image_'.$toId.'" uid="'.$uid.'" onClick="$Core.helper.select_image(this, event)" stock_id="'.$stock_id.'" tp="poster" class="btn btn-icon btn-default">'.$core->makeIcon('upload').'</button>
				</div>
			</div>
			<div class="form-group">
				<button type="button" onClick="$Core.helper.stock_poster_deleteline(this, event)" uid="'.$uid.'" toId="'.$stock_id.'" class="btn btn-outline-default">'.$clsISO->makeIcon('bx-trash-alt', 'Xóa dòng').'</button>
			</div>
		</div>
	</div>';
	// return
	echo $html; die();
}
function helper_update_quick_stock(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$core,
	$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	$clsZalo = new Zalo();
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	$clsNotify = new Notify(); 
	$clsNotifyMOC = new NotifyMOC(); 
	$clsRequestPTG = new RequestPTG();
	$clsProfile = new Profile();
	$clsFcmToken = new FcmToken(); 
	$clsProperty = new Property(); 
	$uid = Input::post('uid');
	$tp = Input::post('tp', 'update_status');
	###
	$stock_id = (int) Input::post('stock_id', 0);
	$oStock = $clsStock->getOne($stock_id, "status_id,block_id,agency_id,more_information,ms_code");
	$block_id = $oStock['block_id'];
	$more_information = $oStock['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	###
	$logs = array(); $m_field = "{$clsStockMeta->pkey},logs";
	$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='{$stock_id}'", $m_field);
	if(!empty($oneStockMeta)){
		$logs = $oneStockMeta['logs'];
		$logs = $clsISO->to_array_json($logs);
	} else {
		$clsStockMeta->insert(array(
			'stock_id' => $stock_id,
			'reg_date' => time(),
			'upd_date' => time()
		));
		$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='{$stock_id}'", $m_field);
	}
	###
	$msg = "_error";
	if($tp=='sheet_price'){
		$csbh = Input::post('csbh');
		$stock_price = Input::post('stock_price');
		$price_sheets = $core->get_field($more_information, "price_sheets", []);
		$price_sheets[$uid]['is_frontpage'] = 1;
		$price_sheets[$uid]['csbh'] = $clsISO->toTime($csbh);
		$price_sheets[$uid]['sheets'] = $stock_price;
		$price_sheets[$uid]['reg_date'] = time();
		$price_sheets[$uid]['upd_date'] = time();
		$price_sheets[$uid]['user_id'] = $profile_id;
		$price_sheets[$uid]['user_update_id'] = $profile_id;
		$more_information['hide_price_sheets'] = 0;
		$more_information['price_sheets'] = $price_sheets;
		// $clsISO->print_pre($more_information); die();
		if($clsStock->updateOne($stock_id, array(
			'upd_date' => time(),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$msg = '_success';
			#activity log			
			$clsActivityLog = new ActivityLog();
			$log = $clsActivityLog->addActivityLog("Stock","update",["tp"=>"PTG","ms_code"=>$oStock['ms_code']]); 
			#cập nhật request PTG và gửi thông báo
			if($profile_id != _PROFILE_TECH_ID){
				$arr_user_request = $arr_request_ids = $arr_user_requestMOC = $arr_request_MOC_id = array();
				$tmp = $clsRequestPTG->getAll("`stock_id`='{$stock_id}' AND `status_id`='0' AND `_from`='1'","{$clsRequestPTG->pkey},`user_id`");
				$set = "`status_id`='1',`user_updated_id`='{$profile_id}',`upd_date`='".time()."'";
				$clsRequestPTG->updateByCond("`stock_id`='{$stock_id}' AND `status_id`='0'", $set);
				if(!empty($tmp)){
					foreach($tmp as $k => $v) {
						if($v['_from'] == 1) {
							$arr_request_ids[] = $v[$clsRequestPTG->pkey];
							if(!$clsISO->checkItemInArray($v['user_id'], $arr_user_request)) {
								$arr_user_request[] = $v['user_id'];
								$clsNotify->sendEmailRequestPTG($v['user_id'],$oStock['ms_code']);
							}
						}else{
							$arr_request_MOC_id[] = $v[$clsRequestPTG->pkey];
							if(!$clsISO->checkItemInArray($v['user_id'],$arr_user_requestMOC)) {
								$arr_user_requestMOC[] = $v['user_id'];
							}
						}
					}
					$list_request_notify = array();
					if(!empty($arr_request_id)) {
						$list_request_notify = $clsNotify->getAll("`tbl`='RequestPTG' and `pkey`='id' 
							and `pval` in (".implode(',',$arr_request_ids).")");
						if(!empty($list_request_notify)){
							foreach($list_request_notify as $key => $val){
								$clsNotify->updateOne($val[$clsNotify->pkey], array(
									'list_user_read' => $val['list_user_slash']
								));
							}
							unset($list_request_notify);
						}
					}
					unset($tmp);
				}
				$list_admins = $clsProfile->getAll("`is_trash`=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."' 
					and `role_id`='"._ROLE_STAFF_ADMIN."' and `{$clsProfile->pkey}`<>'{$profile_id}'", $clsProfile->pkey);
				if(!empty($list_admins)){
					foreach ( $list_admins as $k => $v) {
						if(!$clsISO->checkItemInArray($v[$clsProfile->pkey], $arr_user_request)) {
							$arr_user_request[] = $v[$clsProfile->pkey];
						}
					}
					unset($list_admins);
				}
				$oneBlock = $clsProperty->getOne($block_id,"more_information");
				if(!empty($oneBlock)) {
					$more_information_block = $oneBlock["more_information"];
					$more_information_block = $clsISO->to_array_json($more_information_block);
					$project_manager_id = (int) $core->get_field($more_information_block, 'project_manager', 0);
					if($project_manager_id > 0 && $project_manager_id != $profile_id) {
						$arr_user_request[] = $project_manager_id;				
					}
				}
				// $arr_user_request[] = _PROFILE_LTD_ID;
				$arr_user_request = array_unique($arr_user_request);
				if(!empty($arr_user_request)) {	
					$titleNoty = sprintf('<strong>%s</strong> đã cập nhật phiếu tính giá căn [<strong>%s</strong>]', 
					$clsProfile->getFullName($profile_id,$oneProfile), $clsStock->getMsCode($stock_id));
					#- Danh sách người gửi yêu cầu
					$field = "{$clsProfile->pkey},`full_name`,`first_name`,`last_name`,`phone`,`more_information`";
					$tmp = $clsProfile->getAll("{$clsProfile->pkey} in ".implode(",",$arr_user_request)."", $field);
					if(!empty($tmp)){
						foreach($tmp as $key => $val){
							$phone = $val['phone'];
							$more_information = $val['more_information'];
							$more_information = $clsISO->to_array_json($more_information);
							$id_zalo = $core->get_field($more_information, "zaloId", "");
							$message = sprintf("Xin chào %s. %s", $clsProfile->getFullName($val[$clsProfile->pkey],$val), strip_tags($titleNoty));
							if(!empty($id_zalo)){
								$clsZalo->sendMsgSchedule($id_zalo, $phone, $message);
							} else {
								$id_zalo = $clsZalo->getZaloId("", $phone);
								if(!empty($id_zalo)){
									$more_information['zaloId'] = $id_zalo;
									$clsProfile->updateOne($val[$clsProfile->pkey], array(
										'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
									));
									$clsZalo->sendMsgSchedule($id_zalo, $phone, $message);
								}
							}
						}
						unset($tmp);
					}
					$clsNotify->insertNotify('Stock',$clsStock->pkey, $stock_id, $titleNoty, time(), $arr_user_request);
					/** Gửi thông báo tới người gửi yêu cầu */
					$subscribers = array();
					$tmp = $clsFcmToken->getAll("`push_type`='_pushalert' 
						and `user_id` in (".implode(',', $arr_user_request).") and `token`<>''", "token");	
					if(!empty($tmp)){
						foreach($tmp as $key => $val){
							if(!in_array($val['token'], $subscribers)){
								$subscribers[] = $val['token'];
							}
						}
						$clsNotify->send_subscriber_notification(array(
							'title' => "Cập nhật phiếu tính giá",
							'message' => strip_tags($titleNoty),
							'url' => PCMS_URL . $clsStock->getLink($oStock["ms_code"])
						), $subscribers);
						unset($tmp);
					}
					#thong bao app
					$clsNotification = new Notification();
					$params = [
						'title' => "Cập nhật phiếu tính giá",
						'body' => strip_tags($titleNoty),
						'link' => PCMS_URL
					];
					$clsNotification->doPushMessagingUser($params,$arr_user_request);
				}
				#thông báo cho yêu cầu từ MOC
				if(!empty($arr_request_MOC_id)) {
					$list_request_MOC_notify = $clsNotifyMOC->getAll("`tbl`='RequestPTG' and `pkey`='id' 
					and `pval` in (".implode(',',$arr_request_MOC_id).")");
					if(!empty($list_request_MOC_notify)){
						foreach($list_request_MOC_notify as $key => $val){
							$clsNotifyMOC->updateOne($val[$clsNotifyMOC->pkey], array(
								'list_user_read' => $val['list_user_slash']
							));
						}
						unset($list_request_MOC_notify);
					}
				}
				if(!empty($arr_user_requestMOC)) {	
					$titleNoty = sprintf('<strong>Quản trị viên</strong> đã cập nhật phiếu tính giá căn [<strong>%s</strong>]', $oStock['ms_code']);
					$clsNotifyMOC->insertNotify('Stock',$clsStock->pkey, $stock_id, $titleNoty, time(), $arr_user_requestMOC);
					/** Gửi email tới người gửi yêu cầu */
					foreach ($arr_user_requestMOC as $member_id) {
						$clsNotifyMOC->sendEmailRequestPTG($member_id,$oStock['ms_code']);
					}			
				}
			}
		}
	} else if($tp=='update_status'){
		$status_id = (int) Input::post('status_id', 0);
		$agency_id = (int) Input::post('agency_id', 0);
		$price_field = Input::post('price_field');
		#
		if($oStock['status_id'] != $status_id){
			$logs[$clsISO->getUniqid()] = array(
				'is_fontpage' => 1,
				'reg_date' => $profile_id,
				'from_id' => $oStock['status_id'],
				'to_id' => $status_id,
				'field' => 'status_id'
			);
		}
		if($oStock['agency_id'] != $agency_id){
			$logs[$clsISO->getUniqid()] = array(
				'is_fontpage' => 1,
				'reg_date' => $profile_id,
				'from_id' => $oStock['agency_id'],
				'to_id' => $agency_id,
				'field' => 'agency_id'
			);
		}
		$more = array();
		$more_information['status_id'] = $status_id;
		$more_information['agency_id'] = $agency_id;
		if(!empty($price_field)){
			foreach($price_field as $key => $val){
				if(!empty($val) && (!isset($more_information[$key]) || (isset($more_information[$key]) && $more_information[$key] != $clsISO->processSmartNumber($val)))){
					$logs[$clsISO->getUniqid()] = array(
						'is_fontpage' => 1,
						'reg_date' => $profile_id,
						'from_value' => $more_information[$key],
						'to_value' => $clsISO->processSmartNumber($val),
						'field' => $key
					);
				}
				$more_information[$key] = $clsISO->processSmartNumber($val);
				if($key=='total_price_vat'){
					$more[$key] = $clsISO->processSmartNumber($val);
				}
			}
		}
		if($status_id==_STOCK_STATUS_SOLD_ID){
			$more['ms_date'] = time();
			$more_information['user_id_update_sold'] = $profile_id;
		}
		if($clsStock->updateOne($stock_id, array_merge($more, array(
			'upd_date' => time(),
			'status_id' => $status_id,
			'agency_id' => $agency_id,
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		)))){
			$msg = "_success";
			if(!empty($oneStockMeta) && !empty($logs)){
				$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
					'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
					'upd_date' => time()
				));	
			}
			#activity log			
			$clsActivityLog = new ActivityLog();
			$log = $clsActivityLog->addActivityLog("Stock","update",["tp"=>"status","ms_code"=>$oStock['ms_code']]);
		}
	} else if($tp=='poster'){
		$stock_poster = Input::post('stock_poster');
		$more_information['stock_poster'] = $stock_poster;
		// $clsISO->print_pre($stock_poster); die();
		if($clsStock->updateOne($stock_id, array(
			'upd_date' => time(),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
			#activity log			
			$clsActivityLog = new ActivityLog();
			$log = $clsActivityLog->addActivityLog("Stock","update",["tp"=>"status","ms_code"=>$oStock['ms_code']]);
		}
	} else if($tp=='video'){
		$stock_poster_video = Input::post('stock_poster_video');
		$more_information['stock_poster_video'] = $stock_poster_video;
		if($clsStock->updateOne($stock_id, array(
			'upd_date' => time(),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
			#activity log			
			$clsActivityLog = new ActivityLog();
			$log = $clsActivityLog->addActivityLog("Stock","update",["tp"=>"status","ms_code"=>$oStock['ms_code']]);
		}
	}
	// return
	echo $msg; die();
}
function shortNumber($num) {
	global $clsISO,$profile_id;
	$num = $num / 1000000000;
	return round($num, 1)."<img class='ml-1' src='".URL_IMAGES."/point.png' width='15' height='15' title='1 Future Points = 1 Tỷ'/>";
	// return round($num, 1) ." ". $units[$i];
}
function helper_load_top_ranking(){
	global $smarty,$_CONFIG,$dbconn,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$profile_id,$header_configs;
	$clsCache = new Cache();
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	##
	$tp = Input::post('tp', 'week');
	$ranking_type = Input::post('ranking_type', '_all'); // _sales
	$billing_type = Input::post('billing_type', 'primary');
	##
	$time_config = $clsConfiguration->getValue('time_config');
	$time_config = $clsISO->to_array_json($time_config);
	$is_time_now = !empty($time_config["is_time_now"]) ? $time_config["is_time_now"] : 0;
	if(empty($is_time_now)) {
		$tmp = $clsISO->get_dates_of_quarter('current', date('Y'), 'd-m-Y');
		$current_quarter = $tmp["quarter"];
		$year = !empty($time_config["year"]) ? $time_config["year"] : date("Y"); 		
		$quarter = !empty($time_config["quarter"]) ? $time_config["quarter"] : $current_quarter; 
		$month = !empty($time_config["month"]) ? $time_config["month"] : date("n");		
		if($tp=='week'){
			// Tuần cuối cùng của tháng đang cấu hình: tuần (Thứ 2 → Chủ nhật) chứa ngày cuối
			// tháng, cắt lại trong phạm vi tháng để không đếm lấn sang tháng sau.
			// Do đó khoảng này có thể ngắn hơn 7 ngày — tháng kết thúc đúng vào Thứ 2 thì
			// chỉ còn một ngày.
			$_first_day = mktime(0, 0, 0, (int) $month, 1, (int) $year);
			$_last_day  = mktime(23, 59, 59, (int) $month, (int) date('t', $_first_day), (int) $year);
			$_monday    = strtotime('monday this week 00:00:00', $_last_day);
			$start_date = max($_monday, $_first_day);
			$due_date   = $_last_day;
		} else if($tp=='month'){
			$start_date = strtotime(date(sprintf("%s-%s-01 00:00:00",$year,$month)));
			$due_date = strtotime(date(sprintf("%s-%s-31 23:59:59",$year,$month)));
		} else if($tp=='quarter'){
			$tmp = $clsISO->get_dates_of_quarter((int)$quarter, (int)$year, 'd-m-Y');
			$start_date = strtotime($tmp['start']);
			$due_date = strtotime($tmp['end']." 23:59:59");
		} else if($tp=='year'){
			$start_date = strtotime(date(sprintf("%s-01-01 00:00:00",$year)));
			$due_date = strtotime(date(sprintf("%s-12-31 23:59:59",$year)));
		}
	}else{
		if($tp=='week'){
			// Tuần chạy từ Thứ 2 đến hết Chủ nhật.
			$start_date = strtotime('monday this week 00:00:00');
			$due_date = strtotime('sunday this week 23:59:59');
		} else if($tp=='month'){
			$start_date = strtotime("first day of this month 00:00:00");
			$due_date = strtotime("last day of this month 23:59:59");
		} else if($tp=='quarter'){
			$tmp = $clsISO->get_dates_of_quarter('current', date('Y'), 'd-m-Y');
			$start_date = strtotime($tmp['start']);
			$due_date = strtotime($tmp['end']." 23:59:59");
		} else if($tp=='year'){
			$start_date = strtotime('first day of january this year');
			$due_date = strtotime('last day of december this year 23:59:59');
		}
	}
	// Chốt chặn: $tp không khớp nhánh nào sẽ để trống hai mốc, câu lệnh thành BETWEEN '' AND ''
	// và trả về bảng rỗng mà không báo lỗi gì. Rơi về tuần hiện tại cho an toàn.
	if(empty($start_date) || empty($due_date)){
		$start_date = strtotime('monday this week 00:00:00');
		$due_date = strtotime('sunday this week 23:59:59');
	}
	// BXH thi đua: doanh số = realized_sales_compete (doanh số thi đua sau giảm trừ), KHÔNG phải totalgrand.
	$field = "SUM(`t1`.`realized_sales_compete`) as `total_price`,count(`t1`.`staff_id`) as `total_billing`,`t2`.`profile_id`";
	$_cache_name = sprintf('_top_ranking_ca_%s_%s_%s_cached', $tp, $billing_type, $ranking_type);
	if($clsCache->has($_cache_name) && 1==2){
		$list_top_ranking = $clsCache->get($_cache_name);
		// $clsCache->delete($_cache_name);
	} else {
		$clsBillingSale = new BillingSale();
		$sql_query = "`t2`.`is_trash`=0 AND `t1`.`is_trash`=0 AND `t1`.`is_cancel`=0
			AND `t2`.`status_id`<>'"._STATUS_STAFF_OFF_ID."'
			AND `t2`.`profile_id` NOT IN (".implode(',',_PROFILE_NOTIN_ID).")
			AND `t2`.`department_id`<>'"._DEPARTMENT_DIRECTOR_ID."'
			AND (`t2`.`list_department_id` NOT LIKE '%|"._DEPARTMENT_DEVELOP_ID."|%')
			AND ((`t1`.`agree_date`>0 AND (`t1`.`agree_date` BETWEEN '{$start_date}' AND '{$due_date}')) OR (`t1`.`contract_date`>0 AND (`t1`.`contract_date` BETWEEN '{$start_date}' AND '{$due_date}')))";
		if($billing_type=='primary'){
			$sql_query.= " AND `t1`.`billing_type`<>'"._BILLING_TYPE_SOP_ID."'";
		} else {
			$sql_query.= " AND `t1`.`billing_type`='"._BILLING_TYPE_SOP_ID."'";
		}
		// Danh sách chức danh lãnh đạo kinh doanh, dùng để tách hai bảng "Lãnh đạo" và "Sales".
		// Thiếu mảng này thì câu lệnh thành `role_id IN ()` — MySQL báo lỗi cú pháp và bảng rỗng.
		$arr_leaders = array(_ROLE_GD_SALE, _ROLE_REGIONAL_DIRECTOR_ID);
		if($ranking_type == '_leader'){
			$sql_query.= " AND `t2`.`role_id` IN (".implode(',', $arr_leaders).")";
		} else if($ranking_type == '_sales'){
			$sql_query.= " AND `t2`.`role_id` NOT IN (".implode(',', $arr_leaders).")";
		}
		if(!empty($header_configs["department_not_rankking"])) {
			$department_not_rankking = $clsISO->to_array_json($header_configs["department_not_rankking"]);
			if(!empty($department_not_rankking)) {
				foreach($department_not_rankking as $key => $dep_id) {
					$sql_query .= " AND `t2`.`department_id`<> ".(int) $dep_id;
				}
			}
		}
		if(!empty($header_configs["role_not_rankking"])) {
			$role_not_rankking = $clsISO->to_array_json($header_configs["role_not_rankking"]);
			if(!empty($role_not_rankking)) {
				foreach($role_not_rankking as $key => $role_id) {
					$sql_query .= " AND `t2`.`role_id`<> ".(int) $role_id;
				}
			}
		}
		$field = "SUM(`tbl_bl`.`share_value`) as `total_price`,COUNT(DISTINCT `t1`.`billing_id`) as `total_billing`,`t2`.`profile_id`,`t2`.`more_information`";
		
		$list_top_ranking = $dbconn->getAll("SELECT {$field}
		FROM `{$clsBillingSale->tbl}` AS `tbl_bl`
		INNER JOIN {$clsBilling->tbl} as `t1`
			ON `t1`.`billing_id`=`tbl_bl`.`billing_id`
		INNER JOIN {$clsProfile->tbl} AS `t2`
			ON `t2`.`profile_id`=`tbl_bl`.`staff_id`
		WHERE {$sql_query} GROUP BY `t2`.`profile_id` having `total_billing`>0 ORDER BY `total_price` DESC LIMIT 0,10");
		
		if(!empty($list_top_ranking)){
			foreach($list_top_ranking as $key => $val){
				$more_information = $val['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$list_top_ranking[$key]['more_information'] = $more_information;
			}
		}else{
			$list_top_ranking = null;
		}
		$clsCache->put($_cache_name, $list_top_ranking, 15*60);			
	}
	if(!empty($list_top_ranking)){
		$total_record = count($list_top_ranking);
		$arrr_profile_cached = $clsProfile->getProfileCached();
		if($total_record == 10){
			foreach($list_top_ranking as $key => $val){
				$_staff_id = $val[$clsProfile->pkey];
				$_oneStaff = $arrr_profile_cached[$_staff_id];
				$prof_infomration = $_oneStaff['more_information'];
				$list_top_ranking[$key]['full_name'] = $clsProfile->getFullName($_staff_id, $_oneStaff);
				$list_top_ranking[$key]['avatar'] = $clsProfile->getAvatar($_staff_id, $_oneStaff, 60, 60);
				$list_top_ranking[$key]['department'] = $core->get_field($prof_infomration, "department_name", ""); 
				$list_top_ranking[$key]['prof_infomration'] = $prof_infomration;
				$list_top_ranking[$key]['style'] = '';
			}
		} else {
			$dis = 10 - $total_record;
			foreach($list_top_ranking as $key => $val){
				$_staff_id = $val[$clsProfile->pkey];
				$_oneStaff = $arrr_profile_cached[$_staff_id];
				$prof_infomration = $_oneStaff['more_information'];
				$list_top_ranking[$key]['full_name'] = $clsProfile->getFullName($_staff_id, $_oneStaff);
				$list_top_ranking[$key]['avatar'] = $clsProfile->getAvatar($_staff_id, $_oneStaff, 60, 60);
				$list_top_ranking[$key]['department'] = $core->get_field($prof_infomration, "department_name", ""); 
				$list_top_ranking[$key]['prof_infomration'] = $prof_infomration;
				$list_top_ranking[$key]['style'] = '';
			}
			for($i=0; $i<$dis; $i++){
				$list_top_ranking[$i+$total_record] = array(
					'avatar' => URL_IMAGES.'/no-avatar.jpg',
					'full_name' => 'Đang cập nhật..',
					'department' => 'FH00x',
					'total_price' => 0,
					'more_information' => array(),
					'style' => 'opacity:0.2; filter:alpha(opacity=20); -moz-opacity:0.2; -khtml-opacity:0.2;'
				);
			}
		}
	} else {
		for($i=0; $i<10; $i++){
			$list_top_ranking[$i] = array(
				'avatar' => URL_IMAGES.'/no-avatar.jpg',
				'full_name' => 'Đang cập nhật..',
				'department' => 'FH00x',
				'total_price' => 0,
				'more_information' => array(),
				'style' => 'opacity:0.2; filter:alpha(opacity=20); -moz-opacity:0.5; -khtml-opacity:0.2;'
			);
		}
	}
	$screen_sales = $clsISO->screenSales();
	$html = '<div class="rank__block-column rank-top-'.$tp.' d-flex justify-content-center">';
	$ii = 0; // Init
	foreach($list_top_ranking as $key => $val){
		if($ii < 3){
			if($ii==0) $no = 'one';
			if($ii==1) $no = 'second';
			if($ii==2) $no = 'three';
			$prof_infomration = $val['prof_infomration'];
			$html.= '<div class="rank__block-item rank__top-'.$no.'">
				<div class="rank__block-number mt-'.$no.'">'.($ii+1).'</div>
				<div class="rank__block-profile" style="'.$val['style'].'">
					<div class="rank__profile-avatar avatar-md mx-auto position-relative mb-2"'.((int) $val[$clsProfile->pkey] > 0 ? ' data-url="/index.php?mod=home&act=load_profile_popover&user_id='.(int) $val[$clsProfile->pkey].'" data-toggle="webui-popover" data-trigger="click" data-width="350" style="cursor:pointer"' : '').'>
						<img class="avatar avatar-md rounded-circle" src="'.$val['avatar'].'" onerror="this.src=\''.URL_IMAGES.'/no-avatar.jpg\'" />
						'.$clsProfile->get_icon_verified($val[$clsProfile->pkey], $prof_infomration).'
					</div>
					<h3 class="rank__profile-name fs-13 text-white font-bold mb-1">'.$val['full_name'].'</h3>
					'.($screen_sales ? '<div clas="d-flex align-items-center justify-content-center">
						<span class="mb-0 mx-auto rank__profile-profit text-muted fs-12">
							'.shortNumber($val['total_price']).'
						</span>
					</div>' : '').'
				</div>
			</div>';
		}
		++$ii;
	}
	// Reset
	$ii = 0; // Init
	$html.= '</div>
	<div class="rank__block-row position-relative overflow-hidden rank__block-'.$tp.'">';	
	foreach($list_top_ranking as $key => $val){
		if($ii > 2){
			$more_information = $val['more_information'];
			$is_star_club = (int) $core->get_field($more_information, 'is_star_club', 0);
			$html.= '<div class="d-flex align-items-center rank__row-item"">
				<span class="rank__item-number rounded-circle text-center mr-2">'.($ii+1).'</span>
				<div class="rank__item-avatar position-relative mr-2" style="'.$val['style'].((int) $val[$clsProfile->pkey] > 0 ? ';cursor:pointer' : '').'"'.((int) $val[$clsProfile->pkey] > 0 ? ' data-url="/index.php?mod=home&act=load_profile_popover&user_id='.(int) $val[$clsProfile->pkey].'" data-toggle="webui-popover" data-trigger="click" data-width="350"' : '').'>
					<img class="avatar avatar-xs rounded-circle" src="'.$val['avatar'].'" onerror="this.src=\''.URL_IMAGES.'/no-avatar.jpg\'" />
					'.$clsProfile->get_icon_verified($val[$clsProfile->pkey], $more_information, "star").'
				</div>
				<div class="rank__item-content" style="'.$val['style'].'">
					<h3 class="fs-13 rank__item-name font-bold mb-0">'.$val['full_name'].' 
						'.$clsProfile->get_icon_verified($val[$clsProfile->pkey], $more_information, "verify").'
					</h3>
					<div clas="d-flex align-items-center">
						<span class="text-muted mr-2 fs-13">'.$val['department'].'</span>
						'.($screen_sales ? '<span class="text-danger fs-13">'.shortNumber($val['total_price']).'</span>' : '').'
					</div>
				</div>
			</div>';
		}
		++$ii;
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function helper_load_top_ranking25(){
	global $smarty,$_CONFIG,$dbconn,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$profile_id
	,$header_configs,$deviceType;
	$clsCache = new Cache();
	$clsBilling = new Billing();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	##
	$tp = Input::post('tp', 'month');
	if($tp=='month'){
		$start_date = strtotime("first day of this month 00:00:00");
		$due_date = strtotime("last day of this month 23:59:59");
		$my = date("n/Y");
		$title_content = "Top 25 cá nhân đặc biệt xuất sắc tháng {$my}" ;
		$title_content_time = "Tính từ ngày ".date("01/m/Y") . " đến ngày ".date("d/m/Y");		
		$_cache_name = '_top_ranking_25_month_ca_cached';
		$ratio = 80;
	} else if($tp=='quarter'){
		$tmp = $clsISO->get_dates_of_quarter('current', date('Y'), 'd-m-Y');
		$start_date = strtotime($tmp['start']);
		$due_date = strtotime($tmp['end']." 23:59:59");
		$_cache_name = '_top_ranking_25_quarter_ca_cached';
		$title_content = "Top 25 cá nhân đặc biệt xuất sắc quý {$tmp['quarter']}/{$tmp['year']}" ;
		$title_content_time = "Tính từ ngày ".date("d/m/Y",$start_date) . " đến ngày ".date("d/m/Y");
		$ratio = 60;
	} else if($tp=='year'){
		$start_date = strtotime('first day of january this year');
		$due_date = strtotime('last day of december this year 23:59:59');		
		$title_content = "Top 25 cá nhân đặc biệt xuất sắc năm ".date("Y") ;
		$title_content_time = "Tính từ ngày ".date("1/1/Y") . " đến ngày ".date("d/m/Y");
		$_cache_name = '_top_ranking_25_year_ca_cached';
		$ratio = 60;
	}
	// Chốt chặn: $tp lạ sẽ để trống mốc thời gian lẫn tên khoá cache, câu lệnh thành
	// BETWEEN '' AND '' và trả bảng rỗng mà không báo lỗi. Rơi về tháng hiện tại.
	if(empty($start_date) || empty($due_date) || empty($_cache_name)){
		$start_date = strtotime("first day of this month 00:00:00");
		$due_date = strtotime("last day of this month 23:59:59");
		$_cache_name = '_top_ranking_25_month_ca_cached';
		$title_content = "Top 25 cá nhân đặc biệt xuất sắc tháng ".date("n/Y");
		$title_content_time = "Tính từ ngày ".date("01/m/Y")." đến ngày ".date("d/m/Y");
		$ratio = 80;
	}
	if($deviceType == 'computer') {
		$ratio = 80;
	}
	// Cùng cách tính với BXH cá nhân (load_top_ranking): chấm theo hoa hồng thực nhận
	// trong `billing_sale` nên người đồng bán được ghi nhận phần của mình, kỳ tính theo
	// ngày ký, và loại nhân sự đã nghỉ việc thay vì chỉ giữ người "Chính thức".
	$clsBillingSale = new BillingSale();
	$field = "SUM(`tbl_bl`.`share_value`) as `total_price`,COUNT(DISTINCT `t1`.`billing_id`) as `total_billing`,`t2`.`profile_id`";
	if($clsCache->has($_cache_name) && 1==2){
		$lstItem = $clsCache->get($_cache_name);
		// $clsCache->delete($_cache_name);
	} else {
		$sql_query = "`t2`.`is_trash`=0 AND `t1`.`is_trash`=0 AND `t1`.`is_cancel`=0
		AND `t1`.`billing_type`<>'"._BILLING_TYPE_SOP_ID."'
		AND `t2`.`status_id`<>'"._STATUS_STAFF_OFF_ID."' AND `t2`.`profile_id` NOT IN (".implode(',',_PROFILE_NOTIN_ID).")
		AND ((`t1`.`agree_date`>0 AND (`t1`.`agree_date` BETWEEN '{$start_date}' AND '{$due_date}')) OR (`t1`.`contract_date`>0 AND (`t1`.`contract_date` BETWEEN '{$start_date}' AND '{$due_date}')))
		AND (`t2`.`list_department_id` NOT LIKE '%|"._DEPARTMENT_DEVELOP_ID."|%')";
		// Phòng/chức danh bị loại khỏi thi đua lấy từ cấu hình, giống BXH cá nhân.
		// Thiếu điều kiện theo chức danh thì người của ban PTĐT vẫn lọt vào bảng khi
		// hồ sơ của họ không gắn phòng 12133 trong `list_department_id`.
		if(!empty($header_configs["department_not_rankking"])) {
			$department_not_rankking = $clsISO->to_array_json($header_configs["department_not_rankking"]);
			if(!empty($department_not_rankking)) {
				foreach($department_not_rankking as $key => $dep_id) {
					$sql_query .= " AND `t2`.`department_id`<> ".(int) $dep_id;
				}
			}
		}
		if(!empty($header_configs["role_not_rankking"])) {
			$role_not_rankking = $clsISO->to_array_json($header_configs["role_not_rankking"]);
			if(!empty($role_not_rankking)) {
				foreach($role_not_rankking as $key => $role_id) {
					$sql_query .= " AND `t2`.`role_id`<> ".(int) $role_id;
				}
			}
		}
		$lstItem = $dbconn->getAll("SELECT {$field}
			FROM `{$clsBillingSale->tbl}` AS `tbl_bl`
			INNER JOIN {$clsBilling->tbl} as `t1` ON `t1`.`billing_id`=`tbl_bl`.`billing_id`
			INNER JOIN {$clsProfile->tbl} AS `t2` ON `t2`.`profile_id`=`tbl_bl`.`staff_id` AND `t2`.`department_id`<>'"._DEPARTMENT_DIRECTOR_ID."'
			WHERE {$sql_query} GROUP BY `t2`.`profile_id` HAVING `total_billing`>0 ORDER BY `total_price` DESC LIMIT 0,25");
		// Lưu mảng rỗng chứ không lưu chuỗi rỗng: lần đọc sau `count()` trên chuỗi sẽ lỗi
		// và `$lstItem[] = ...` trên chuỗi là fatal "[] operator not supported for strings".
		$clsCache->put($_cache_name, (!empty($lstItem) ? $lstItem : array()), 15*60);
	}
	if(!is_array($lstItem)) $lstItem = array();
	$max_height = 0; $col_height_min = 10;
	$total_item = count($lstItem);
	$arr_department_cached = $clsProperty->getArraySearchByKey("_DEPARTMENT");
	if(!empty($lstItem)){
		$arr_profile_cached = $clsProfile->getProfileCached();
		foreach($lstItem as $key => $val){
			$profile_id = $val['profile_id'];
			$oneStaff = $arr_profile_cached[$profile_id];
			$more_information = $oneStaff['more_information'];
			$department_name = $core->get_field($more_information, "department_name", "");
			$oneStaff['department_name'] = !empty($department_name) ? $department_name : $arr_department_cached[$oneStaff["department_id"]]["title"];
			$lstItem[$key]['oneStaff'] = $oneStaff;
			$lstItem[$key]['total_price'] = $clsISO->shortNumberV2($val['total_price']);
			if($key == 0) {
				$max_height = $val['total_price'];
				$lstItem[$key]['col_height'] = $ratio;
				$lstItem[$key]['bg_avt'] = URL_IMAGES."/rank/top-1.png";
				$lstItem[$key]['bgcolor'] = "#fff9e4e8";
			}else{
				if($key == 1) {
					$lstItem[$key]['bg_avt'] = URL_IMAGES."/rank/top-2.png";
					$lstItem[$key]['bgcolor'] = "#fff9e4e8";
				}else if($key == 2) {
					$lstItem[$key]['bg_avt'] = URL_IMAGES."/rank/top-3.png";
					$lstItem[$key]['bgcolor'] = "#fff9e4e8";
				} else{
					$lstItem[$key]['bg_avt'] = URL_IMAGES."/rank/top-4.png";
					$lstItem[$key]['bgcolor'] = "#FFF";
				}
				// $max_height là doanh số của người đứng đầu; bằng 0 thì mọi cột đều bằng 0,
				// chia thẳng sẽ lỗi division by zero.
				$col_height = ($max_height > 0) ? ($val['total_price'] * $ratio) / $max_height : 0;
				$lstItem[$key]['col_height'] = $col_height;
				if($key == $total_item -1 && $col_height_min >= $col_height){
					$col_height_min = $col_height-5;
				}
			}
		}
	}
	if($total_item  < 25) {
		for($i=0; $i< 25-$total_item; $i++) {
			$lstItem[] = [
				"bg_avt"	=>	URL_IMAGES."/rank/top-4.png",
				"bgcolor"	=>	"#FFF",
				"col_height" =>	$col_height_min,
				"is_none"	=>	1,
			];
		}
	}
	$reg_date_arrs = @array_column($lstItem, 'col_height');
	@array_multisort($reg_date_arrs, SORT_ASC, $lstItem);
	$smarty->assign("max_height",$max_height);
	$smarty->assign("lstItem",$lstItem);	
	$smarty->assign("title_content",$title_content);
	$smarty->assign("title_content_time",$title_content_time);
	// Màn hình mặc định không xem doanh số: cột biểu đồ vẫn so sánh được, chỉ bỏ con số.
	$smarty->assign("screen_sales", $clsISO->screenSales());
	$html_text = '<h3 class="text-upper fs-3 mb-2">'.$title_content.'</h3>
		<p class=" fs-16 text-white">('.$title_content_time.')</p>';
	$html_chart = $core->build('helper'.DS.'_ajax.top_rank_25.tpl');
	// Return
	echo json_encode(array(
		'html_text' => $html_text,
		'html_chart' => $html_chart
	)); die();
}
function helper_open_policy(){
	global $smarty,$_CONFIG,$dbconn,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$uid = $clsISO->getUniqid();
	$tp = Input::post('tp', '_view');
	$smarty->assign('tp', $tp);
	$smarty->assign('uid', $uid);
	###
	$sale_policy = $clsConfiguration->getValue('sale_policy');
	$sale_policy = !empty($sale_policy) ? json_decode(html_entity_decode($sale_policy), true) : array();
	$smarty->assign('sale_policy', $sale_policy);
	// Return
	$html = $core->build('_ajax.policy.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function helper_update_policy(){
	global $smarty,$_CONFIG,$dbconn,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	###
	$msg = "_success";
	$banner = Input::post('banner');
	$status = (int) Input::post('status', 0);
	$policy = Input::post('policy');
	if(Input::post('submit')=='Update'){
		if($clsConfiguration->updateValue('sale_policy', json_encode(array(
			'banner' => $banner,
			'policy' => $policy,
			'status' => $status,
			'upd_date' => time()
		), JSON_UNESCAPED_UNICODE))){
			$msg = "_success";
			#activity log			
			$clsActivityLog = new ActivityLog();
			$log = $clsActivityLog->addActivityLog("Configuration","update",["field"=>"sale_policy"]);
		}
	}
	// Return
	echo $msg; die();
}
function helper_register_mwf(){
	global $smarty,$_CONFIG,$dbconn,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	// Return
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	###
	$clsProperty = new Property();
	$smarty->assign('clsProperty', $clsProperty);
	$arr_register_type_mwf = $clsProperty->getCacheItems('_REGISTER_TYPE_MWF');
	$smarty->assign('arr_register_type_mwf', $arr_register_type_mwf);
	// Return
	$html = $core->build('_ajax.register_mwf.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function helper_save_register_mwf(){
	global $smarty,$_CONFIG,$dbconn,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$profile_id;
	##
	$clsNotify = new Notify();
	$clsProfile = new Profile();
	##
	$uid = Input::post('uid', "");
	$time = Input::post("time","");
	#- Require library
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	#- End require
	$cachedName = sprintf('%s.json', 'register_mwf');
	$cachedFile = DIR_CACHE_JSON.'/'.$cachedName;
	// $clsISO->print_pre($cachedFile); die();
	$data_regiser_mwfs = array();
	if(@file_exists($cachedFile)){
		$decoder = new Webmozart\Json\JsonDecoder();
		$data_regiser_mwfs = $decoder->decodeFile($cachedFile);
	}
	if(!empty($uid)){
	} else {
		$uid = $clsISO->getUniqid();
		$data_regiser_mwfs[$uid] = array(
			'is_confirmed' => 0,
			'reg_date' => time(),
			'profile_id' => $profile_id,
			'type_id' => Input::post('type_id'),
			'full_name' => Input::post('full_name'),
			'stock_code' => Input::post('stock_code'),
			'phone' => Input::post('phone'),
			'CCID' => Input::post('CCID'),
			'time' => $time
		);
		$content = sprintf("<strong>%s</strong> đã đăng ký tham quan nhà mẫu vào lúc <strong>%s</strong>", 
			$clsProfile->getFullName($profile_id, $oneProfile), $time);
		$clsNotify->insertNotify("register_mwf", "id", 0, $content, time(), '|'.implode('|',_PROFILE_RECEIVE_NOTIFY_ID).'|');
		#activity log			
		$clsActivityLog = new ActivityLog();
		$log = $clsActivityLog->addActivityLog("RegisterMWF","insert");
	}
	// $clsISO->print_pre($data_regiser_mwfs); die();
	$encoder = new Webmozart\Json\JsonEncoder();
	$encoder->encodeFile($data_regiser_mwfs, $cachedFile);
	// Return
	echo(1); die();
}
function helper_get_property(){
	global $smarty,$_CONFIG,$dbconn,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$results = array();
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$property_type = Input::get('property_type');
	if($property_type=='_AGENCY_SOLD'){
		$field = "distinct(`t1`.{$clsProperty->pkey}),`t1`.`title`";
		$tmp = $dbconn->getAll("select {$field} from ".$clsProperty->tbl." as `t1` 
			inner join ".$clsStock->tbl." as `t2` on `t1`.`property_id`=`t2`.`agency_id` 
			where `t1`.`is_trash`=0 and `t1`.`property_type`='_AGENCY' order by `t1`.`order_no` ASC");
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$results[] = array(
					'id' => $val[$clsProperty->pkey],
					'text' => $val['title']
				);
			}
			unset($tmp);
		}
	} else {
		$field = "{$clsProperty->pkey},title";
		$tmp = $clsProperty->getAll("`is_trash`=0 and `is_locked`=0 and `property_type`='{$property_type}' order by `order_no` ASC", $field);
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$results[] = array(
					'id' => $val[$clsProperty->pkey],
					'text' => $val['title']
				);
			}
			unset($tmp);
		}
	}
	// Return
	echo json_encode($results); die();
}
function helper_open_stock_chart(){
	global $smarty,$_CONFIG,$dbconn,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	$clsProperty = new Property();
	##
	$today = time();
	$interval = 500000000;
	$uid = $clsISO->getUniqid();
	$stock_id = (int) Input::post('stock_id', 0);
	$oneStock = $clsStock->getOne($stock_id. "`ms_code`,`reg_date`,`more_information`");
	$more_information = $oneStock['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$total_price_vat = $core->get_money_field($more_information, "total_price_vat", 0);
	$total_price_vat = $clsISO->convertPriceShortToFull($total_price_vat);
	$smarty->assign('oneStock', $oneStock);
	##
	$list_logs = array();
	$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='{$stock_id}'", "logs");
	if(!empty($oneStockMeta)){
		$list_logs = $oneStockMeta['logs'];
		$list_logs = $clsISO->to_array_json($list_logs);
	}
	// $clsISO->print_pre($list_logs); die();
	$data = $barChartData = $dataPoints = array();
	$barChartData['animationEnabled'] = true;
	###
	$total_price_max = $total_price_min = $total_price_vat;
	if(!empty($list_logs)){
		$list_dates = array();
		foreach($list_logs as $key => $val){
			$reg_date = $val['reg_date'];
			if((int) $reg_date > 0 && $val['field'] == 'total_price_vat'){
				if(!in_array(date('dmy', $reg_date), $list_dates)){
					$to_value = isset($val['to_value']) ? $val['to_value'] : $val['to_id'];
					$to_value = (int) $clsISO->processSmartNumber($to_value);
					$to_value = $clsISO->convertPriceShortToFull($to_value);
					if((float) $to_value > $interval){
						if($total_price_max < $to_value) $total_price_max = $to_value;
						if($total_price_min > $to_value) $total_price_min = $to_value;
						/// $total_price_vat = $val['to_value'];
						$list_dates[] = date('dmy', $reg_date);
						$dataPoints[] = array(
							'reg_date' => $reg_date,
							'label' => date('d/m/Y', $val['reg_date']),
							'y' => $clsISO->convertToNumber($to_value)
						);
					}
				}
			}
		}
	}
	$dataPoints[] = array(
		'reg_date' => $today,
		'label' => date('d/m/Y', $today),
		'y' => $total_price_vat
	);
	if(!empty($dataPoints)){
		$reg_date_arrs = @array_column($dataPoints, 'reg_date');
		@array_multisort($reg_date_arrs, SORT_ASC, $dataPoints);
	}
	$data['type'] = 'spline';
	// $data['showInLegend'] = 'false';
	// $data['legendText'] = '{label}';
	$data['indexLabel'] = '{y}';
	$data['indexLabelPlacement'] = 'inside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$data['axisY'] = [
		"title" => "Giá Full Vat(tỷ)",
		"labelFormatter" =>	1,
		"interval"	=> $interval,
		"minimum" => $total_price_min - $interval,
		"maximum" => $total_price_max + $interval
	];
	$barChartData['data'] = $data;
	// Return
	$smarty->assign('uid', $uid);
	$html = $core->build('_ajax.stock_chart.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'barChartData' => $barChartData
	)); die();
}
function helper_check_stock_code(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin
	,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,$clsConfiguration,$IsoEditor;
	$clsSop = new Sop();
	$clsStock = new Stock();
	###
	$stock_id = 0; $msg = "_invalid";
	$stock_code = Input::post('stock_code');
	if(!empty($stock_code)){
		$oStock = $clsStock->getByCond("(`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' 
			OR `stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."') and `ms_code`='{$stock_code}'", $clsStock->pkey);
		if(!empty($oStock)){
			$msg = "_valid";
			$stock_id  = $oStock[$clsStock->pkey];
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'stock_id' => $stock_id
	)); die();
}
function helper_open_stock_today(){
	global $smarty,$assign_list,$_CONFIG,$dbconn,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$profile_id;
	$clsToday = new Today();
	$clsProfile = new Profile();
	$smarty->assign('clsToday', $clsToday);	
	$smarty->assign('clsProfile', $clsProfile);	
	// Return
	$now = time();
	$uid = $clsISO->getUniqid();
	$action = Input::post("action","_open");
	$oneToday = $clsToday->getByCond("{$now} BETWEEN `start_date` AND `end_date`");
	$action = "_add";
	if(!empty($oneToday)){
		$action = "_edit";
		$start_date = $oneToday['start_date'];
		$end_date = $oneToday['end_date'];
		$start_date = date("Y-m-d\Th:i",$start_date);
		$end_date = date("Y-m-d\Th:i",$end_date);
		$more_information = $oneToday['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$total_images = isset($more_information["total_images"]) 
			? (int) $more_information["total_images"] : 0;
		$oneToday['more_information'] = $more_information;
		$oneToday['total_images'] = $total_images;
		$oneToday['start_date'] = $start_date;
		$oneToday['end_date'] = $end_date;
	} else {
		$more_information = array();
		$start_date = strtotime(date('d-m-Y'));
		$end_date = strtotime(sprintf('%s 23:59:59', date('d-m-Y')));
		$start_date = date("Y-m-d\Th:i", $start_date);
		$end_date = date("Y-m-d\Th:i",$end_date);
		$oneToday['is_online'] = 1;
		$oneToday['is_repeat'] = 0;
		$oneToday['start_date'] = $start_date;
		$oneToday['end_date'] = $end_date;
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('action', $action);
	$smarty->assign('oneToday', $oneToday);
	$smarty->assign('more_information', $more_information);
	// Return
	$html = $core->build('_ajax.stock_today.tpl');
	echo json_encode(array(
		"uid"		=>	$uid,
		"html"		=>	$html
	)); die();
}
function helper_edit_stock_today(){
	global $smarty,$assign_list,$_CONFIG,$dbconn,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$profile_id,$deviceType;
	$clsToday = new Today();
	$clsProfile = new Profile();
	$clsStock = new Stock();
	$clsProperty = new Property();
	$smarty->assign('clsToday', $clsToday);	
	$smarty->assign('clsProfile', $clsProfile);	
	// Return
	$now = time();
	$uid = $clsISO->getUniqid();
	$action = Input::post("action","_edit");
	$today_id = (int)Input::post("today_id",0);
	$oneToday = $clsToday->getOne($today_id);
	if($action == "_dupplicate") {
		$maxId = $clsToday->getMaxID();
		$oneToday['today_id'] = $maxId;
		$clsToday->insert($oneToday);
		$oneToday = $clsToday->getOne($maxId);
		$action = "_edit";
	}
	if(!empty($oneToday)){
		$start_date = $oneToday['start_date'];
		$end_date = $oneToday['end_date'];
		$start_date = date("Y-m-d\Th:i",$start_date);
		$end_date = date("Y-m-d\Th:i",$end_date);
		$more_information = $oneToday['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$total_images = isset($more_information["total_images"]) 
			? (int) $more_information["total_images"] : 0;
		$oneToday['more_information'] = $more_information;
		$oneToday['total_images'] = $total_images;
		$oneToday['start_date'] = $start_date;
		$oneToday['end_date'] = $end_date;
		if($action == "_detail") {
			$stock_code = $oneToday['stock_code'];
			$oneStock = $clsStock->getByCond("ms_code='{$stock_code}'", "{$clsStock->pkey},home_direction_id,more_information");
			$stock_information = $oneStock['more_information']; // Stock
			$stock_information = $clsISO->to_array_json($stock_information);
			$DT_TT = $clsISO->convertToNumber($stock_information['DT_TT']);
			$total_price_vat = $clsISO->processSmartNumber($stock_information['total_price_vat']);
			$total_price_early = $clsISO->processSmartNumber($stock_information['total_price_early']);
			###
			$oneToday['stock_id'] = $oneStock[$clsStock->pkey];
			$oneToday['DT_TT'] = $stock_information['DT_TT'];
			// $oneToday['bedroom_name'] = $clsProperty->getTitle($oneStock['bedroom_id']);
			if($deviceType == "phone"){
				$oneToday['home_direction_name'] = $clsProperty->getTitle($oneStock['home_direction_id']);	
			}else{
				$oneToday['home_direction_name'] = $clsProperty->getTitleQR($oneStock['home_direction_id']);
			}			
			$oneToday['price_m2'] = $clsISO->priceFormatV2($total_price_early/$DT_TT,1);
			###
			$total_images = 0;
			$list_images = array();
			$images = $more_information['images'];
			if(!empty($images)){
				foreach($images as $key => $img){
					if(!empty($img)){
						$total_images += 1;
						$list_images[] = FH_URL.$img;
					}
				}
			}
			$oneToday['list_images'] = $list_images;
		}		
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('action', $action);
	$smarty->assign('oneToday', $oneToday);
	$smarty->assign('more_information', $more_information);
	// Return
	$html = $core->build('_ajax.stock_today.tpl');
	echo json_encode(array(
		"uid"		=>	$uid,
		"html"		=>	$html
	)); die();
}
function helper_delete_today(){
	global $smarty,$assign_list,$_CONFIG,$dbconn,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$profile_id;
	$clsToday = new Today();
	$clsProfile = new Profile();
	$smarty->assign('clsToday', $clsToday);	
	$smarty->assign('clsProfile', $clsProfile);	
	// Return
	$now = time();
	$uid = $clsISO->getUniqid();
	$today_id = (int)Input::post("today_id",0);
	$oneToday = $clsToday->getOne($today_id);
	$data = ["result"	=>	false];
	if(!empty($oneToday)){
		if($clsToday->deleteOne($today_id)){
			$data = ["result"	=>	true];
		}
	}
	echo json_encode($data); die();
}
function helper_save_stock_today(){
	global $smarty,$assign_list,$_CONFIG,$dbconn,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$profile_id;
	$clsToday = new Today();
	$clsProfile = new Profile();
	$smarty->assign('clsToday', $clsToday);	
	$smarty->assign('clsProfile', $clsProfile);	
	// Return
	$uid = $clsISO->getUniqid();
	$today_id = (int) Input::post("today_id", 0);
	$images = Input::post("images", array());
	$total_images = Input::post("total_images","");
	$start_date = Input::post('start_date');
	$end_date = Input::post('end_date');
	$start_date = !empty($start_date) ? $clsISO->toTime($start_date) : 0;
	$end_date = !empty($end_date) ? $clsISO->toTime($end_date) : 0;
	#
	$msg = "_error";
	$more_information = array(
		'images'		=>	$images,
		'total_images'	=>	$total_images
	);
	if($today_id == 0){
		$today_id = $clsToday->getMaxID();
		if($clsToday->insert(array(
			$clsToday->pkey => $today_id,
			'title' => Input::post('title'),
			'stock_code' => Input::post('stock_code'),
			'content' => Input::post('content'),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'start_date' => $start_date,
			'end_date' => $end_date,
			'is_repeat' => Input::post('is_repeat', 0),
			'is_online' => Input::post('is_online', 0),
			'reg_date' => time(),
			'upd_date' => time(),
			'user_id' => $profile_id,
			'user_id_update' => $profile_id
		))){
			$msg = "_success";
		}
	} else {
		if($clsToday->updateOne($today_id, array(
			'title' => Input::post('title'),
			'stock_code' => Input::post('stock_code'),
			'content' => Input::post('content'),
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'start_date' => $start_date,
			'end_date' => $end_date,
			'is_repeat' => Input::post('is_repeat', 0),
			'is_online' => Input::post('is_online', 0),
			'upd_date' => time(),
			'user_id_update' => $profile_id
		))){
			$msg = "_success";
		}
	}
	// Return
	echo $msg; die();
}
function helper_upload_image(){
    global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id;
    $clsSop = new Sop();
	$clsProperty = new Property();
    #
	$total_images = $total_upload = 0;
	$html = '';  $msg = "_error"; 
    if(!empty($_FILES['images'])){
        $images = $_FILES['images'];
		$total_images = (int) Input::post('total_images', 0);
        if(!empty($images['name']) && array_sum($images['error'])==0){
			$results = array();
            for($i=0; $i<count($images['name']); $i++){
                $img = array();
                $img['name'] = $images['name'][$i];
                $img['type'] = $images['type'][$i];
                $img['tmp_name'] = $images['tmp_name'][$i];
                $img['error'] = $images['error'][$i];
                $img['size'] = $images['size'][$i];
				$total_images+= 1;
                if(is_uploaded_file($img['tmp_name']) && $total_images <= 20){
					$total_upload+= 1;
                    $clsUploadFile = new UploadFile();
                    $up = $clsUploadFile->uploadItem($img,"/dropzone",EXTENSION_FILE_UPLOAD);
					if(!empty($up) && @file_exists(ROOTPATH.$up)){
						$msg = "_success";
						$results[] = $up;
					}
                }
            }
			// Return
			if(!empty($results)){
				foreach($results as $image){
					$html .= '<div class="item col-6 col-sm-3 mb-2 position-relative">
						<img class="w-100" src="'.$image.'" style="height:90px;object-fit:cover"/>
						<input type="hidden" name="images[]" value="'.$image.'" />
						<a class="delete text-white position-absolute top-0 cursor-pointer" src="'.$image.'" onClick="$Core.today.delete_image(this, event)" data-type="item" style="right:10px">x</a>
					</div>';
				}
			}
        }
    }
    // Return
	echo json_encode(array(
		'msg' => $msg,
		'html' => $html,
		'total_upload' => $total_upload,
		'total_images' => $total_images
	)); die();
}
function helper_delete_image(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser
	,$_frontIsLoggedin,$_frontIsLoggedin_user_id,$_loggedin,$extLang,$_LoggedUser,$clsConfiguration,$IsoEditor;
	#
	$clsToday = new Today();
	$type = Input::post("type","item");
	$today_id = (int)Input::post("today_id",0);
	$html = "";
	$data = array('result' => false, 'html' => $html);
	if($type == "item"){
		$src = Input::post('src');
		if(!empty($src) && file_exists(ROOTPATH . $src)){
			@unlink(ROOTPATH . $src);
			if($today_id > 0){
				$oneItem = $clsToday->getOne($today_id);
				$more_information = $oneItem['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$images = !empty($more_information['images']) ? $more_information['images']: array();
				$key = array_search($src, $images);
				unset($images[$key]);
				$more_information['images'] = array_values($images);
				$total_images = $more_information['total_images'] - 1;
				if($total_images < 1){
					for($i=0; $i<4; $i++){
						$html .= '<div class="col-6 col-sm-3 mb-2 position-relative">
							<div class="rounded">
								<img class="w-100" src="'.URL_IMAGES.'/no-image.jpg" style="height:90px;object-fit:cover"/>
							</div>
						</div>';
					}
				}
				$clsToday->updateOne($today_id, array(
					'user_id_update' => $profile_id,
					'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
				));
				$data = [
					"result"	=>	true,
					"html"		=>	$html
				];
			}
		}
	}else if($type == "all"){
		if($today_id > 0){
			$oneItem = $clsToday->getOne($today_id);
			$more_information = $oneItem['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$images = !empty($more_information['images']) ? $more_information['images']: array();
			if(!empty($images)){
				foreach($images as $img){	
					if(!empty($img) && file_exists(ROOTPATH . $img)){
						@unlink(ROOTPATH . $img);
					}				
				}
			}
			$key = array_search($src, $images);
			unset($images[$key]);
			$more_information = ['total_images'	=>	0, 'images' =>	[]];
			for($i=0; $i<4; $i++){
				$html .= '<div class="col-6 col-sm-3 mb-2 position-relative">
					<div class="rounded">
						<img class="w-100" src="'.URL_IMAGES.'/no-image.jpg" style="height:90px; object-fit:cover"/>
					</div>
				</div>';
			}
			$clsToday->updateOne($today_id, array(
				'user_id_update' => $profile_id,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			));
			$data = [
				"result"	=>	true,
				"html"		=>	$html
			];
		}
	}
	// Return
	echo $html; die();
}
function helper_load_today(){
	// ini_set('display_errors', '1');
	// ini_set('display_startup_errors', '1');
	// error_reporting(E_ALL);
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	$clsToday = new Today();
	$clsProfile = new Profile();
	##
	$html = "";
	$keySearch = Input::post('keySearch', "");
	$start_date = Input::post('start_date', "");
	$end_date = Input::post('end_date', "");
	$start_date = !empty($start_date) ? $clsISO->convertTextToTime($start_date):0;
	$end_date =  !empty($end_date) ? $clsISO->convertTextToTime($end_date, "23:59:59") :0;
	##
	$cond = "1=1";
	if(!empty($keySearch)) {
		$cond .= " and (title like '%{$keySearch}%' 
			or stock_code like '%".$keySearch."%'
		)";
	}
	if($start_date > 0 && $end_date > 0){
		$cond .= " 	AND {$start_date} <= `end_date` AND `start_date` <= {$end_date}";
	}else if($start_date > 0 && $end_date == 0){
		$cond .= " AND {$start_date} BETWEEN `start_date` AND `end_date`";
	}else if($start_date == 0 && $end_date > 0){
		$cond .= " AND {$end_date} BETWEEN `start_date AND end_date`";
	}
	$permiss_edit = $clsISO->checkPermission("edit_course");
	$smarty->assign('permiss_edit', $permiss_edit);
	#- Begin Pagination
	$current_page = Input::post('page',1);
	$per_page  = Input::post('per_page',10);
	$total_record = $clsToday->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#- End Pagination
//	$clsToday->setDeBug(1);
	$list_today = $clsToday->getAll($cond." order by start_date DESC".$limitCond);
//	var_dump($list_today);die;
	$time = time();
	if(!empty($list_today)){		
		$i=0; $arr_cache = [];
		foreach($list_today as $key => $val){	
			if($val['is_online'] == 1) {
				if($val['start_date'] <= $time && $val['end_date'] >= $time){
					$list_today[$key]['status'] = "<span class='text-success'>Đang hiển thị</span>";
				}else if($val['start_date'] > $time) {
					$list_today[$key]['status'] = "<span class='text-muted'>Chưa hiển thị</span>";
				}else{
					$list_today[$key]['status'] = "<span class='text-danger'>Đã tắt</span>";
				}
			}else{
				$list_today[$key]['status'] = "<span class='text-danger'>Đã tắt</span>";
			}
			$list_today[$key]['start_date'] = date("d/m/Y H:i",$val['start_date']);
			$list_today[$key]['end_date'] = date("d/m/Y H:i",$val['end_date']);
			$list_today[$key]['reg_date'] = date("d/m/Y H:i",$val['reg_date']);
			if(!isset($arr_cache[$val['user_id']])){
				$arr_cache[$val['user_id']] = $clsProfile->getFullName($val['user_id']);
			}
			$list_today[$key]['user_name'] = $arr_cache[$val['user_id']];
		}
	}
//	var_dump($list_today);die;
	$assign_list['list_today'] = $list_today;
	// Return
	$html = $core->build("_ajax.listToday.tpl");
	echo json_encode(array(
		'html' => $html,
		'total_page' => $total_page,
		'total_record' => $total_record,
		'current_page' => $current_page,
		'per_page' => $per_page
	));
}
function helper_status_today(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$oneProfile,$assign_list;
	###
	$clsToday = new Today();
	$clsNotify = new Notify();
	$clsProperty = new Property();
	$today_id = (int) Input::post('today_id', 0);
	$status = (int) Input::post('status', 0);
	$field = Input::post('field', "");
	###
	$data = [
		"result"	=>	false
	];
	if($today_id > 0 && $field != ""){
		if($field == "is_online") {
			$arr_data = [
				'is_online'	=>	$status
			];
			if($clsToday->updateOne($today_id, $arr_data)){
				$data = [
					"result"	=>	true,
				];
			}
		}else if($field == "is_repeat") {
			$arr_data = [
				'is_repeat'	=>	$status
			];
			if($clsToday->updateOne($today_id, $arr_data)){
				$data = [
					"result"	=>	true,
				];
			}
		}
	}
	// Return
	echo json_encode($data); die();	
}
function helper_open_Lpoint(){
	global $smarty,$clsISO,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser
	,$_frontIsLoggedin,$_frontIsLoggedin_user_id,$_loggedin,$extLang,$_LoggedUser,$clsConfiguration,$IsoEditor;
	$clsFPoint = new FPoint();
	$clsProfile = new Profile();
	$uid = $clsISO->getUniqid();
	$staff_id = (int) Input::post('staff_id');
	$oProfile = $clsProfile->getOne($staff_id, "`full_name`,`first_name`,`last_name`,`total_Lpoint`");
	$smarty->assign('staff_id', $staff_id);
	$smarty->assign('oProfile', $oProfile);
	###
	$list_preloaders = array();
	for($i=0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('list_preloaders', $list_preloaders);
	// Return
	$smarty->assign('template_type', '_modal');
	$html = $core->build('helper'.DS.'_ajax.Lpoint.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function helper_list_Lpoint(){
	global $smarty,$clsISO,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser
	,$_frontIsLoggedin,$_frontIsLoggedin_user_id,$_loggedin,$extLang,$_LoggedUser,$clsConfiguration,$IsoEditor;
	$clsFPoint = new FPoint();
	$clsProfile = new Profile();
	$uid = Input::post('uid');
	$staff_id = (int) Input::post('staff_id');
	$smarty->assign('uid', $uid);
	$smarty->assign('staff_id', $staff_id);
	$cond = "`ns_type`='Lpoint' and `profile_id`='{$staff_id}'";
	#- Begin pagination
	$current_page = (int) Input::post('page', 1);
	$per_page = (int) Input::post('per_page', 15);
	$total_record = $clsFPoint->countItem($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " limit {$offset},{$per_page}";
	#- End pagination
	$list_items = $clsFPoint->getAll($cond." order by reg_date DESC".$limitCond);
	if(!empty($list_items)){
		foreach($list_items as $key => $val){
			$symbol = '+';
			$act = $val['act'];
			$content = $val['content'];
			$parts = @explode('_', $act);
			if($act == '_minus'){
				$symbol = '-';
				$score_type = '<span class="text-primary">Trừ điểm</span>';
			} else if($act == '_plus'){
				$score_type = '<span class="text-success">Công điểm</span>';
			} else if($clsISO->checkContainer($act,'seniority','')){
				$score_type = '<span class="text-danger">Thâm niên</span>';
			} else if($clsISO->checkContainer($act,'team_','') || $clsISO->checkContainer($act,'dept_','')){
				$score_type = '<span class="text-secondary">Đội ngũ bán</span>';
			} else if($clsISO->checkContainer($act,'region_director_','')){
				$score_type = '<span class="text-secondary">Đội ngũ khu vực</span>';
			} else if($clsISO->checkContainer($act,'manage','')){
				$score_type = '<span class="text-info">Phụ trách</span>';
			} else {
				$score_type = '<span>Tự bán</span>';
			}
			$content = str_replace('giao dịch thành công','GDTC',$content);
			$list_items[$key]['score_type'] = $score_type;
			$list_items[$key]['content'] = $content;
			$list_items[$key]['symbol'] = $symbol;
		}
	}
	$smarty->assign('list_items', $list_items);
	$smarty->assign('current_page', $current_page);
	$smarty->assign('per_page', $per_page);
	$smarty->assign('total_page', $total_page);
	// Return
	$smarty->assign('template_type', '_list');
	$html = $core->build('helper'.DS.'_ajax.Lpoint.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'per_page' => $per_page,
		'total_record' => $total_record,
		'current_page' => $current_page,
		'total_page' => $total_page
	)); die();
}
function helper_hide_stock_MOC(){
	global $smarty,$clsISO,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser
	,$_frontIsLoggedin,$_frontIsLoggedin_user_id,$_loggedin,$extLang,$_LoggedUser,$clsConfiguration,$IsoEditor;
	$clsStock = new Stock();
	#
	$msg = "_error";
	$action = "_keep";
	$stock_id = (int) Input::post('stock_id');
	if($stock_id > 0){
		$key = 'MOC'; $key_p = 'user.fh'; 
		$show_website = $clsStock->getOneField("show_website", $stock_id);
		$show_website_arrs = !empty($show_website) 
			? $clsISO->getArrayByTextSlash($show_website) : array();
		if(in_array($key, $show_website_arrs)){
			// Remove
			$action = "_remove";
			$show_website_arrs = array_values(@array_diff($show_website_arrs, array($key)));
			$show_website_arrs = array_values(@array_diff($show_website_arrs, array($key_p)));
		} else {
			// Add
			$action = "_add";
			$show_website_arrs[] = $key;
			$show_website_arrs[] = $key_p;
		}
		$show_website = $clsISO->makeSlashListFromArrayRoot($show_website_arrs);
		if($clsStock->updateOne($stock_id, array(
			'show_website' => $show_website
		))){
			$msg = "_success";
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'action' => $action
	)); die();
}
function helper_history_price_log(){
	global $smarty,$_CONFIG,$dbconn,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	$clsProperty = new Property();
	##
	$uid = $clsISO->getUniqid();
	$stock_id = (int) Input::post('stock_id', 0);
	$oneStock = $clsStock->getOne($stock_id. "`ms_code`,`more_information`");
	$m_field = "{$clsStockMeta->pkey},`logs`";
	$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='{$stock_id}'", $m_field);
	##
	$list_logs = array();
	if(!empty($oneStockMeta)){
		$list_logs = $oneStockMeta['logs'];
		$list_logs = $clsISO->to_array_json($list_logs);
	}
	$more_information = $oneStock['more_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$smarty->assign('oneStock', $oneStock);
	###
	$data = array();
	if(!empty($list_logs)){
		$list_dates = array();
		foreach($list_logs as $key => $val){
			$field = $val['field'];
			if($field!='total_price_vat'){
				unset($list_logs[$key]);
			}
		}
		$checkExist = 0;
		foreach($list_logs as $key => $val){
			$from_value = (int)$clsISO->processSmartNumber($val["from_value"]);
			$from_value = $clsISO->convertPriceShortToFull($from_value);
			$to_value = (int)$clsISO->processSmartNumber($val["to_value"]);
			$to_value = $clsISO->convertPriceShortToFull($to_value);
			$list_logs[$key]["reg_date"] = date('d/m/Y H:i', $val['reg_date']);
			$list_logs[$key]["from_value"] = $clsISO->convertToNumber($from_value);
			$list_logs[$key]["to_value"] = $clsISO->convertToNumber($to_value);
		}
	}
//	$clsISO->print_pre($list_logs);die;
	$smarty->assign('list_logs', $list_logs);
	$smarty->assign('oneStock', $oneStock);
	// Return
	$html = $core->build('helper'.DS.'_ajax.history_price_log.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function helper_open_wishlist(){
	global $smarty,$_CONFIG,$dbconn,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	$clsStock = new Stock();
	$clsLeasing = new Leasing();
	$clsProperty = new Property();
	##
	$uid = $clsISO->getUniqid();	
	$clsSop = new Sop();
	$clsPolicy = new Policy();
	$clsStockMeta = new StockMeta();
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$smarty->assign('clsProject', $clsProject);
	$smarty->assign('clsLeasing', $clsLeasing);
	$smarty->assign('clsSop', $clsSop);
	$smarty->assign('clsProperty', $clsProperty);
	$lstBlock = $clsProperty->getArraySearchByKey("_BLOCK");
	$lstBuilding = $clsProperty->getArraySearchByKey("_BUILDING");
	$lstBedroom = $clsProperty->getArraySearchByKey("_BEDROOM");
	$lstHomeDirection = $clsProperty->getArraySearchByKey("_DIRECTION");
	$limit = " LIMIT 0,10";
	$more_information = $oneProfile['more_information'];
	//	var_dump($more_information);die;
	$wishlist = (!empty($oneProfile['wishlist']))
		? $clsISO->to_array_json($oneProfile['wishlist']):[];
	$list_stocks = array();
	if(!empty($wishlist)){
		$field= "{$clsStock->pkey},ms_code,bedroom_id,home_direction_id,project_id,block_id,building_id
		,status_id,more_information";
		$list_stocks = $clsStock->getAll("stock_id in (".implode(',',$wishlist).")".$limit, $field);
		$arr_status_cached = $arr_property_cached = array();
		foreach ($list_stocks as $key => $val) {
			$more_information_stock = $clsISO->to_array_json($val['more_information']);
			$list_stocks[$key]['more_information'] = $more_information_stock;
			$arr_property_cached[$val['block_id']] = $lstBlock[$val['block_id']]['title'];
			$arr_property_cached[$val['building_id']] = $lstBuilding[$val['building_id']]['title'];
			$arr_property_cached[$val['bedroom_id']] = $lstBedroom[$val['bedroom_id']]['title'];
			$arr_property_cached[$val['home_direction_id']] = $lstHomeDirection[$val['home_direction_id']]['title'];
			$list_stocks[$key]['block_name'] = $arr_property_cached[$val['block_id']];
			$list_stocks[$key]['building_name'] = $arr_property_cached[$val['building_id']];
			$list_stocks[$key]['bedroom'] = $arr_property_cached[$val['bedroom_id']];
			$list_stocks[$key]['home_direction'] = $arr_property_cached[$val['home_direction_id']];
			$list_stocks[$key]['having_dq'] = !(empty($more_information_leasing['having_dq']))?$more_information_leasing['having_dq']:0;
			#location
			$location = [];
			if(!empty($arr_property_cached[$val['building_id']])) {
				$location[] = $arr_property_cached[$val['building_id']];
			}
			if(!empty($arr_property_cached[$val['block_id']])) {
				$location[] = $arr_property_cached[$val['block_id']];
			}
			$list_stocks[$key]['location'] = implode(", ",$location);
		}
	}
	$smarty->assign('list_stocks', $list_stocks);
	// Return
	$smarty->assign('uid', $uid);
	$html = $core->build("helper".DS.'_ajax.loadFavourite.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
	)); die();
}
function helper_save_order(){
	global $oSmarty,$smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID,$profile_id,$oneProfile;
	$clsProfile = new Profile();
	// Action
	$orderNo = Input::post('orderNo');
	$area_id = (int)Input::post('area_id',0);
	$more_information = $oneProfile["more_information"];
	if(!empty($area_id)) {
		$more_information["order_block_area"][$area_id] = $orderNo;
	}else{
		$more_information["order_block"] = $orderNo;
	}	
	$res = ["result"=> false];
	if($clsProfile->updateOne($profile_id,["more_information" => json_encode($more_information,JSON_UNESCAPED_UNICODE)])) {
		$res = ["result"=> true];
	}
	echo json_encode($res,JSON_UNESCAPED_UNICODE);die;
}
function helper_open_contact(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$clsISO,$profile_id,$clsConfiguration,$deviceType;
	$clsProfile = new Profile();
	$uid = $clsISO->getUniqid();
	$ContactFooter = $clsConfiguration->getValue('ContactFooter');
	$ContactFooter = $clsISO->to_array_json($ContactFooter);
	$smarty->assign('ContactFooter', $ContactFooter);
	// Return
	$html = $core->build('helper'.DS.'_ajax.contact_footer.tpl');
	Response::echoResponse(200, array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function helper_save_contact(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$clsISO,$profile_id,$deviceType,$dbconn;
	$clsCache = new Cache();
	$clsConfiguration = new Configuration();
	##
	$msg = "_error";
	// $dbconn->debug = true;
	$ContactFooter = Input::post('ContactFooter');
	if($clsConfiguration->updateValue('ContactFooter', json_encode($ContactFooter, JSON_UNESCAPED_UNICODE))){
		$clsCache->delete('_header_configs_cached');
		$msg = "_success";
	} 
	// Return
	echo $msg; die();
}
/*note calendar*/
function helper_open_day(){
	global $oSmarty,$smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID,$profile_id,$oneProfile,$dbconn;
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsFollowUp = new FollowUp();
	$clsCustomer = new Customer();
	$clsBilling = new Billing();
	$clsBillingMeta = new BillingMeta();
	$clsCache = new Cache();
	// Action
	$date = Input::post('date',"");
	$lunar_text = Input::post('lunartext',"");
	$total = $total_birthday = 0;
	if($date != "") {
		$time = strtotime($date);
		#
		$arrTypeCustomer = $clsProperty->getArraySearchByKey("FOLLOWUP_TYPE");
		$sql_note_crm = "SELECT `t1`.`title`,`t1`.`date_id`,`t1`.`{$clsFollowUp->pkey}`,`t1`.`type_id`,`t1`.`intro`,`t2`.`customer_id`,`t2`.`name` FROM `{$clsFollowUp->tbl}` AS `t1` LEFT JOIN `{$clsCustomer->tbl}` AS `t2` ON `t1`.`customer_id` = `t2`.`customer_id` WHERE `t1`.`followup_type`='_crm' AND `t1`.`admin_id`='{$profile_id}' AND FROM_UNIXTIME(`t1`.`date_id`,'%Y-%m-%d')='{$date}' ORDER BY `t1`.`date_id` ASC";
		$lstNoteCRM = $dbconn->getAll($sql_note_crm);
		if(!empty($lstNoteCRM)) {
			foreach ($lstNoteCRM as $key => $val ){
	//			$txt_message = "<strong>".$arrTypeCustomer[$val["type_id"]]['title']."</strong> khách hàng <strong>".$val['name']."</strong>";
				$lstNoteCRM[$key]["followup_type_name"] = $arrTypeCustomer[$val["type_id"]]['title'];
				$lstNoteCRM[$key]["time"] = date("H:i",$val["date_id"]);
			}
			$total += count($lstNoteCRM);
		}
		#sinh nhật thành viên
		$arr_cache_role = $clsProperty->getArraySearchByKey("_ROLE");
		$lstProfileBirthday = $clsProfile->getAll("`is_trash`=0 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."' AND FROM_UNIXTIME(`birthday`,'%d-%m')='".date('d-m',strtotime($date))."' ", "{$clsProfile->pkey},first_name,last_name,full_name,department_id,role_id");
		if(!empty($lstProfileBirthday)) {
			foreach ($lstProfileBirthday as $key => $val) {
				if(!isset($arr_cache_dep[$val["department_id"]])) {
					$arr_cache_dep[$val[$clsProfile->pkey]] = $clsProperty->getTitle($val['department_id']);
				}
				if(!isset($arr_cache_role[$val["role_id"]])) {
					$arr_cache_role[$val["role_id"]] = $clsProperty->getOne($val['role_id']);
				}
					$lstProfileBirthday[$key]["depart_name"] = $arr_cache_dep[$val[$clsProfile->pkey]];
				$lstProfileBirthday[$key]["role_name"] = $arr_cache_role[$val["role_id"]]["title"];
			}
			$total_birthday += count($lstProfileBirthday);
		}
		#sinh nhat khach hang
		$lstCustomer = $clsCustomer->getAll("`admin_id`='{$profile_id}' AND FROM_UNIXTIME(`birthday`,'%Y-%m-%d')='{$date}'","name");
		$total_birthday += !empty($lstCustomer) ? count($lstCustomer) : 0;
		#lich ky
		$field = "{$clsBilling->pkey},`stock_code`,`contract_date`,`contract_status_id`,`estimate_date`,`agree_date`,`billing_source_id`,`billing_type`,`agree_status_id`,`more_information`,`admin_id`,`staff_id`";
		$cond = "`is_trash`=0 AND `is_cancel`=0";	
		$sql_query = "SELECT {$field},'_new' AS `sign_type` FROM {$clsBilling->tbl} WHERE {$cond} AND ((FROM_UNIXTIME(`contract_date`,'%Y-%m-%d')='{$date}') OR (FROM_UNIXTIME(`agree_date`,'%Y-%m-%d')='{$date}'))";
		$sql_union_query = "SELECT {$field},'_old' AS `sign_type` FROM {$clsBilling->tbl} WHERE {$cond}  AND EXISTS (SELECT 1 FROM {$clsBillingMeta->tbl} WHERE FROM_UNIXTIME(`meta_value`, '%Y-%m-%d')='{$date}' AND {$clsBilling->tbl}.`billing_id`={$clsBillingMeta->tbl}.`billing_id`)";
		if($clsISO->checkPermissionGroup('PROJECT_DIRECTOR')){
			$more_profile = $oneProfile['more_information'];
			$permiss_billing = $core->get_field($more_profile, 'permiss_billing', []);
			if(!empty($permiss_billing)){
				$sql_query .= " AND ((1=1";
				$sql_union_query .= " AND ((1=1";
				$arr_billing_types = array();
				$arr_projects = array_keys($permiss_billing);
				foreach($permiss_billing as $key => $val){
					$arr_billing_types = array_merge($arr_billing_types, $val);
				}
				if(!empty($arr_projects)){
					$sql_query.= " AND `project_id` IN (".implode(',',$arr_projects).")";
					$sql_union_query.= " AND `project_id` IN (".implode(',',$arr_projects).")";
				}
				if(!empty($arr_billing_types)){
					$arr_billing_types = array_unique($arr_billing_types);
					$sql_query.= " AND `billing_type` IN (".implode(',',$arr_billing_types).")";
					$sql_union_query.= " AND `billing_type` IN (".implode(',',$arr_billing_types).")";
				}
				$sql_query .= ")";
				$sql_query .= " OR `staff_id`='{$profile_id}'";
				$sql_query .= ")";
				$sql_union_query .= ")";
				$sql_union_query .= " OR `staff_id`='{$profile_id}'";
				$sql_union_query .= ")";
			}
		}else{
			$sql_query .= " AND `staff_id`='{$profile_id}'";
			$sql_union_query .= " AND `staff_id`='{$profile_id}'";
		}
//			$clsBilling->setDeBug(1);
		$list_billings = $dbconn->getAll("{$sql_query} UNION ALL {$sql_union_query}");
		if(!empty($list_billings)) {
			$arr_admins = array();
			$field = "{$clsProfile->pkey},`full_name`,`avatar`,`more_information`";
			$list_admins = $clsProfile->getAll("`is_trash`='0' AND `role_id`='"._ROLE_STAFF_ADMIN."'", $field);
			if(!empty($list_admins)){
				foreach($list_admins as $key => $val){
					$admin_id = $val[$clsProfile->pkey];
					$arr_admins[$admin_id] = $val;
				}
			}
			$arr_cache_dep = [];
			foreach($list_billings as $key => $val) {
				$admin_id = (int) $val['admin_id'];
				if($admin_id == 0) $admin_id = _PROFILE_ADMIN_ID;
				$sign_type = $val['sign_type'];
				$billing_type = (int) $val['billing_type'];
				$agree_date = (int) $val['agree_date'];
				$contract_date = (int) $val['contract_date'];
				$agree_status_id = (int) $val['agree_status_id'];
				$contract_status_id = (int) $val['contract_status_id'];
				$more_billing = $val['more_information'];
				$more_billing = $clsISO->to_array_json($more_billing);
				$text_type = array(); $time_k = $status = "";
				$is_note = !empty($more_billing['rescheduling_reason']) ? 1 : 0;
				$list_billings[$key]["is_note"] = $is_note;
				if($contract_date > 0) { // && date('d/m/Y', $contract_date) == $date
					$text_type[] = 'HĐMB';
					if(!empty($estimate_date)){
						$time_k = date("H:i",$estimate_date);
						$day = $clsISO->formatDate($estimate_date,4);
					}
					if(!empty($contract_date)){
						$time_k = date("H:i",$contract_date);
						$day = $clsISO->formatDate($contract_date,4);
					}
					if($sign_type == '_new'){
						$status = "<span class=\"text-danger\">Chưa ký</span>";
						if($contract_status_id == _CONTRACT_STATUS_DONE_ID){
							$status = "<span class=\"text-success\">Đã ký</span>";
						}
					} else {
						$status = "<span class=\"text-muted\">Hủy ký</span>";
					}
				} else if($agree_date > 0){ //  && date('d/m/Y', $agree_date) == $date
					if($sign_type == '_new'){
						$status = "<span class=\"text-danger\">Chưa ký</span>";
						if($agree_status_id == _CONTRACT_STATUS_AGREE_SIGNED_ID){
							$status = "<span class=\"text-success\">Đã ký</span>";
						}
					} else {
						$status = "<span class=\"text-muted\">Hủy ký</span>";
					}
					$text_type[] = 'VBTT</span>';
					$time_k = date("H:i",$agree_date);
					$day = $clsISO->formatDate($agree_date,4);
				}
				if(!isset($arr_cache_profile[$val["staff_id"]])) {
					$_oProfile = $clsProfile->getOne($val["staff_id"],$clsProfile->pkey.',full_name,last_name,department_id');
					if(!isset($arr_cache_dep[$_oProfile["department_id"]])) {
						$arr_cache_dep[$val["staff_id"]] = $clsProperty->getTitle($_oProfile['department_id']);
					}
					$_oProfile["depart_name"] = $arr_cache_dep[$val["staff_id"]];
					$arr_cache_profile[$val["staff_id"]] = $_oProfile;
					unset($_oProfile);				
				}
				$list_billings[$key]["staff"] = $arr_cache_profile[$val["staff_id"]];
				$list_billings[$key]["time"] = $time_k;
				$list_billings[$key]["day"] = $day;
				$list_billings[$key]["status"] = $status;
				$admin = $arr_admins[$admin_id];
				$list_billings[$key]["admin"] = $admin;
				$list_billings[$key]["text_type"] = implode(',', $text_type);
				++$total;
			}
//				$clsISO->print_pre($list_billings);die;
			$smarty->assign("list_billings",$list_billings);
		}
		#
		$clsCourse = new Course();
		$clsCheckIn = new CheckIn();
		$clsGroupProfile = new GroupProfile();
		$smarty->assign("clsCourse",$clsCourse);
		$cache_name = sprintf("_profile_group_%s_cached",$profile_id);
		if($clsCache->has($cache_name)){
			$arr_profile_groups = $clsCache->get($cache_name);
		} else {
			$arr_profile_groups = array();
			$tmp = $clsGroupProfile->getAllCache("`list_profile_id` like '%|{$profile_id}|%'", $clsGroupProfile->pkey);
			if(!empty($tmp)){
				foreach($tmp as $key => $val){
					$arr_profile_groups[] = $val[$clsGroupProfile->pkey];
				}
				unset($tmp);
			}
			// Save Cached
			$clsCache->put($cache_name, $arr_profile_groups, 60*60);
		}
		$department_id = $oneProfile['department_id'];
		$list_department_id = $oneProfile['list_department_id'];
		$arr_department_ids = !empty($list_department_id) ? $clsISO->getArrayByTextSlash($list_department_id) : array();
		$arr_department_ids[] = $department_id;
		$arr_department_ids = array_unique($arr_department_ids);
		$cond = "and (`is_all_staff` = '1' OR ((`is_all_staff`='0' and (";
		$ii = 0;
		foreach($arr_department_ids as $id){
			$cond.= ($ii==0 ? "": " or ")."`list_department_id` like '%|{$id}|%'";
			++$ii;
		}
		$sql_group = "";
		if(!empty($arr_profile_groups)){$ii = 0;
			foreach($arr_profile_groups as $group_id){
				$sql_group.= ($ii==0 ? "" : " or "). " `list_group_profile_id` like '%|{$group_id}|%'";
				++$ii;
			}
		}
		$cond .= " or `list_profile_id` LIKE '%|{$profile_id}|%' 
			or `user_id`='{$profile_id}'))".(!empty($arr_profile_groups)? " 
			or (`is_all_staff`=2 and (".$sql_group."))" : "")."))";
		$start_time = strtotime(date("Y-m-d 00:00",$time));
		$end_time = strtotime(date("Y-m-d 23:59",$time));
		$list_events = $clsCourse->getAll("`is_trash`=0 and `is_online`=1 and `start_date`<='{$end_time}' AND `due_date`>='{$start_time}' ".$cond." order by `start_date`,`due_date` asc");
		foreach($list_events as $key => $val){
			$course_id = $val[$clsCourse->pkey];
			$cat_id = (int) $val['cat_id'];
			$start_date = $val['start_date'];
			$due_date = $val['due_date'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			if(time() < $start_date){
				$list_events[$key]['status'] = '<span class="badge bg-label-info">Sắp diễn ra</span>';
			}else if($start_date < time() && time() < $due_date){
				$list_events[$key]['status'] = '<span class="badge bg-label-success">Đang diễn ra</span>';
			}else{
				$list_events[$key]['status'] = '<span class="badge bg-label-danger">Đã diễn ra</span>';
			}
			++$total;
		}
		$smarty->assign("list_events",$list_events);
		$uid = $clsISO->getUniqid();
		$smarty->assign("uid",$uid);
		$smarty->assign("lstFollow",$lstFollow);
		$smarty->assign("time",$time);
		$smarty->assign("date",$date);
		$smarty->assign("lunar_text",$lunar_text);
		$smarty->assign("lstNoteCRM",$lstNoteCRM);
		$smarty->assign("lstCustomer",$lstCustomer);
		$smarty->assign("clsProfile",$clsProfile);
		$smarty->assign("lstProfileBirthday",$lstProfileBirthday);
		$smarty->assign("total",$total);
		$smarty->assign("total_birthday",$total_birthday);
		// Return
		$html = $core->build('helper'.DS.'_ajax.open_day.tpl');
	}
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'total' => $total,
		'total_birthday' => $total_birthday,
	),JSON_UNESCAPED_UNICODE); die();
}
function helper_load_note_number(){
	global $oSmarty,$smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID,$profile_id,$oneProfile,$dbconn;
	$clsProperty = new Property();
	$clsFollowUp = new FollowUp();
	$clsCustomer = new Customer();
	$clsProfile = new Profile();
	$clsBilling = new Billing();
	$clsBillingMeta = new BillingMeta();
	$clsCache = new Cache();
	$year = Input::post("year","");
	$month = Input::post("month","");
	$ym = $year."-".$month;
	$startOfMonth = strtotime("$year-$month-01");
	$start = strtotime("-7 days", $startOfMonth);
	$endOfMonth = strtotime(date("Y-m-t", $startOfMonth));
	$end = strtotime("+7 days", $endOfMonth);
	#
	$lstFollowUp = $clsFollowUp->getAll("(`followup_type`='_note' OR `followup_type`='_crm') AND `admin_id`='{$profile_id}' AND `date_id` BETWEEN {$start} AND {$end} GROUP BY `date_time`","FROM_UNIXTIME(`date_id`,'%Y-%m-%d') as date_time, COUNT({$clsFollowUp->pkey}) as total"); 
	$arr_note = [];
	if(!empty($lstFollowUp)) {
		foreach ($lstFollowUp as $key => $val) {
			$arr_note["{$val['date_time']}"] = (int)$val["total"];
		}
	}
	#sinh nhật thành viên		
	$arr_birthday = [];
	$lstProfileBirthday = $clsProfile->getAll("`is_trash`=0 AND `status_id`<>'"._STATUS_STAFF_OFF_ID."' AND (FROM_UNIXTIME(`birthday`,'%m')='".date("m",$startOfMonth)."' OR FROM_UNIXTIME(`birthday`,'%m')='".date("m",$start)."' OR FROM_UNIXTIME(`birthday`,'%m')='".date("m",$end)."') GROUP BY date_time ", "FROM_UNIXTIME(`birthday`,'{$year}-%m-%d') as date_time, COUNT(`{$clsProfile->pkey}`) AS total");
	if(!empty($lstProfileBirthday)) {
		foreach ($lstProfileBirthday as $key => $val) {
			if($val["total"] > 0 && $val['date_time'] != "") {
				if(!isset($arr_birthday["{$val['date_time']}"])) {
					$arr_birthday["{$val['date_time']}"] = (int)$val["total"];
				}else{
					$arr_birthday["{$val['date_time']}"] += (int)$val["total"];
				}
			}
		}
	}
	#sinh nhat khach hang
	$lstCustomer = $clsCustomer->getAll("`admin_id`='{$profile_id}' AND (FROM_UNIXTIME(`birthday`,'%m')='".date("m",$startOfMonth)."' OR FROM_UNIXTIME(`birthday`,'%m')='".date("m",$start)."' OR FROM_UNIXTIME(`birthday`,'%m')='".date("m",$end)."')","FROM_UNIXTIME(`birthday`,'{$year}-%m-%d') as date_time, COUNT(`{$clsCustomer->pkey}`) AS total");
	if(!empty($lstCustomer)) {
		foreach ($lstCustomer as $key => $val) {
			if($val["total"] > 0 && $val['date_time'] != "") {
				if(!isset($arr_birthday["{$val['date_time']}"])) {
					$arr_birthday["{$val['date_time']}"] = (int)$val["total"];
				}else{
					$arr_birthday["{$val['date_time']}"] += (int)$val["total"];
				}
			}
		}
	}
	#lich ky
	$field = "{$clsBilling->pkey},`stock_code`,`contract_date`,`contract_status_id`,`estimate_date`,`agree_date`,`billing_source_id`,`billing_type`,`agree_status_id`,`more_information`,`admin_id`,`staff_id`";
	$cond = "`is_trash`=0 AND `is_cancel`=0";	
	$sql_query = "SELECT {$field},'_new' AS `sign_type` FROM {$clsBilling->tbl} WHERE {$cond} AND ((`contract_date` BETWEEN {$start} AND {$end}) OR (`agree_date` BETWEEN {$start} AND {$end}))";
	$sql_union_query = "SELECT {$field},'_old' AS `sign_type` FROM {$clsBilling->tbl} WHERE {$cond}  AND EXISTS (SELECT 1 FROM {$clsBillingMeta->tbl} WHERE (`meta_value` BETWEEN {$start} AND {$end}) AND {$clsBilling->tbl}.`billing_id`={$clsBillingMeta->tbl}.`billing_id`)";
	if($clsISO->checkPermissionGroup('PROJECT_DIRECTOR')){
		$more_profile = $oneProfile['more_information'];
		$permiss_billing = $core->get_field($more_profile, 'permiss_billing', []);
		if(!empty($permiss_billing)){
			$sql_query .= " AND ((1=1";
			$sql_union_query .= " AND ((1=1";
			$arr_billing_types = array();
			$arr_projects = array_keys($permiss_billing);
			foreach($permiss_billing as $key => $val){
				$arr_billing_types = array_merge($arr_billing_types, $val);
			}
			if(!empty($arr_projects)){
				$sql_query.= " AND `project_id` IN (".implode(',',$arr_projects).")";
				$sql_union_query.= " AND `project_id` IN (".implode(',',$arr_projects).")";
			}
			if(!empty($arr_billing_types)){
				$arr_billing_types = array_unique($arr_billing_types);
				$sql_query.= " AND `billing_type` IN (".implode(',',$arr_billing_types).")";
				$sql_union_query.= " AND `billing_type` IN (".implode(',',$arr_billing_types).")";
			}
			$sql_query .= ")";
			$sql_query .= " OR `staff_id`='{$profile_id}'";
			$sql_query .= ")";
			$sql_union_query .= ")";
			$sql_union_query .= " OR `staff_id`='{$profile_id}'";
			$sql_union_query .= ")";
		}
	}else{
		$sql_query .= " AND `staff_id`='{$profile_id}'";
		$sql_union_query .= " AND `staff_id`='{$profile_id}'";
	}
	$list_billings = $dbconn->getAll("{$sql_query} UNION ALL {$sql_union_query}");
	if(!empty($list_billings)) {
		$arr_cache_dep = [];
		foreach($list_billings as $key => $val) {
			$agree_date = (int) $val['agree_date'];
			$contract_date = (int) $val['contract_date'];
			$text_type = array(); $time_k = $status = "";
			if($contract_date > 0) { // && date('d/m/Y', $contract_date) == $date
				if(!empty($estimate_date)){
					if(!isset($arr_note[date("Y-m-d",$estimate_date)])) {
						$arr_note[date("Y-m-d",$estimate_date)] = 1;
					}else{
						$arr_note[date("Y-m-d",$estimate_date)] += 1;
					}
					$day = $clsISO->formatDate($estimate_date,4);
				}
				if(!empty($contract_date)){
					if(!isset($arr_note[date("Y-m-d",$contract_date)])) {
						$arr_note[date("Y-m-d",$contract_date)] = 1;
					}else{
						$arr_note[date("Y-m-d",$contract_date)] += 1;
					}
					$day = $clsISO->formatDate($contract_date,4);
				}
			} else if($agree_date > 0){
				if(!isset($arr_note[date("Y-m-d",$agree_date)])) {
					$arr_note[date("Y-m-d",$agree_date)] = 1;
				}else{
					$arr_note[date("Y-m-d",$agree_date)] += 1;
				}
				$day = $clsISO->formatDate($agree_date,4);
			}
			$list_billings[$key]["date"] = $day;
		}
	}	
	#course
	$clsCourse = new Course();
	$clsCheckIn = new CheckIn();
	$clsGroupProfile = new GroupProfile();
	$smarty->assign('clsCourse', $clsCourse);
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsGroupProfile', $clsGroupProfile);
	###
	$uid = $clsISO->getUniqid();
	$arr_profile_groups = array();
	$cache_name = sprintf("_profile_group_%s_cached",$profile_id);
	if($clsCache->has($cache_name)){
		$arr_profile_groups = $clsCache->get($cache_name);
	} else {
		$arr_profile_groups = array();
		$tmp = $clsGroupProfile->getAllCache("`list_profile_id` like '%|{$profile_id}|%'", $clsGroupProfile->pkey);
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$arr_profile_groups[] = $val[$clsGroupProfile->pkey];
			}
			unset($tmp);
		}
		// Save Cached
		$clsCache->put($cache_name, $arr_profile_groups, 60*60);
	}
	$department_id = $oneProfile['department_id'];
	$list_department_id = $oneProfile['list_department_id'];
	$arr_department_ids = !empty($list_department_id) ? $clsISO->getArrayByTextSlash($list_department_id) : array();
	$arr_department_ids[] = $department_id;
	$arr_department_ids = array_unique($arr_department_ids);
	$cond = " and (e.`is_all_staff` = '1' OR ((e.`is_all_staff`='0' and (";
	$ii = 0;
	foreach($arr_department_ids as $id){
		$cond.= ($ii==0 ? "": " or ")."e.`list_department_id` like '%|{$id}|%'";
		++$ii;
	}
	$sql_group = "";
	if(!empty($arr_profile_groups)){$ii = 0;
		foreach($arr_profile_groups as $group_id){
			$sql_group.= ($ii==0 ? "" : " or "). " e.`list_group_profile_id` like '%|{$group_id}|%'";
			++$ii;
		}
	}
	$cond .= " or e.`list_profile_id` LIKE '%|{$profile_id}|%' 
		or e.`user_id`='{$profile_id}'))".(!empty($arr_profile_groups)? " 
		or (e.`is_all_staff`=2 and (".$sql_group."))" : "")."))";
	$total_events = 0;
	$cond_course = "WITH RECURSIVE
		date_range AS(
		SELECT
			".$start." AS day_start
		UNION ALL
	SELECT
		day_start + 86400
	FROM
		date_range
	WHERE
		day_start + 86400 <= ".$end."
	)
	SELECT
		FROM_UNIXTIME(dr.day_start, '%Y-%m-%d') AS event_day,
		COUNT(e.course_id) AS total_events
	FROM
		date_range dr
	LEFT JOIN default_course e ON
		e.start_date <= dr.day_start + 86399 AND e.due_date >= dr.day_start ".$cond."
	GROUP BY
		event_day
	HAVING total_events > 0
	ORDER BY
		event_day;";
	$list_events = $dbconn->getAll($cond_course);
	if(!empty($list_events)) {
		foreach($list_events as $key => $val) {
			if(!empty($val["total_events"])) {
				if(!isset($arr_note[$val["event_day"]])) {
					$arr_note[$val["event_day"]] = (int)$val["total_events"];
				}else{
					$arr_note[$val["event_day"]] += (int)$val["total_events"];
				}
			}
		}
	}
	// Action	
	echo json_encode(array(
		'data'	=>	json_encode($arr_note,JSON_UNESCAPED_UNICODE),
		'data_birthday'	=>	json_encode($arr_birthday,JSON_UNESCAPED_UNICODE),
	),JSON_UNESCAPED_UNICODE); die();
}
function helper_load_note(){
	global $oSmarty,$smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID,$profile_id,$oneProfile;
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsFollowUp = new FollowUp();
	$date = Input::post("date","");
	$toId = input::post("toId","");
	$html = "";
	if($date != "" && $toId != "") {
		$lstFollowUp = $clsFollowUp->getAll("`followup_type`='_note' AND `admin_id`='{$profile_id}' AND FROM_UNIXTIME(`date_id`,'%Y-%m-%d')='{$date}' ORDER BY date_id ASC");
		if(!empty($lstFollowUp)) {
			foreach ($lstFollowUp as $key => $val){
				$time = date("H:i",$val["date_id"]);
				$html .= '<div class="d-flex justify-content-between align-items-start py-1 item_note_'.$toId.'_'.$val[$clsFollowUp->pkey].'"  >
						<div class="note-item"><strong>'.$time.'</strong> — '.$val["intro"].'</div>
						<div class="d-flex gap-1 align-items-center">
							<button class="btn btn-icon btn-sm" type="button" onClick="$Core.note_calendar.open_note(this,event)" note_id="'.$val[$clsFollowUp->pkey].'" date="'.$date.'" _type="'.$val['followup_type'].'" toId="'.$toId.'" title="Sửa" ><i class="bx bx-edit-alt"></i></button>
							<button class="btn btn-icon btn-sm" type="button" onClick="$Core.note_calendar.delete_note(this,event)" note_id="'.$val[$clsFollowUp->pkey].'" date="'.$date.'" _type="'.$val['followup_type'].'" toId="'.$toId.'" title="Xóa" ><i class="bx bx-trash"></i></button>
						</div>						
					</div>';
			}
		}else {
			$html = '<div class="text-center text-muted">
						<img src="'.URL_IMAGES.'/listing-empty.svg" class="w-px-50">
						<p>Chưa có ghi chú!</p>
					</div>';
		}
	}else {
		$html = '<div class="text-center text-muted">
					<img src="'.URL_IMAGES.'/listing-empty.svg" class="w-px-50">
					<p>Chưa có ghi chú!</p>
				</div>';
	}
	// Action	
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'total_note'	=>	!empty($lstFollowUp) ? count($lstFollowUp) : 0
	),JSON_UNESCAPED_UNICODE); die();
}
function helper_open_note(){
	global $oSmarty,$smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID,$profile_id,$oneProfile;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsFollowUp = new FollowUp();
	// Action
	$uid = $clsISO->getUniqid();
	$note_id = (int) Input::post('note_id',0);
	$toId = Input::post('toId',"");
	$date = Input::post('date',"");
	$_type = Input::post('_type',"_note");
	$time_id = strtotime("+ 30 minutes");
	#
	$list_activity = $clsProperty->getAllCache("`is_trash`=0 and `property_type`='FOLLOWUP_TYPE' 
		order by `order_no` ASC", "{$clsProperty->pkey},`title`,`image`");
	$smarty->assign("list_activity",$list_activity);
	#
	$action = "_add";
	$oneItem = array(); //'type_id' => _FOLLOWUP_TASK_ID
	if($note_id > 0) {
		$action = "_edit";
		$time = strtotime($date);
		$oneItem = $clsFollowUp->getOne($note_id);	
	}	
	$list_times = [
		'-15 minutes'	=> 'Trước 15p',
		'-30 minutes'	=> 'Trước 30p',
		'-1 hour'		=> 'Trước 1h',
		'-2 hours'		=> 'Trước 2h',
		'-5 hours'		=> 'Trước 5h',
		'-8 hours'		=> 'Trước 8h',
		'-12 hours'		=> 'Trước 12h',
		'-18 hours'		=> 'Trước 18h',
		'-1 day'		=> 'Trước 1 ngày',
		'-2 days'		=> 'Trước 2 ngày',
		'-7 days'		=> 'Trước 7 ngày',
		'-15 days'		=> 'Trước 15 ngày',
		'-30 days'		=> 'Trước 30 ngày'
	];
	$smarty->assign("uid",$uid);
	$smarty->assign("date",$date);
	$smarty->assign("toId",$toId);
	$smarty->assign("action",$action);
	$smarty->assign("note_id",$note_id);
	$smarty->assign("oneItem",$oneItem);
	$smarty->assign("_type",$_type);
	$smarty->assign('list_times', $list_times);
	$html = $core->build('helper'.DS.'_ajax.open_note.tpl');
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	),JSON_UNESCAPED_UNICODE); die();
}
function helper_save_note(){
	global $oSmarty,$smarty,$profile_id,$core,$clsISO,$oneProfile,$dbconn;
	$clsNotify = new Notify();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();	
	##
	$res = [
		"result"	=>	false,
		"msg"		=>	"Error"
	];
	$toId = Input::post('toId', "");
	$note_id = (int) Input::post('note_id', 0);
	$type_id = (int) Input::post('type_id', 0);
	$intro = Input::post('intro', ""); // Content
	$date_id = Input::post('date_id');
	$time_id = Input::post('time_id');
	$_type = Input::post('_type',"_note");
	$datetime = strtotime(sprintf("%s %s", $date_id, $time_id));
	$is_reminder = (int)Input::post('is_reminder',0);
	$is_send_zalo = (int)Input::post('is_send_zalo',0);
	$reminder_before = Input::post('before_time',"");
	$reminder_time = 0;
	if(!empty($is_reminder)) {
		$reminder_time = strtotime($reminder_before, $datetime);
	}
	// $clsISO->print_pre($list_purpose_id); die();
	if($note_id == 0){
		$note_id = $clsFollowUp->getMaxId();
		$arr_data = array(
			$clsFollowUp->pkey => $note_id,
			'followup_type' => $_type,
			'type_id' => $type_id,
			'customer_id' => 0,
			'intro' => $intro,
			'date_id' => $datetime,
			'is_reminder' => $is_reminder,
			'reminder_before' => $reminder_before,
			'reminder_time' => $reminder_time,
			'is_send_zalo' => $is_send_zalo,
			'status_id' => _FOLLOWUP_STATUS_PLAN_ID,
			'admin_id' => $profile_id,
			'user_id' => $profile_id,
			'user_id_update' => $profile_id,
			'reg_date' => time(),
			'upd_date' => time()
		);
		// $dbconn->debug = true;
		if($clsFollowUp->insert($arr_data)){
			$res = [
				"result"	=>	true,
				"msg"		=>	"Thêm ghi chú thành công"
			];
			$msg = "_success";	
		}
	} else {
		$oneFollowup = $clsFollowUp->getOne($note_id);
		$arr_data = array(
			'intro' => $intro,
			'date_id' => $datetime,
			'is_reminder' => $is_reminder,
			'reminder_before' => $reminder_before,
			'reminder_time' => $reminder_time,
			'is_send_zalo' => $is_send_zalo,
			'status_id' => $status_id,
			'user_id_update' => $profile_id,
			'upd_date' => time()
		);
		if($clsFollowUp->updateOne($note_id, $arr_data)){
			$msg = "_success";	
			$res = [
				"result"	=>	true,
				"msg"		=>	"Cập nhật ghi chú thành công"
			];
		}
	}
	// Reuturn
	echo json_encode($res,JSON_UNESCAPED_UNICODE); die();
}
function helper_delete_note(){
	global $oSmarty,$smarty,$assign_list,$profile_id,$core,$clsISO,$oneProfile;
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	###
	$note_id = (int) Input::post('note_id', 0);
	###
	$msg = "_error";
	$oneFollowup = $clsFollowUp->getOne($note_id);	
	if($clsFollowUp->deleteOne($note_id)){
		$msg = "_success";
		#activity log
//		$clsActivityLog = new ActivityLog();	
//		$log = $clsActivityLog->addActivityLog("FollowUp","delete",$oneFollowup);
	}
	// Return
	echo $msg; die();
}
function helper_open_market_data(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	global $deviceType;
	$clsSetting = new Setting();
	$uid = $clsISO->getUniqid();
	##
	$lstPackage = $clsSetting->getCacheItems("_PACKAGE_DATA");
	foreach ($lstPackage as $key => $val) {
		$lstPackage[$key]["more_information"] = $clsISO->to_array_json($val['more_information']);
	}
	$smarty->assign('lstPackage', $lstPackage);
	// Return
	$smarty->assign('uid', $uid);
	$html = $core->build('helper'.DS.'_ajax.package_data.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function helper_open_package(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	global $deviceType;
	$clsSetting = new Setting();
	$uid = $clsISO->getUniqid();
	$package_id = (int)Input::post("package_id",0);
	$_oItem = $clsSetting->getOne($package_id);
	$res = ["result" => false,"msg"=>"Lỗi!","html" => ""];
	if(!empty($_oItem)) {
		$more_information = $clsISO->to_array_json($_oItem["more_information"]);
		$smarty->assign('package_id', $package_id);
		$smarty->assign('_oItem', $_oItem);
		$smarty->assign('more_information', $more_information);
		$html = $core->build('helper'.DS.'_ajax.open_package.tpl');
		$res = ["result" => true,"msg"=>"Success", "html"	=>	$html,"uid"=>$uid];
	}
	// Return	
	echo json_encode($res); die();
}
function helper_save_req(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$clsConfiguration;
	global $profile_id, $oneProfile;
	$clsProfile = new Profile();
	$clsSetting = new Setting();
	$clsCustomer = new Customer();
	$clsRequestCus = new RequestCus();
	$clsZalo = new Zalo();
	###
	$msg = "_error";
	$project_name = Input::post('project_name');
	$notes = Input::post('notes');
	$package_id = (int)Input::post('package_id',0);
	$onePackage = $clsSetting->getOne($package_id);
	if(!empty($onePackage)) {
		$more_package = $clsISO->to_array_json($onePackage["more_information"]);
		$amount = $more_package["total_data"];
		$more_information = array(
			'amount' => $amount,
			'project_name' => $project_name,
			'notes' => $notes,
			'package_id' => $package_id,
		);
		if($clsRequestCus->insert(array(
			'amount' => $amount,
			'_type' => "_buy",
			'project_name' => $project_name,
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'user_id' => $profile_id,
			'reg_date' => time()
		))){
			$msg = "_success";
			// Send Zalo
			$message = "======================";
			$message.= "\n";
			$message.= sprintf("📢**%s %s** yêu cầu mua gói data khách hàng **%s**", $oneProfile['role_name'] , $clsProfile->getFullName($profile_id, $oneProfile),$onePackage["title"]);
			$message.= "\n";
			$message.= "Số lượng: {color:#C00000}".$amount."{/color} khách hàng";
			$message.= "\n";
			$message.= "Đơn giá: {color:#C00000}".$clsISO->shortNumber($more_package['price_data'],0)."{/color}";
			$message.= "\n";
			$message.= sprintf("Dự án: %s", $project_name);
			if(!empty($notes)){
				$message.= sprintf("Ghi chú: %s", $notes);
			}
			$message.= "\n";
			$notify_zalo_recipient = $clsConfiguration->getValue('notify_zalo_recipient');
			$notify_zalo_recipient = !empty($notify_zalo_recipient) ? $clsISO->to_array_json($notify_zalo_recipient) : array();
			#thông báo gửi yêu cầu
			if(!empty($notify_zalo_recipient)) {
				foreach ($notify_zalo_recipient as $pro_id) {
					$clsZalo->sendZaloUser($pro_id,$message);
				}
			}
			#thông báo xác nhận gửi yêu cầu
			$message = "======================";
			$message.= "\n";
			$message.= "📢**Thông tin đăng ký gói data khách hàng";
			$message.= "\n";
			$message.= "Gói khách hàng: {color:#C00000}".$onePackage["title"]."{/color}";
			$message.= "\n";
			$message.= "Số lượng: {color:#C00000}".$amount."{/color} khách hàng";
			$message.= "\n";
			$message.= "Đơn giá: {color:#C00000}".$clsISO->shortNumber($more_package['price_data'],0)."{/color}";
			$message.= "\n";
			$message.= sprintf("Dự án: %s", $project_name);
			if(!empty($notes)){
				$message.= sprintf("Ghi chú: %s", $notes);
			}
			$message.= "\n";
			$clsZalo->sendZaloUser($profile_id,$message,$oneProfile);
		}
	}
	// return
	echo $msg; die();
}
function helper_open_config_menu(){
	global $oSmarty,$smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID,$profile_id,$oneProfile;
	$clsProfile = new Profile();
	$clsFollowUp = new FollowUp();
	$clsProperty = new Property();
	// Action
	$uid = $clsISO->getUniqid();
	$role_id = $oneProfile['role_id'];
	$department_id = $oneProfile['department_id'];
	$more_profile = $oneProfile["more_information"];
	$role = $clsProperty->getRoleUtilities($role_id);
	$order = 1; $utilities_active = $arr_utilities_unactive = $lst_utilities = array();
	$list_utilities = $clsProperty->getCacheItems("_ULTILITIES");
	$role_permiss = $clsProperty->getArraySearchByKey("_ROLE", $role_id);
	$more_permiss =  $clsISO->to_array_json($role_permiss["more_information"]);
	$permiss_ultilities = $core->get_field($more_permiss, "permiss_ultilities", []);
	$utilites_default = [];
	if(!empty($permiss_ultilities)) {
		for($i=0; $i<12; $i++) {
			$utilites_default[] = $permiss_ultilities[$i];
		}
	}else{
		$ii = 0;
		foreach($list_utilities as $key => $val) {
			$id = $val[$clsProperty->pkey];
			$more_unnity = $val['more_information'];
			$more_unnity = $clsISO->to_array_json($more_unnity);
			$arr_role = $core->get_field($more_unnity, "role", []);
			if($ii < 12){
				$utilites_default[] = $id;
				++$ii;
			}
		}
	}
//	$clsISO->print_pre($utilites_default);die;
	$utilites_profile = $core->get_field($more_profile, "utilites_menu", $utilites_default);
//	$clsISO->print_pre($utilites_profile);die;
	$order_max = count($utilites_profile) + 1;
	if(!empty($list_utilities)){
		foreach($list_utilities as $key => $val) {		
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$arr_role = $core->get_field($more_information, "role", []);
			if($clsISO->checkItemInArray($val[$clsProperty->pkey], $utilites_profile)) {
				$order = array_search($val[$clsProperty->pkey],$utilites_profile);
				$utilities_active[] = [
					"id"	=>	$val[$clsProperty->pkey],
					"title"	=>	$val['title'],
					"link"	=>	$more_information["link"],
					"icon"	=>	$more_information["icon"],
					'textcolor' => $val['textcolor'],
					'bgcolor' => $val['bgcolor'],
					'order'	=>	$order
				];
			}elseif($clsISO->checkItemInArray($val[$clsProperty->pkey],$permiss_ultilities)){
				$arr_utilities_unactive[] = $val[$clsProperty->pkey];
				$order = $order_max;
				++$order_max;
			}
			if($clsISO->checkItemInArray($val[$clsProperty->pkey],$permiss_ultilities)){
				$lst_utilities[] = [
					"id"	=>	$val[$clsProperty->pkey],
					"title"	=>	$val['title'],
					"link"	=>	$more_information["link"],
					"icon"	=>	$more_information["icon"],
					'textcolor' => $val['textcolor'],
					'bgcolor' => $val['bgcolor'],
					'order'	=>	$order
				];
			}
		}
	}
	$menu_active_arrs = @array_column($utilities_active, 'order');
	@array_multisort($menu_active_arrs, SORT_ASC, $utilities_active);
	//	$clsISO->print_pre($lst_utilities);die;
	$smarty->assign('uid', $uid);
	$smarty->assign('lst_utilities', $lst_utilities);
	$smarty->assign('utilities_active', $utilities_active);
	$smarty->assign('utilities_unactive', $arr_utilities_unactive);
	// Return
	$html = $core->build('helper'.DS.'_ajax.open_config_menu.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	),JSON_UNESCAPED_UNICODE); die();
}
function helper_saveMenu(){
	global $oSmarty,$smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID,$profile_id,$oneProfile;
	$clsProfile = new Profile();
	$clsFollowUp = new FollowUp();
	$clsProperty = new Property();
	$uid = $clsISO->getUniqid();
	// Action
	$utilites_default = [8761,8762,8763,8767,8769,11129,11130,11131];
	$utilites_menu = Input::post("utilites_menu",array());
	$utilites_menu = !empty($utilites_menu) ? $utilites_menu : $utilites_default;
	$more_profile = $oneProfile["more_information"];
	$more_profile["utilites_menu"] = $utilites_menu;
	$res = ["result" => false, "msg"	=>	"Lỗi"];
	if($clsProfile->updateOne($profile_id,["more_information" => json_encode($more_profile)])) {
		$res = ["result" => true, "msg"	=>	"Lưu thành công"];
	}
	echo json_encode($res,JSON_UNESCAPED_UNICODE); die();
}
function helper_loadMenu(){
	global $smarty, $core, $clsISO, $_LANG_ID, $profile_id, $oneProfile, $dbconn, $deviceType;
	$clsProfile = new Profile();
	$clsFollowUp = new FollowUp();
	$clsProperty = new Property();
	#
	$maxItem = 12; // Mặc định
	$role_id = $oneProfile['role_id'];
	$department_id = $oneProfile['department_id'];
	$window_w = (int) Input::post('window_w', 1360);
	if($deviceType == 'computer'){
		if($window_w < 1600) $maxItem = 10;
		if($window_w < 1360) $maxItem = 8;
	}
	$arr_utilities = [];
	$more_information = $oneProfile["more_information"];
	$role_permiss = $clsProperty->getArraySearchByKey("_ROLE", $role_id);
	$more_permiss =  $clsISO->to_array_json($role_permiss["more_information"]);
	$permiss_ultilities = $core->get_field($more_permiss, "permiss_ultilities", []);
	if(!empty($permiss_ultilities)) {
		$utilites_default = [];
		for($i=0; $i<12; $i++) {
			$utilites_default[] = $permiss_ultilities[$i];
		}
	}
	$role = $clsProperty->getRoleUtilities($role_id);
	$list_utilities = $clsProperty->getArraySearchByKey("_ULTILITIES");
	$utilites_profile = $core->get_field($more_information, "utilites_menu", $utilites_default);
	if(!empty($utilites_profile)) { $ii = 0;
		foreach($utilites_profile as $id) {
			if(isset($list_utilities[$id])) {
				$oneUtilities = $list_utilities[$id];
				$more_unnity = $oneUtilities['more_information'];
				$arr_role = $core->get_field($more_unnity, "role", []);
				if($ii < $maxItem){
					$total = $clsProperty->getMenuTotal($id);	
					$arr_utilities[] = [
						"id"	=>	$id,
						"title"	=>	$oneUtilities['title'],
						'attr' => $core->get_field($more_unnity, "attr", ""),
						'link' => $core->get_field($more_unnity, "link", ""),
						'icon' => $core->get_field($more_unnity, "icon", ""),
						'badge' => $core->get_field($more_unnity, "badge", ""),
						'bgcolor' => $oneUtilities['bgcolor'],
						'textcolor' => $oneUtilities['textcolor'],
						'total' => $total,
					];
					++$ii;					
				}
			}
		}
	} else {
		$ii= 0;
		foreach($list_utilities as $key => $val) {
			$id = $val[$clsProperty->pkey];
			$more_unnity = $val['more_information'];
			$more_unnity = $clsISO->to_array_json($more_unnity);
			$arr_role = $core->get_field($more_unnity, "role", []);
			if($clsISO->checkItemInArray($role, $arr_role) && $ii < $maxItem){
				$total = $clsProperty->getMenuTotal($id);
				$arr_utilities[] = [
					"id"	=>	$id,
					"title"	=>	$val['title'],
					'attr' => $core->get_field($more_unnity, "attr", ""),
					'link' => $core->get_field($more_unnity, "link", ""),
					'icon' => $core->get_field($more_unnity, "icon", ""),
					'badge' => $core->get_field($more_unnity, "badge", ""),
					'bgcolor' => $val['bgcolor'],
					'textcolor' => $val['textcolor'],
					'total' => $total,
				];
				++$ii;
			}
		}
	}
	$smarty->assign('lstUtilities', $arr_utilities);
	// Return
	$html = $core->build('helper'.DS.'_ajax.loadMenu.tpl');
	echo json_encode(array( 'html' => $html),JSON_UNESCAPED_UNICODE); die();
}
function helper_edit_helper(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$clsISO,$profile_id,$clsConfiguration,$deviceType;
	$clsProfile = new Profile();
	$uid = $clsISO->getUniqid();
	$action = Input::post("action","_OPEN"); 
	$mod_page = Input::post("mod_page",""); 
	$sub_page = Input::post("sub_page",""); 
	$act_page = Input::post("act_page",""); 
	$config_name = sprintf("Helper_%s_%s_%s",$mod_page, $sub_page, $act_page);
	// Return
	if($action == "_OPEN") {		
		$helper_page = $clsConfiguration->getValue($config_name);
		$helper_page = $clsISO->to_array_json($helper_page);
		$smarty->assign('helper_page', $helper_page);
		$smarty->assign('mod_page', $mod_page);
		$smarty->assign('sub_page', $sub_page);
		$smarty->assign('act_page', $act_page);
		$html = $core->build('helper'.DS.'_ajax.open_helper.tpl');		
		Response::echoResponse(200, array(
			'uid' => $uid,
			'result' => true,
			'html' => $html
		)); die();
	}elseif($action == "_EDIT") {
		$helper_page["content"] = $_POST["content"];
		if($clsConfiguration->updateValue($config_name, json_encode($helper_page,JSON_UNESCAPED_UNICODE))){
			$msg = "_success";
		} 		
		Response::echoResponse(200, array(
			'result' => true,
			'msg' => $msg
		)); die();
	}
	Response::echoResponse(200, array(
		'result' => false,
		'msg' => "error"
	)); die();
}
function helper_view_helper(){
	// ini_set('display_errors', '1');
	// ini_set('display_startup_errors', '1');
	// error_reporting(E_ALL);
	global $smarty,$mod,$act,$adminid,$core,$clsISO,$profile_id,$clsConfiguration;
	$mod_page = Input::post("mod_page",""); 
	$sub_page = Input::post("sub_page",""); 
	$act_page = Input::post("act_page",""); 
	$config_name = sprintf("Helper_%s_%s_%s",$mod_page, $sub_page, $act_page);
	$helper_page = $clsConfiguration->getValue($config_name);
	$helper_page = $clsISO->to_array_json($helper_page);
	$smarty->assign('helper_page', $helper_page);
	// Return
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	$html = $core->build('helper'.DS.'_ajax.view_helper.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function helper_load_month(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$oneProfile;
	####
	$year = (int) Input::post('year', date('Y'));
	$quarter = (int) Input::post('quarter', 0);
	$type = Input::post('type', "year");
	if(!empty($type)) {		
		if($quarter == 1) {
			$start_month = 1;
			$end_month = 3;
		}else if($quarter == 2) {
			$start_month = 4;
			$end_month = 6;
		}else if($quarter == 3) {
			$start_month = 7;
			$end_month = 9;
		}else if($quarter == 4) {
			$start_month = 10;
			$end_month = 12;
		}
	}else{
		if($year == 2023){
			$start_month = 5;
			$end_month = 12;
		} else if($year == date('Y')) {
			$start_month = 1;
			$end_month = date('m');
		} else {
			$start_month = 1;
			$end_month = 12;
		}
	}		
	$max_loop = 4; 
	if($year == (int) date('Y')){
		if(date('n') >=1 && date('n') <=3) $max_loop = 1;
		if(date('n') >=4 && date('n') <=6) $max_loop = 2;
		if(date('n') >=7 && date('n') <=9) $max_loop = 3;
		if(date('n') >=10 && date('n') <=12) $max_loop = 4;
	}
	$html_quarter = sprintf('<option value="0">%s</option>', 'Chọn Quý');
	for($i=1; $i<=$max_loop; $i++){
		$selected = ($quarter == $i) ? "selected" : "";
		$html_quarter.= sprintf('<option value="%s" %s>Quý %s</option>', $i, $selected, $i);
	}
	$html_month = sprintf('<option value="0">%s</option>', 'Chọn tháng');
	for($i=$start_month; $i<= $end_month; $i++){
		$selected = ($month == $i) ? "selected" : "";
		$html_month.= sprintf('<option value="%s" %s>Tháng %s</option>', $i, $selected, $i);
	}
	// Return
	echo json_encode(array(
		"html_month"	=>	$html_month,
		"html_quarter"	=>	$html_quarter,
	),JSON_UNESCAPED_UNICODE);die;
}
function helper_load_info_agency(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$clsProfile,$profile_id,$deviceType;
	$clsStock = new Stock();
	$clsPolicy = new Policy();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsLog = new Log();
	$assign_list["clsProject"] = $clsProject;
	$assign_list["clsProperty"] = $clsProperty;
	$dbconn->setFetchMode(ADODB_FETCH_ASSOC);
	###
	$agency_id = (int)Input::get('agency_id', 0);
	$oneAgency = $clsProperty->getOne($agency_id);
	$more_information = $clsISO->to_array_json($oneAgency["more_information"]);
	$info_agency = !empty($more_information["info_agency"]) ? $more_information["info_agency"] : [];
	$branch_office = $project = [];
	if(!empty($info_agency["branch_office"])) {
		$branch_office = $info_agency["branch_office"];
		foreach ($branch_office as $key => $val) {
			if(empty($val)) {
				unset($branch_office[$key]);
			}
		}
	}
	if(!empty($info_agency["project"])) {
		$project = $info_agency["project"];
		foreach ($project as $key => $val) {
			if(empty($val["title"]) && empty($val["project_manager"]) && empty($val["project_manager"])) {
				unset($project[$key]);
			}
		}
	}
	$assign_list["oneAgency"] = $oneAgency;
	$assign_list["info_agency"] = $info_agency;
	$assign_list["branch_office"] = $branch_office;
	$assign_list["project"] = $project;
	$html = $core->build("helper".DS."_ajax.load_info_agency.tpl");
	// Return
	echo $html; die();
}
function helper_loadDataImageCrawl(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$clsProfile,$profile_id,$deviceType;
	###
	$image = Input::post('image', "");
	$tblData = [];
	$gg_id = $clsISO->getGoogleId($image);
	if($image != "" && $gg_id != "") {
		$image_drive = $clsISO->genGoogleURL($gg_id,"preview");
		$tblData = $clsISO->getDataImage('https://n8n.futurehomes.vn/webhook/upload-image_UNC',["image"=>$image_drive]);
	}
	// Return
	echo json_encode($tblData,JSON_UNESCAPED_UNICODE);die;
}
?>