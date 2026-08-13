<?php if (!defined('ABSPATH')) exit('No direct script access allowed');

/*======================================================================*\

|| #################################################################### ||

|| # The Classes configurations of the MaxxCMS                        # ||

|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||

|| # ---------------------------------------------------------------- # ||

|| # All PHP code in this file is ©2014-2015 Future Group.        	  # ||

|| # This file may not be redistributed in whole or significant part. # ||

|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||

|| #################################################################### ||

\*======================================================================*/

function containsKeywords($string, $keywords) {

	global $clsISO;

    // Duyệt qua từng từ khóa trong mảng

    foreach ($keywords as $keyword) {

        // Sử dụng hàm stripos để kiểm tra sự tồn tại của từ khóa trong chuỗi

        if (stripos($string, $keyword) !== false) {

            return true; // Nếu tìm thấy từ khóa, trả về true

			break;

        }

    }

    return false; // Nếu không tìm thấy từ khóa nào, trả về false

}

function default_toggle_agent(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page

	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id,$oneProfile;

	$clsProfile = new Profile();

	###

	$msg = "_error";

	$status = (int) Input::post('status', 0);

	$more_information = $oneProfile['more_information'];

	$more_information['hide_agent'] = ($status == 0 ? 1: 0);

	// $clsISO->print_pre($more_information); die();

	if($clsProfile->updateOne($profile_id, array(

		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)

	))){

		$msg = "_success";

	}

	// Return

	echo $msg; die();

}

function default_default(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,

	$keyword_page,$extLang,$clsISO,$clsProfile,$profile_id,$oneProfile,$clsConfiguration;

	$helper = new Helper();

	$clsCache = new Cache();

	$clsStock = new Stock();

	$clsProperty = new Property();

	$clsProject = new Project();

	$clsProjectMeta = new ProjectMeta();

	$assign_list["clsStock"] = $clsStock;

	$assign_list["clsProject"] = $clsProject;

	$assign_list["clsProperty"] = $clsProperty;

	// $keyword = $_GET['keyword'];

	$keyword = Input::get('keyword', "", false);

	$stock_type = vnSessionExist('_ss_stock_type') ? vnSessionGetVar('_ss_stock_type') : _BLOCK_TYPE_HIGHLEVEL_SALE;

	$more_information = $oneProfile['more_information'];

	$hide_agent = $core->get_field($more_information, "hide_agent", 0);

	$assign_list["hide_agent"] = $hide_agent;

	//$clsISO->print_pre($keyword); die();

	$hide_stock_globe = $clsISO->checkPermission('hide_stock_globe') ? 1 : 0;

	$assign_list["hide_stock_globe"] = $hide_stock_globe;

	// Empty keyword

	if(empty($keyword)){

		$core->redirect('/');

	}

	$replace_arrs = array(

		'hdmb'	=> 'hđmb',

		'HDMB'	=> 'HĐMB',

		'L26X' => 'L26.X',

		'L27X' => 'L27.X',

	);
	$arr_block_cached = $clsProperty->getArraySearchByKey("_BLOCK");

	foreach($replace_arrs as $key => $val){

		$keyword = str_replace($key, $val, $keyword);

	}

	$list_results = $list_stocks = $list_projects = array();

	$arr_cached_project = $arr_cached_property = $arr_cached_query = array();

	if(!empty($keyword)){

		$keyword = trim($keyword);

		$keyword = preg_replace('/(\s+){2,}/', ' ', $keyword);

		if(!preg_match('/([0-9]PN)\s+?([0-9]PN)/',$keyword)){

			$keyword = @preg_replace('/(1PN\s1)/', '1PN+1', $keyword);

			$keyword = @preg_replace('/(2PN\s1)/', '2PN+1', $keyword);

			$keyword = @preg_replace('/(3PN\s1)/', '3PN+1', $keyword);

		}

		$slug = $core->replaceSpace($keyword);

		$slug_arrs = @explode('-', $slug);

		// Tìm căn theo đại lý

		$has_stock = false;

		if(!$clsISO->checkContainer($keyword, '&', '') && $stock_type != _BLOCK_TYPE_LOWFLOOR_SALE){

			$arr_beedroom_ids = array();

			if(preg_match('/([0-9]PN(\+[0-9]){0,}|Studio|ST)/i', $keyword)){

				@preg_match_all('/([0-9]PN(\+[0-9]){0,}|Studio)/i', $keyword, $matches);

				if(!empty($matches[1])){

					foreach($matches[1] as $key){

						$field = "{$clsProperty->pkey}";

						$oProp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_BEDROOM' 

							and `slug`='{$core->replaceSpace($key)}'", $field);

						if(!empty($oProp)) $arr_beedroom_ids[] = $oProp[$clsProperty->pkey];

					}

				}

			}

		}

		$sql_string = "";

		if(count($slug_arrs) > 1){

			$tmp = array();

			foreach($slug_arrs as $keysearch){

				if(strlen($keysearch) >= 2 && !preg_match('/([0-9]PN(\+[0-9]){0,}|Studio|ST)/i', $keysearch)){

					$tmp[] = "(`slug` like '%{$keysearch}%' 

						or `slug_vn` like '%{$keysearch}%' 

						or `property_code` like '%{$keysearch}%'

					)";

				}

			}

			if(!empty($tmp)){

				$sql_string.= " and (".implode(" and ", $tmp).")"; 

			}

		} else {

			$sql_string.= " and (`slug` like '%{$slug}%' or `slug_vn` like '%{$slug}%' or `property_code`='{$keyword}')";

		}

		/** Quỹ căn theo đại lý */

		$is_not_null = false; $arr_hid_blocks = $arr_agency_VIN = array();

		if(!$clsISO->checkPermission('view_stock_hidden') && $stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE){

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

			$list_agency = $clsProperty->getCacheItems("_AGENCY");

			if(!empty($list_agency)){

				foreach($list_agency as $key => $val){

					$agency_id = $val[$clsProperty->pkey];

					$ag_information = $val['more_information'];

					$ag_information = $clsISO->to_array_json($ag_information);

					if(isset($ag_information['FH_stock_vin']) && (int) $ag_information['FH_stock_vin']==1){

						$arr_agency_VIN[] = $agency_id;

					}

					foreach($arr_hid_blocks as $hid_block_id) {

						$hid_field = sprintf('FH_%s_%s', $agency_id, $hid_block_id);

						if(isset($ag_information[$hid_field]) && (int) $ag_information[$hid_field]==1){

							$is_not_null = true;

							${"arr_agency_".$hid_block_id}[] = $agency_id;

						}

					}

				}

			}

			$list_agency_notins = $clsProperty->getAll("`is_trash`=1 and `is_locked`=0 

				and `property_type`='_AGENCY'", $clsProperty->pkey);

			if(!empty($list_agency_notins)){

				foreach($list_agency_notins as $key => $val){

					$arr_agency_VIN[] = $val[$clsProperty->pkey];

					foreach($arr_hid_blocks as $hid_block_id) {

						$is_not_null = true;

						${"arr_agency_".$hid_block_id}[] = $val[$clsProperty->pkey];

					}

				}

				unset($list_agency_notins);

			}

		}

		$list_agent_stocks = $arr_cond_blocks = array();

		if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE){

			$field = "{$clsProperty->pkey},`property_code`,`property_type`,`title`";

			$types_arrs = array('_BLOCK','_BUILDING');

			if($clsISO->checkSupper()){

				$types_arrs = array('_BLOCK','_AGENCY','_BUILDING');

			} 

			$list_agent_stocks = $clsProperty->getAll("`is_trash`=0 AND `parent_id`='{$stock_type}' AND (`property_type` in ('".implode('\',\'',$types_arrs)."') or `{$clsProperty->pkey}`='"._AGENCY_FH_ID."')".$sql_string, $field);

			if(!empty($list_agent_stocks)){

				foreach($list_agent_stocks as $key => $val){

					$field_id = $val[$clsProperty->pkey];

					$property_type = $val['property_type'];

					if($property_type=='_BLOCK') $f_field = "block_id";

					if($property_type=='_AGENCY') $f_field = "agency_id";

					if($property_type=='_BUILDING') $f_field = "building_id";

					$field = "{$clsStock->pkey},ms_code,project_id,block_id,building_id,agency_id

					,status_id,bedroom_id,`home_direction_id`,`more_information`,`show_website`,`floor`";

					$cond_stock = "`is_trash`=0 AND `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' AND (`status_id`>0 and `status_id`<>'"._STOCK_STATUS_SOLD_ID."' AND status_id<>'"._STOCK_STATUS_NON_ID."') AND `{$f_field}`='{$field_id}'";

					if(!$clsISO->checkPermission('hide_stock_globe')){

						$cond_stock.= " AND `show_website` like '%|user.fh|%'";

					}

					// Không hiện thị quỹ ẩn

					if(defined('_STOCK_SEARCH_HIDE_ENABLE') && _STOCK_SEARCH_HIDE_ENABLE==0){

						$cond_stock.= " AND status_id<>'"._STOCK_STATUS_HIDDEN_ID."'";

					}

					if(!empty($arr_beedroom_ids)){

						$cond_stock.= " AND `bedroom_id` in (".implode(',',$arr_beedroom_ids).")";

					}

					if(!$clsISO->checkPermission('view_stock_hidden') && $stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE){

						if(!empty($arr_agency_VIN) || $is_not_null){

							$cond_stock.= " AND (CASE ";

							if(!empty($arr_agency_VIN)){

								$cond_stock.= " WHEN `block_id` NOT IN(".implode(',', $arr_hid_blocks).") 

									THEN `agency_id` NOT IN (".implode(',', $arr_agency_VIN).")";

							}

							if(!empty($arr_hid_blocks)){

								foreach($arr_hid_blocks as $hid_block_id) {

									if(!empty(${"arr_agency_".$hid_block_id})){

										$cond_stock.= " WHEN `block_id`='{$hid_block_id}' THEN 

											`agency_id` NOT IN (".implode(',',${"arr_agency_".$hid_block_id}).")";

									}

								}

							}

							$cond_stock.= " ELSE TRUE END)";

						}

					}

					// $dbconn->debug = true;

					$list_stocks = $clsStock->getAll($cond_stock." order by `total_price_vat` ASC", $field);

					if(!empty($list_stocks)){

						$has_stock = true; $arr_block_cached = array();

						foreach($list_stocks as $okey => $oval){

							$more_information = $oval['more_information'];

							$more_information = $clsISO->to_array_json($more_information);

							$total_price_vat = $more_information['total_price_vat'];

							$total_price_vat = $clsISO->processSmartNumber($total_price_vat);

							$total_price_early = $core->get_field($more_information, 'total_price_early', 0);

							if(empty($total_price_vat) && !empty($total_price_early)){

								$total_price_vat = $clsISO->processSmartNumber($total_price_early);

							}

							$list_stocks[$okey]['total_price_vat'] = $total_price_vat;

							$list_stocks[$okey]['more_information'] = $more_information;

						}

					}

					$order_no_arrs = @array_column($list_stocks, 'total_price_vat');

					@array_multisort($order_no_arrs, SORT_ASC, $list_stocks);

					foreach($list_stocks as $okey => $oval){

						$project_id = $oval['project_id'];

						$stock_id = (int) $oval['stock_id'];

						$block_id = (int) $oval['block_id'];

						$agency_id = (int) $oval['agency_id'];

						$building_id = (int) $oval['building_id'];

						$status_id = (int) $oval['status_id'];

						$bedroom_id = (int) $oval['bedroom_id'];

						$home_direction_id = (int) $oval['home_direction_id'];

						$more_information = $oval['more_information'];

						if(!isset($arr_cached_project[$project_id])){

							$arr_cached_project[$project_id] = $clsProject->getCode($project_id);

						}

						if(!isset($arr_cached_property[$block_id]) && !isset($arr_block_cached[$block_id])){

							$arr_block_cached[$block_id] = $clsProperty->getOne($block_id, "`title`,`more_information`");

							$arr_cached_property[$block_id] = $clsProperty->getTitle($block_id, $arr_block_cached[$block_id]);

						}

						$list_price_configs = array();

						$more_information_block = $arr_block_cached[$block_id]['more_information'];

						$more_information_block = $clsISO->to_array_json($more_information_block);

						$price_field_configs = $core->get_field($more_information_block, 'price_field_configs', []);

						$is_fund_type = $clsStock->checkStockFundType($stock_id, $building_id, $val);

						if(!empty($price_field_configs)){

							foreach($price_field_configs as $nkey => $nval){

								if(isset($nval['status']) && (int) $nval['status'] == 1){

									$price = isset($more_information[$nkey]) ? $more_information[$nkey] : 0;

									if(!empty($price)){

										$list_price_configs[$nkey] = array(

											'title' => $nval['title'],

											'price' => $price,

											'bgcolor' => '#137303'

										);

									}

								}

							}

						}

						$list_stocks[$okey]['list_price_configs'] = $list_price_configs;

						if(!isset($arr_cached_property[$building_id])){

							$arr_cached_property[$building_id] = $clsProperty->getTitle($building_id);

						}

						if(!isset($arr_cached_property[$bedroom_id])){

							$arr_cached_property[$bedroom_id] = $clsProperty->getTitle($bedroom_id);

						}

						if(!isset($arr_cached_query[$home_direction_id])){

							$arr_cached_query[$home_direction_id] = $clsProperty->getTitleQR($home_direction_id);

						}

						if(!isset($arr_cached_query[$agency_id])){

							$arr_cached_query[$agency_id] = $clsProperty->getTitleQR($agency_id);

						}

						if($status_id > 0){

							$status_id = $oval['status_id'];

							$oneStatus= $clsProperty->getOne($status_id,"title,bgcolor,textcolor");

							$status_name = $clsProperty->getTitle($status_id, $oneStatus);

						} else{

							$status_name = "Đã bán";

							$oneStatus= $clsProperty->getOne(_STOCK_STATUS_SOLD_ID,"bgcolor,textcolor");

						}

						$list_stocks[$okey]['is_fund_type'] = $is_fund_type;

						$list_stocks[$okey]['status_name'] = $status_name;

						$list_stocks[$okey]['oneStatus'] = $oneStatus;

					}

					$list_agent_stocks[$key]['list_stocks'] = $list_stocks;

				}

			}

		} else if($stock_type != 1){

			$sql_string.= " and (`slug` like '%{$slug}%'

				or `slug` like '%".$core->replaceSpace(preg_replace('/\s+/','',$keyword))."%' 

				or `slug_vn` like '%{$slug}%'

				or `slug_vn` like '%".$core->replaceSpace(preg_replace('/\s+/','',$keyword))."%' 

				or `property_code`='{$keyword}' 

				or LOWER(`property_code`)='".$core->replaceSpace(preg_replace('/\s+/','',$keyword))."'

			)";

			$types_arrs = array('_BLOCK','_BUILDING');

			$field = "{$clsProperty->pkey},`property_type`,`title`";

			$list_props_stocks = $clsProperty->getAll("`property_type` in ('".implode('\',\'',$types_arrs)."') AND `parent_id`='{$stock_type}'".$sql_string, $field);

			if(!empty($list_props_stocks)){

				foreach($list_props_stocks as $key => $val){

					if($val['property_type'] == '_BLOCK'){

						$arr_cond_blocks[] = "`block_id`='{$val[$clsProperty->pkey]}'";

					}

				}

			}

		}

		$assign_list["list_agent_stocks"] = $list_agent_stocks;

		// Tìm kiếm căn hộ

		$list_stocks = array();

		if(!$has_stock){

			$cnd = "`is_trash`=0 AND `stock_type`='{$stock_type}'"; 

			if(!$clsISO->checkPermission('view_stock_globe')){

				$cnd.= " and `show_website` like '%|user.fh|%'";

			}

			$field = "{$clsStock->pkey},`stock_type`,`ms_code`,`floor`,`project_id`,`block_id`,`show_website`

			,`building_id`,`agency_id`,`status_id`,`bedroom_id`,`home_direction_id`,`more_information`";

			if(!$clsISO->checkPermission('view_stock_hidden') && $stock_type== _BLOCK_TYPE_HIGHLEVEL_SALE){

				if(!empty($arr_agency_VIN) || $is_not_null){

					$cnd.= " AND (CASE ";

					if(!empty($arr_agency_VIN)){

						$cnd.= " WHEN `block_id` NOT IN(".implode(',', $arr_hid_blocks).") THEN `agency_id` NOT IN (".implode(',', $arr_agency_VIN).")";

					}

					if(!empty($arr_hid_blocks)){

						foreach($arr_hid_blocks as $hid_block_id) {

							if(!empty(${"arr_agency_".$hid_block_id})){

								$cnd.= " WHEN `block_id`='{$hid_block_id}' THEN `agency_id` NOT IN (".implode(',', ${"arr_agency_".$hid_block_id}).")";

							}

						}

					}

					$cnd.= " ELSE TRUE END)";

				}

			}

			if(defined('_STOCK_SEARCH_HIDE_ENABLE') && _STOCK_SEARCH_HIDE_ENABLE==0){

				$cnd.= " AND (`status_id`>0 AND `status_id`<>'"._STOCK_STATUS_NON_ID."' 

					AND `status_id`<>'"._STOCK_STATUS_HIDDEN_ID."')";

			} else {

				$cnd.= " AND (`status_id`>0 AND `status_id`<>'"._STOCK_STATUS_NON_ID."')";

			}

			// $clsISO->print_pre($cnd); die();

			$order_field = "total_price_vat";

			$keysearch = @mb_strtoupper($keyword);

			$stock_code = preg_replace('/\s+/', '', $keysearch);

			$stock_code = trim(str_replace('.','', $stock_code));

			$stock_code = str_replace("-","",$stock_code);

			$stock_code = strtoupper($stock_code);

			if($clsISO->checkContainer($keysearch,'X','')){

				$limitCond = "";

				$viTriCuaXX = $helper->viTriCuaXX($keysearch);

				if($viTriCuaXX == '_last'){

					$order_field = "code";

				} else {

					$order_field = "floor";

				}

				if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE){

					$key2search = str_replace('X', 'XX', $keysearch);

					$cnd.= " AND (`ms_code` like '%".str_replace('X','_',$keysearch)."' 

						or `ms_code` like '%".str_replace('X','_',$key2search)."' OR REPLACE(REPLACE(`ms_code`,'-',''),'.','')='{$stock_code}')";

				} else {

					$key2search = @str_replace('XX', 'XXX', $keysearch);

					$cnd.= " AND (`ms_code` like '%".str_replace('X','_',$keysearch)."' 

						or `ms_code` like '%".str_replace('X', "_", $key2search)."%' OR REPLACE(REPLACE(`ms_code`,'-',''),'.','')='{$stock_code}')";

				}

			} else {

				$limitCond = " limit 0,32";

				if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE && !empty($arr_cond_blocks)){

					$cnd.= " AND (`ms_code` like '%{$keysearch}%' or ".implode(" or ", $arr_cond_blocks)." OR REPLACE(REPLACE(`ms_code`,'-',''),'.','')='{$stock_code}')";

				} else {

					$cnd.= " AND (`ms_code` like '%{$keysearch}%' OR REPLACE(REPLACE(`ms_code`,'-',''),'.','')='{$stock_code}')";

				}

			}

			// $clsISO->print_pre($cnd); die();

			$list_stocks = $clsStock->getAll("{$cnd} ORDER BY `status_id` DESC,`{$order_field}` ASC".$limitCond, $field);

			if(!empty($list_stocks)){

				$arr_block_cached = array();

				if(!empty($list_stocks)){

					$has_stock = true;

					foreach($list_stocks as $key => $val){

						$more_information = $val['more_information'];

						$more_information = $clsISO->to_array_json($more_information);

						$list_stocks[$key]['more_information'] = $more_information;

						$total_price_vat = $more_information['total_price_vat'];

						$total_price_vat = $clsISO->processSmartNumber($total_price_vat);

						$list_stocks[$key]['total_price_vat'] = $total_price_vat;

					}

				}

				foreach($list_stocks as $key => $val){

					$project_id = $val['project_id'];

					$block_id = $val['block_id'];

					$status_id = $val['status_id'];

					$agency_id = $val['agency_id'];

					$building_id = $val['building_id'];

					$bedroom_id = $val['bedroom_id'];

					$home_direction_id = $val['home_direction_id'];

					$more_information = $val['more_information'];

					if(!isset($arr_cached_project[$project_id])){

						$arr_cached_project[$project_id] = $clsProject->getCode($project_id);

					}

					if(!isset($arr_block_cached[$block_id]) && !isset($arr_cached_property[$block_id])){

						$arr_block_cached[$block_id] = $clsProperty->getOne($block_id, "`title`,`more_information`");

						$arr_cached_property[$block_id] = $clsProperty->getTitle($block_id, $arr_block_cached[$block_id]);

					}

					$list_price_configs = array();

					$more_information_block = $arr_block_cached[$block_id]['more_information'];

					$more_information_block = $clsISO->to_array_json($more_information_block);

					$price_field_configs = $core->get_field($more_information_block, 'price_field_configs', []);

					$is_fund_type = $clsStock->checkStockFundType($stock_id, $building_id, $val);

					if(!empty($price_field_configs)){

						foreach($price_field_configs as $nkey => $nval){

							if(isset($nval['status']) && (int) $nval['status'] == 1){

								if($is_fund_type == 1){

									if(in_array($nkey, array('total_price_early', 'total_price_bank'))){

										$price = isset($more_information[$nkey]) ? $more_information[$nkey] : 0;

										if(!empty($price)){

											$list_price_configs[$nkey] = array(

												'title' => $nval['title'],

												'price' => $price,

												'bgcolor' => '#137303'

											);

										}

									}

								} else {

									$price = isset($more_information[$nkey]) ? $more_information[$nkey] : 0;

									if(!empty($price)){

										$list_price_configs[$nkey] = array(

											'title' => $nval['title'],

											'price' => $price,

											'bgcolor' => '#137303'

										);

									}

								}

							}

						}

					}

					$list_stocks[$key]['is_fund_type'] = $is_fund_type;

					$list_stocks[$key]['list_price_configs'] = $list_price_configs;

					###

					if(!isset($arr_cached_property[$building_id])){

						$arr_cached_property[$building_id] = $clsProperty->getTitle($building_id);

					}

					if(!isset($arr_cached_property[$bedroom_id])){

						$arr_cached_property[$bedroom_id] = $clsProperty->getTitle($bedroom_id);

					}

					if(!isset($arr_cached_query[$home_direction_id])){

						$arr_cached_query[$home_direction_id] = $clsProperty->getTitleQR($home_direction_id);

					}

					if(!isset($arr_cached_query[$agency_id])){

						$arr_cached_query[$agency_id] = $clsProperty->getTitleQR($agency_id);

					}

					if($status_id > 0){

						$status_id = $val['status_id'];

						$oneStatus= $clsProperty->getOne($status_id,"title,bgcolor,textcolor");

						$status_name = $clsProperty->getTitle($status_id, $oneStatus);

					} else{

						$status_name = "Đã bán";

						$oneStatus= $clsProperty->getOne(_STOCK_STATUS_SOLD_ID,"bgcolor,textcolor");

					}

					$list_stocks[$key]['status_name'] = $status_name;

					$list_stocks[$key]['oneStatus'] = $oneStatus;

				}

			}

		}

		$assign_list["lst_stocks"] = $list_stocks; 

		$start = 0; $field = "{$clsProject->pkey},`title`,`intro`,`upd_date`";

		$tmp = $clsProject->getAll("`is_trash`=0 and `is_menu`=1 and (`slug` like '%{$slug}%' OR `code` like '%{$slug}%')", $field);

		if(!empty($tmp)){

			foreach($tmp as $key => $val){

				$project_id = $val[$clsProject->pkey];

				$list_projects[$start] = array(

					'title' => $val['title'],

					'intro' => $val['intro'],

					'reg_date_f' => $clsISO->getTimeAgo($val['upd_date']),

					'link' => $clsProject->getLinkDetail($val[$clsProject->pkey],0,0)

				);

				$list_props = $clsProjectMeta->getAll("`type`='project' and ".$clsProjectMeta->condByProject($project_id)."

				and `is_hot`='1' order by `order_no` ASC", "title,content");

				if(!empty($list_props)){

					foreach($list_props as $okey => $oval){

						$is_driver = $clsISO->checkContainer($oval['content'],"drive.google.com","") ? 1 : 0;

						$list_props[$okey]['is_driver'] = $is_driver;

						$list_props[$okey]['link'] = $clsISO->getIframeUrl($oval['content']);

					}

				}

				$list_projects[$start]['list_props'] = $list_props;

				++$start;

			}

			unset($tmp);

		}

		$field = "{$clsProperty->pkey},`property_type`,`title`,`intro`,`for_id`,`more_information`";

		$cond_block_building = "";

		if($stock_type != 1) {

			$cond_block_building = " AND `parent_id`='{$stock_type}'";

		}

//		$clsProperty->setDeBug(1);

		$tmp = $clsProperty->getAll("`is_trash`=0 and (`property_type`='_BLOCK' or `property_type`='_BUILDING') ".$cond_block_building." and IF(property_type='_BUILDING',`for_id` in (select {$clsProperty->pkey} from {$clsProperty->tbl} as `t1` inner join {$clsProject->tbl} as `t2` on `t1`.`for_id`=`t2`.`project_id` where `t1`.`property_type`='_BLOCK' and `t2`.`is_menu`=1),for_id in (select {$clsProject->pkey} from {$clsProject->tbl} where `is_trash`=0 and `is_menu`=1)) and `slug` like '%{$slug}%'");

//		$clsISO->print_pre($tmp);die;
		if(!empty($tmp)){

			foreach($tmp as $key => $val){

				$for_id = $val['for_id'];

				$obj_id = $val[$clsProperty->pkey];

				$property_type = $val['property_type'];

                $more_information = $val['more_information'];

                $more_information = $clsISO->to_array_json($more_information);

                $list_attrs = $core->get_field($more_information, 'attrs', []);

				$cond = "`is_hot`='1'";

				if($property_type=='_BLOCK'){

					$type = 'block';

					$prefix = 'Phân khu';

					$link = $clsProject->getLinkDetail($for_id, $val[$clsProperty->pkey],0);

					$cond .= " AND `type`='block' AND `block_ids` LIKE '%|{$obj_id}|%'";

				} else if($property_type=='_BUILDING') {

					$type = 'building';

					$prefix = 'Tòa nhà';
					$proj_id = $arr_block_cached[$for_id]["for_id"];

					$link = $clsProject->getLinkDetail($proj_id, $for_id, $val[$clsProperty->pkey]);

					$cond .= " AND `type`='building' AND `building_ids` LIKE '%|{$obj_id}|%'";

				}

				$list_props = $clsProjectMeta->getAll($cond." order by `order_no` ASC", "title,content");

				if(!empty($list_props)){

					foreach($list_props as $nkey => $nval){

						$is_driver = $clsISO->checkContainer($nval['content'],"drive.google.com","") ? 1 : 0;

						$list_props[$nkey]['is_driver'] = $is_driver;

						$list_props[$nkey]['link'] = $clsISO->getIframeUrl($nval['content']);

					}

				}

				$list_projects[$start] = array(

					'title' => sprintf('%s %s', $prefix, $val['title']),

					'intro' => $val['intro'],

					'reg_date_f' => $clsISO->getTimeAgo($val['upd_date']),

					'link' => $link

				);

                $list_projects[$start]['list_attrs'] = $list_attrs;

				$list_projects[$start]['list_props'] = $list_props;

				++$start;

			}

		}

		$assign_list["lst_projects"] = $list_projects;

		$cond = "(`title` like '%{$keyword}%' or `slug` like '%{$slug}%' or `slug` like '%".str_replace('-','%',$slug)."%')";

		if($slug_arrs){

			$cond.= " OR (";

			foreach($slug_arrs as $key => $tag){

				$cond.= (($key > 0) ? " AND " : "") . " `tags_slug` like '%{$tag}%'";

			}

			$cond.= " )";

		}

		if($stock_type == 1) {

			$lst_results = $clsProjectMeta->getAll("({$cond}) order by `upd_date` DESC");

			$arr_block_cache = $clsProperty->getArraySearchByKey("_BLOCK");

			$arr_building_cache = $clsProperty->getArraySearchByKey("_BUILDING");

			$arr_project_cache = [];

			$lst_results = $clsProjectMeta->getListResult($lst_results);

		}

		if(!empty($lst_results)){

			foreach($lst_results as $key => $val){

				$building_ids = $clsISO->getArrayByTextSlash($val['building_ids']);

				$block_ids = $clsISO->getArrayByTextSlash($val['block_ids']);

				$project_id = $val["project_id"];

				$html_info = "";

				if(!empty($building_ids)) {

					foreach ($building_ids as $building_id) {

						$oBuilding = $arr_building_cache[$building_id];

						$oBlock = $arr_block_cache[$arr_building_cache[$building_id]["for_id"]];

						if(!isset($arr_project_cache[$oBlock["for_id"]])) {

							$arr_project_cache[$oBlock["for_id"]] = $clsProject->getOne($oBlock["for_id"],$clsProject->pkey.",title,code");

						}

						$oProject = $arr_project_cache[$oBlock["for_id"]];

						$html_info .= (!empty($html_info) ? ' | ' : '') . sprintf('<a href="%s" class="link_project_detail"><i class="bx bx-building-house mr-1 fs-12"></i>Tòa %s, %s(%s)</a>',$clsProject->getLinkDetail($project_id,$block_id,$building_id,$oProject),$oBuilding["property_code"],$oBlock["property_code"],$oProject["code"]) ;

					}

				}else if(!empty($block_ids)) {

					foreach ($block_ids as $block_id) {

						$oBlock = $arr_block_cache[$block_id];

						if(!isset($arr_project_cache[$oBlock["for_id"]])) {

							$arr_project_cache[$oBlock["for_id"]] = $clsProject->getOne($oBlock["for_id"],$clsProject->pkey.",title,code");

						}

						$oProject = $arr_project_cache[$oBlock["for_id"]];

						$html_info .= (!empty($html_info) ? ' | ' : '') . sprintf('<a href="%s" class="link_project_detail"><i class="bx bx-building-house mr-1 fs-12"></i>%s(%s)</a>',$clsProject->getLinkDetail($project_id,$block_id,0,$oProject),$oBlock["title"],$oProject["code"]) ;

					}

				}else if(!empty($project_id)) {

					if(!isset($arr_project_cache[$project_id])) {

						$arr_project_cache[$project_id] = $clsProject->getOne($project_id,$clsProject->pkey.",title,code");

					}

					$oProject = $arr_project_cache[$project_id];

					$html_info .= (!empty($html_info) ? ' | ' : '') . sprintf('<a href="%s" class="link_project_detail"><i class="bx bx-building-house mr-1 fs-12"></i>%s</a>',$clsProject->getLinkDetail($project_id,0,0,$oProject),$oProject["code"]) ;

				}

				$lst_results[$key]['html_info'] = $html_info;				

			}

		}

	}

	$assign_list["keyword"] = $keyword;

	$assign_list["lst_results"] = $lst_results;

	$assign_list["arr_cached_project"] = $arr_cached_project;

	$assign_list["arr_cached_property"] = $arr_cached_property;

	$assign_list["arr_cached_query"] = $arr_cached_query;

	/*=============Title & Description Page==================*/

	$title_page = $keyword.' - Kết quả tìm kiếm từ khoá - ' . PAGE_NAME;

	$assign_list["title_page"] = $title_page;

	$description_page = $title_page;

	$assign_list["description_page"] = $description_page;

	$keyword_page = $description_page;

	$assign_list["keyword_page"] = $keyword_page;

}