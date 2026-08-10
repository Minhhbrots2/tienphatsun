<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
function lowfloor_crawl_lowfloor(){
	global $assign_list, $_CONFIG, $_SITE_ROOT, $mod, $_LANG_ID, $act, $menu_current, $current_page, $oneSetting, $clsConfiguration;
	global $core, $clsModule, $clsButtonNav, $clsISO, $title_page, $description_page, $oneProfile, $profile_id, $dbconn;
     
	$clsProject   = new Project();
	$clsProperty  = new Property();
	$clsLogCrawl  = new LogCrawl();
	$clsCrawl     = new Crawl();
	$clsStock     = new Stock();
	$clsStockMeta = new StockMeta();
	$clsProfile   = new Profile();
	$clsUser      = new User();
    
	$assign_list['clsProperty'] = $clsProperty;
	$stock_type = _BLOCK_TYPE_LOWFLOOR_SALE; 
	$assign_list['stock_type']  = $stock_type;
	
	// ==========================================
	// 1. Check Hash Thay Đổi Log (Tối ưu Break)
	// ==========================================
	$clsStockLog = new StockLog();
	$start_time  = strtotime(date("d-m-Y 00:00:00", strtotime("-2 weeks"))); // Thấp tầng dùng -2 weeks
	$end_time    = strtotime(date("d-m-Y 23:59:59"));
    
	$lstStockLog = $clsStockLog->getAll("`stock_type`='".$stock_type."' AND `reg_date` BETWEEN {$start_time} AND {$end_time}");
	
	$arrCheckLowFloor = [];
	if (!empty($lstStockLog)) {
		$arr_logLowFloor = [];
		foreach ($lstStockLog as $val) {
			$more_info = $clsISO->to_array_json($val['more_information'] ?? '');
			$arr_logLowFloor[$val["agency_id"]][$val["project_id"]][] = $more_info;
		}

		foreach ($arr_logLowFloor as $agency_id => $logProject) {
			foreach ($logProject as $project_id => $logs) {
				$in_use = 1;
				$count_logs = count($logs);
				if($count_logs > 1) {
					$in_use = 0;
					for($i = 1; $i < $count_logs; $i++) {
						if($clsISO->hashArray($logs[$i]) != $clsISO->hashArray($logs[$i-1])) {
							$in_use = 1;
							break; // Dừng ngay khi tìm thấy sự khác biệt
						}
					}
				}
				$arrCheckLowFloor[$agency_id][$project_id] = $in_use;
			}		
		}
	}
	
	// ==========================================
	// 2. Lấy dữ liệu Agency, Stock, và Project
	// ==========================================
	$more_information_user = $oneProfile['more_information'] ?? '';
    
	$list_agency = $clsProperty->getAll("`is_trash`=0 AND `is_locked`=0 AND `property_id`<>'"._AGENCY_FH_ID."' AND `property_type`='_AGENCY' ORDER BY `order_no` ASC", "{$clsProperty->pkey},title,more_information");
	
	// Tổng quỹ căn đại lý theo dự án
	$lstTotal_stock_project = $clsStock->getAll("`stock_type`='".$stock_type."' AND `status_id`>0 AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."' GROUP BY `project_id`,`agency_id`", "COUNT(`stock_id`) as `total_stock`, `project_id`, `agency_id`");
	$arr_project_stock = [];
	if(!empty($lstTotal_stock_project)) {
		foreach ($lstTotal_stock_project as $val) {
			$arr_project_stock[$val["agency_id"]][$val['project_id']] = $val['total_stock'];
		}
	}
	
	$cond_project = "`is_menu`='1' AND `list_block_type` LIKE '%|".$stock_type."|%'";
	if(!$clsISO->checkSupper() && $profile_id != 289) {
		$cond_project .= " AND JSON_SEARCH(JSON_EXTRACT(`more_information`,'$.list_admin_id.\"".$stock_type."\"'), 'one', {$profile_id}) IS NOT NULL";
	}
	
	$lst_project = [];
	$arr_project_id = [];
	$list_projects = $clsProject->getAll($cond_project." ORDER BY `reg_date` ASC", "{$clsProject->pkey},`code`,`title`,`more_information`");
	
	if (!empty($list_projects)) {
		foreach($list_projects as $val) {
			$more_info = $clsISO->to_array_json($val['more_information'] ?? '');
			$lst_project[$val[$clsProject->pkey]] = [
				"project_code"	=> $val["code"],
				"spreadsheetId"	=> $more_info["spreadsheetId"] ?? "",
			]; 
			$arr_project_id[] = $val[$clsProject->pkey];
		}
	}

    // ==========================================
    // 3. BATCH QUERY LOG CRAWL LÊN RAM (GIẢI QUYẾT N+1)
    // ==========================================
    $one_week_ago = strtotime('-1 weeks');
    $all_logs_db = $clsLogCrawl->getAll("`stock_type` = '".$stock_type."' AND `date` >= {$one_week_ago} ORDER BY `date` ASC", "agency_id, date, description");
    
    $mapped_logs = [];
    if (!empty($all_logs_db)) {
        foreach ($all_logs_db as $log) {
            $agency_id_log = $log['agency_id'];
            $desc = $clsISO->to_array_json($log['description'] ?? '');
            
            if (!empty($desc['logs']) && is_array($desc['logs'])) {
                foreach ($desc['logs'] as $proj_id => $log_array) {
                    if (is_array($log_array) && !empty($log_array)) {
                        // Lấy log cuối cùng trong mảng giống hàm end()
                        $proj_log = end($log_array);
                        
                        $mapped_logs[$agency_id_log][$proj_id] = [
                            'time'        => $proj_log['time'] ?? $log['date'],
                            'result_type' => $proj_log['result_type'] ?? '',
                            '_from_site'  => $proj_log['_from_site'] ?? '',
                            'user_id'     => $proj_log['user_id'] ?? 0
                        ];
                    }
                }
            }
        }
    }

	// ==========================================
	// 4. MAP DATA VÀ XỬ LÝ GIAO DIỆN
	// ==========================================
	$arr_agency_success = [];
	$assign_list["arr_agency_success"] = $arr_agency_success;
	$arr_cache_user = [];
    
	$totalStock = 0; 
    $arr_total_project = [];

	foreach ($list_agency as $key => $val) {
		$agency_id = $val["property_id"];
		$more_info = $clsISO->to_array_json($val['more_information'] ?? '');		
		
		$project_not_upd = $more_info["project_not_upd"] ?? [];
		if($clsCrawl->isSubset_diff($arr_project_id, $project_not_upd)) {
			unset($list_agency[$key]);
			continue;
		}
		
		$crawl_lowfloor = $more_info['crawl_lowfloor'] ?? [];		
		$list_agency[$key]['more_information'] = $more_info;
		$list_agency[$key]['spreadsheetId']    = $more_info["spreadsheetId"] ?? '';
		
		$total_dq = 0;
        $is_crawl = 0;
		
		foreach($lst_project as $project_id => $oProject) {
            
            // Lấy log từ mảng RAM, không query DB
            $log = $mapped_logs[$agency_id][$project_id] ?? [];
            
			$total_stock = $arr_project_stock[$agency_id][$project_id] ?? 0;
			
			$crawl_lowfloor[$project_id]["time"] = !empty($log) ? $clsLogCrawl->getTimeAgo($log["time"]) : "--";
			$crawl_lowfloor[$project_id]["total_stock"] = $total_stock;
			
			$arr_total_project[$project_id] = ($arr_total_project[$project_id] ?? 0) + $total_stock;
			
			if(!empty($log)){
				$is_success = ($log["result_type"] == "update");
				$crawl_lowfloor[$project_id]["html_result"] = $is_success ? "<span class='text-success'>Thành công</span>" : "<span class='text-danger'>Thất bại</span>";
				$crawl_lowfloor[$project_id]["is_success"]  = $is_success ? 1 : 0;
			}
            
			$total_dq   += $total_stock;
			$totalStock += $total_stock;
			$crawl_lowfloor[$project_id]["link_stock"] = $clsProject->getLink($project_id)."?agency_ids=".$agency_id;
			
			if(!empty($crawl_lowfloor[$project_id]['is_crawl'])){
				$is_crawl = 1;
			}
			
            // Xử lý Profile User
			$pro_id = 0;
			if(!empty($log)) {
				if($log["_from_site"] == "_admin") {
                    $uid = $log["user_id"];
					if(!isset($arr_cache_user['uid_'.$uid])) {
                        $oneUser = $clsUser->getOne($uid, "more_information");
                        $more_user = $clsISO->to_array_json($oneUser["more_information"] ?? '');
						$arr_cache_user['uid_'.$uid] = $more_user["staff_permiss_id"] ?? 0;
					}
					$pro_id = $arr_cache_user['uid_'.$uid];
				} else {
					$pro_id = $log["user_id"] ?? 0;
				}
				
				if($pro_id && !isset($arr_cache_user['pro_'.$pro_id])) {
					$arr_cache_user['pro_'.$pro_id] = $clsProfile->getLastName($pro_id, 0, 0);
				}
				
				if($pro_id) {
					$crawl_lowfloor[$project_id]["user_upd"] = '<span class="cursor-pointer" data-url="/index.php?mod=home&act=load_profile_popover&user_id='.$pro_id.'" data-toggle="webui-popover" data-trigger="hover" data-width="350">'.($arr_cache_user['pro_'.$pro_id] ?? '').'</span>';
				}
			}

			$crawl_lowfloor[$project_id]["is_changed"] = (isset($arrCheckLowFloor[$agency_id][$project_id]) && $arrCheckLowFloor[$agency_id][$project_id] == 1) ? 0 : 1;
		}
		
		$list_agency[$key]['crawl_lowfloor'] = $crawl_lowfloor;
		$list_agency[$key]['total_stock']    = $total_dq;		
		$list_agency[$key]["order_no"]       = $is_crawl ? 1 : 2;
	}

	// ==========================================
	// 5. Output
	// ==========================================
	$total_sales_arrs = array_column($list_agency, 'order_no');
	array_multisort($total_sales_arrs, SORT_ASC, $list_agency);
    
	$assign_list["lst_project"]       = $lst_project;
	$assign_list["list_agency"]       = $list_agency;
	$assign_list["arr_total_project"] = $arr_total_project;
	$assign_list["totalStock"]        = $totalStock;
	
	/*============= Title & Description Page ==================*/
	$assign_list["title_page"]       = $title_page       = 'Cập nhật bảng hàng Excel thấp tầng - '.PAGE_NAME;
	$assign_list["description_page"] = $description_page = ' Cập nhật bảng hàng Excel thấp tầng - '.PAGE_NAME;
}
function lowfloor_crawl_lowfloorOld(){
	// ini_set('display_errors', '1');
	// ini_set('display_startup_errors', '1');
	// error_reporting(E_ALL);
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$title_page,$description_page,$oneProfile,$profile_id,$dbconn;
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsLogCrawl = new LogCrawl();
	$clsCrawl = new Crawl();
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	$clsProfile = new Profile();
	$clsUser = new User();
	$assign_list['clsProperty'] = $clsProperty;
	$stock_type = _BLOCK_TYPE_LOWFLOOR_SALE; 
	$assign_list['stock_type'] = $stock_type;
	//	$clsISO->print_pre($oneProfile);die;
	
	###
	$clsStockLog = new StockLog();
	$start_time = strtotime("-2 weeks");
	$start_time = strtotime(date("d-m-Y 00:00:00",$start_time));
	$end_time = strtotime(date("d-m-Y 23:59:59"));
	$lstStockLog = $clsStockLog->getAll("`stock_type`='".$stock_type."' AND `reg_date` BETWEEN {$start_time} AND {$end_time}");
	$arr_logHighFloor = $arr_logLowFloor = [];
	foreach ($lstStockLog as $key => $val) {
		$more_information = $clsISO->to_array_json($val['more_information']);
		$arr_logLowFloor[$val["agency_id"]][$val["project_id"]][] = $more_information;
	}
//	$clsISO->print_pre($arr_logLowFloor);die;
	$arrCheckLowFloor = [];
	foreach ($arr_logLowFloor as $agency_id => $logProject) {
		foreach ($logProject as $project_id => $logs) {
			$flag = [];
			$in_use = 0;
			if(count($logs) > 1) {
				for($i=0; $i < count($logs); $i++) {
					$flag = $logs[$i];
					if($i > 0 && $clsISO->hashArray($flag) != $clsISO->hashArray($logs[$i-1])) {
						$in_use = 1;
					}
				}
			}else{
				$in_use = 1;
			}
			$arrCheckLowFloor[$agency_id][$project_id] = $in_use;
		}		
	}
	
	$project_permiss = array();
	$more_information_user = $oneProfile['more_information'];
	$list_agency = $clsProperty->getAll("`is_trash`=0 AND `is_locked`=0 AND `property_id`<>'"._AGENCY_FH_ID."' 
	AND `property_type`='_AGENCY'  order by `order_no` ASC", "{$clsProperty->pkey},title,more_information");
	# tổng quỹ căn đại lý theo phân khu
	$lstTotal_stock_project_agency = $clsStock->getAll("`stock_type`='".$stock_type."' AND `status_id`>0 AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."' GROUP BY `project_id`,`agency_id` ","COUNT(`stock_id`) as `total_stock`, `project_id`, `agency_id`");
	$arr_project_stock = [];
	if(!empty($lstTotal_stock_project_agency)) {
		foreach ($lstTotal_stock_project_agency as $key => $val) {
			$arr_project_stock[$val["agency_id"]][$val['project_id']] = $val['total_stock'];
		}
	}
	
	$totalStock = 0; $arr_total_project = [];
	$field = "{$clsProject->pkey},`code`,`title`,`more_information`";
	$cond_project = "`is_menu`='1' AND `list_block_type` LIKE '%|".$stock_type."|%'";
	if(!$clsISO->checkSupper() && $profile_id != 289) {
		$cond_project.= " AND JSON_SEARCH(JSON_EXTRACT(`more_information`,'$.list_admin_id.\"".$stock_type."\"'), 'one', {$profile_id}) IS NOT NULL";
	}
	$lst_project = $arr_project_id  = array();
	$list_projects = $clsProject->getAll($cond_project." order by `reg_date` ASC", $field);
	foreach($list_projects as $key => $val) {
		$more_information = $clsISO->to_array_json($val['more_information']);
		$lst_project[$val[$clsProject->pkey]] = [
			"project_code"	=>	$val["code"],
			"spreadsheetId"	=>	!empty($more_information["spreadsheetId"]) ? $more_information["spreadsheetId"] : "",
		]; 
		$arr_project_id[] = $val[$clsProject->pkey];
	}
	$arr_agency_success = [];
	$assign_list["arr_agency_success"] = $arr_agency_success;
	$arr_cache_user = $arr_cache_user_admin = array();
	foreach ($list_agency as $key => $val) {
		$agency_id = $val["property_id"];
		$more_information = $clsISO->to_array_json($val['more_information']);		
		$project_not_upd = !empty($more_information["project_not_upd"]) ? $more_information["project_not_upd"] : array();
		if($clsCrawl->isSubset_diff($arr_project_id,$project_not_upd)) {
			unset($list_agency[$key]);
			continue;
		}
		$crawl_lowfloor = !empty($more_information['crawl_lowfloor']) ? $more_information['crawl_lowfloor'] : array();		
		$list_agency[$key]['more_information'] = $more_information;
		$list_agency[$key]['spreadsheetId'] = $more_information["spreadsheetId"];
		$total_dq = $is_crawl = 0;
		foreach($lst_project as $project_id=>$oProject) {
			$list_logs_json = $clsLogCrawl->getByCond("agency_id = '{$agency_id}'
					  AND stock_type = '".$stock_type."'
					  AND `date` >= '".strtotime('-1 weeks')."' 
					  AND JSON_CONTAINS_PATH(description,'one', CONCAT('$.logs.\"', ".$project_id.", '\"'))
					ORDER BY `date` DESC","JSON_EXTRACT(description, CONCAT('$.logs.\"', ".$project_id.", '\"')) AS log_array");
			$log_array = !empty($list_logs_json["log_array"]) ? $clsISO->to_array_json($list_logs_json["log_array"]) : array();
			$log = !empty($log_array) ? end($log_array) : array();
			$total_stock = !empty($arr_project_stock[$agency_id][$project_id]) ? $arr_project_stock[$agency_id][$project_id] : 0;
			$crawl_lowfloor[$project_id]["time"] = !empty($log) ? $clsLogCrawl->getTimeAgo($log["time"]) : "--";
			$crawl_lowfloor[$project_id]["total_stock"] = $total_stock;
			if(isset($arr_total_project[$project_id])) {
				$arr_total_project[$project_id] += $total_stock;
			}else{
				$arr_total_project[$project_id] = $total_stock;
			}
			if(!empty($log)){
				$crawl_lowfloor[$project_id]["html_result"] = ($log["result_type"]=="update")?"<span class='text-success'>Thành công</span>" : "<span class='text-danger'>Thất bại</span>";
				$crawl_lowfloor[$project_id]["is_success"] = ($log["result_type"]=="update")? 1 : 0;
			}				
			$total_dq += $total_stock;
			$totalStock += $total_stock;
			$crawl_lowfloor[$project_id]["link_stock"] = $clsProject->getLink($project_id)."?agency_ids=".$agency_id;
			if(!empty($crawl_lowfloor[$project_id]['is_crawl'])){
				$is_crawl = 1;
			}
			if($log["_from_site"] == "_admin") {
				if(!isset($arr_cache_user_admin[$log["user_id"]])) {
					$arr_cache_user_admin[$log["user_id"]] = $clsUser->getOne($log["user_id"],"more_information");
				}
				$oneUser = $arr_cache_user_admin[$log["user_id"]];
				$more_user = $clsISO->to_array_json($oneUser["more_information"]);
				$pro_id = !empty($more_user["staff_permiss_id"]) ? $more_user["staff_permiss_id"] : 0;
			}else{
				$pro_id = !empty($log["user_id"]) ? $log["user_id"] : 0;
			}
			if(!isset($arr_cache_user[$pro_id])) {
				$arr_cache_user[$pro_id] = $clsProfile->getLastName($pro_id,0,0);
			}
			if(!empty($pro_id)) {
				$crawl_lowfloor[$project_id]["user_upd"] = '<span class="cursor-pointer" data-url="/index.php?mod=home&act=load_profile_popover&user_id='.$pro_id.'" data-toggle="webui-popover" data-trigger="hover" data-width="350">'.$arr_cache_user[$pro_id].'</span>';
			}
			unset($log);
			$crawl_lowfloor[$project_id]["is_changed"] = (!empty($arrCheckLowFloor[$agency_id][$project_id]) && $arrCheckLowFloor[$agency_id][$project_id] == 1) ? 0 : 1;
		}
		$list_agency[$key]['crawl_lowfloor'] = $crawl_lowfloor;
		$list_agency[$key]['total_stock'] = $total_dq;		
		if(!empty($is_crawl)) {
			$list_agency[$key]["order_no"] = 1;
		}else{
			$list_agency[$key]["order_no"] = 2;
		}	
	}
	//	var_dump($arr_cache_user);die;
	//	$clsISO->print_pre($arr_total_block);die;
	$total_sales_arrs = @array_column($list_agency, 'order_no');
	@array_multisort($total_sales_arrs, SORT_ASC, $list_agency);
	$assign_list["lst_project"] = $lst_project;
	$assign_list["list_agency"] = $list_agency;
	$assign_list["arr_total_project"] = $arr_total_project;
	$assign_list["totalStock"] = $totalStock;
	/*=============Title & Description Page==================*/
	$title_page = 'Cập nhật bảng hàng Excel thấp tầng - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = ' Cập nhật bảng hàng Excel thấp tầng - '.PAGE_NAME;
	$assign_list["description_page"] = $description_page;
}
function lowfloor_crawl_agency_lowfloorOld(){
//	 ini_set('display_errors', '1');
//	 ini_set('display_startup_errors', '1');
//	 error_reporting(E_ALL);
	ini_set('memory_limit', '5048M');
	global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID,$profile_id,$clsConfiguration;
	$clsCrawl = new Crawl();
	$clsProperty = new Property();
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	$clsStockLog = new StockLog();
	$clsLogCrawl = new LogCrawl();
	$clsStockAgent = new StockAgent();
	$clsTmpStockAgent = new TmpStockAgent();
	
	#- Required Library
	require_once (DIR_INCLUDES.'/json_master/autoload.php');				
	require_once (DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
	
	$agency_id = (int) Input::post("agency_id", 0);
	$target_id = (int) Input::post("target_id", 0);
	$stock_type = _BLOCK_TYPE_LOWFLOOR_SALE;	
	
	/*giá min max*/
	$field_config_price = $clsConfiguration->getValue('field_config_price');
	$field_config_price = $clsISO->to_array_json($field_config_price);
	$arr_price_min_max = [];
	if(!empty($field_config_price)) {
		foreach ($field_config_price as $key => $val) {
			if($val["stock_type"] == $stock_type) {
				$arr_price_min_max[$val["project_id"]][$val["block_id"]] = $val;
			}
			
		}
	}
	/*end giá min max*/
	
	$oneAgency = $clsProperty->getByCond("`property_type`='_AGENCY' AND `property_id`='{$agency_id}' 
	AND JSON_SEARCH(`more_information`,'one','1',NULL,'$.crawl_lowfloor.*.is_crawl') IS NOT NULL");
	$total_stock_sold = $total_stock_new = $total_updated = 0;
	$min_max_block = [];
	if(!empty($oneAgency) && !empty($target_id)) {
		$more_information = $oneAgency['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$crawl_lowfloors = $core->get_field($more_information, "crawl_lowfloor", []);
		$stock_status_id = _STOCK_STATUS_LOCK_ID;
		if(isset($more_information['stock_status_id']) && !empty($more_information['stock_status_id'])){
			$stock_status_id = $more_information['stock_status_id'];
		}
		$arr_crawl = $arr_not_upd = $arr_upd = $arr_data = $arr_ms_code_new = $ms_codes = array();
		$crawl_lowfloor = $crawl_lowfloors[$target_id];
		if(!empty($crawl_lowfloor["is_crawl"])) {
			$spreadsheetId = $crawl_lowfloor['sheetID'];
			$lst_range = $crawl_lowfloor['sheet_name'];
			if(!empty(trim($spreadsheetId)) && !empty($lst_range)) {
				$ranges = explode("|",$lst_range);
				foreach ($ranges as $k => $range) {
					$ranges[$k] = $range."!A1:AZ500";
				}
				#cache demo
				$cachedName = sprintf('%s_%s.json', $oneAgency["title"], $target_id);
				$cachedFile = DIR_CACHE_JSON.'/crawl/'.$cachedName;
				if(file_exists($cachedFile) && 1==2){
					$decoder = new Webmozart\Json\JsonDecoder();
					$res = $decoder->decodeFile($cachedFile);
					// @unlink($cachedFile);
				}else{
					$res = $clsCrawl->getDataLowFloor($spreadsheetId,$ranges,$target_id,$agency_id,$stock_type,$crawl_lowfloor);
//					$encoder = new Webmozart\Json\JsonEncoder();
//					$encoder->encodeFile($res, $cachedFile); 
				}
//				$clsISO->print_pre($res);die;
				#end cache demo
				$lst_stock_id = [];
				if(!empty($res["result"])) {
					$arr_data = !empty($res["tblData"]) ? $res["tblData"] : array();
					if(!empty($arr_data)) {
						$arr_data_code = $lstStock = array();
						$total_record = @count($arr_data);
						$arr_stock_code_not_in = $arr_stock_id_not_in = array();
						foreach ($arr_data as $key => $val) {
							$ms_code = $val['ms_code'];
							if (!empty($ms_code) && preg_match(REGEX_MS_CODE, $ms_code)) {
								$val['ms_code'] = addslashes($ms_code);
								$arr_data_code[$ms_code] = $val;
							}
							unset($ms_code);
						}
						if(!empty($arr_data_code)) {
							$arr_ms_code = array_keys($arr_data_code);
							$str_code_in = implode("','",$arr_ms_code);
							$arr_code_old = [];
							$list_agency_stocks = $clsStock->getAll("`stock_type`='{$stock_type}' AND `project_id`='{$target_id}' AND (`agency_id` <> '"._AGENCY_FH_ID."' OR (`agency_id` = '"._AGENCY_FH_ID."' AND `status_id`='"._STOCK_STATUS_SOLD_ID."')) AND `ms_code` IN ('".$str_code_in."')",$clsStock->pkey.',more_information,ms_code,status_id,block_id');
							
							if(!empty($list_agency_stocks)) {
								foreach ($list_agency_stocks as $k_stock => $oneStock) {
									$arr_stock_id_not_in[] = $oneStock[$clsStock->pkey];
									$arr_stock_code_not_in[] = $oneStock["ms_code"];
									$arr_data_code[$oneStock["ms_code"]]["stock_id"] = $oneStock["stock_id"];
									$arr_data_code[$oneStock["ms_code"]]["oneStock"] = $oneStock;
									if($oneStock['status_id'] == _STOCK_STATUS_LOCK_ID){
										$arr_code_old[] = $oneStock["ms_code"];
									}
									
									/*giá min max*/
									if(!empty($arr_price_min_max[$target_id])) {
										$price_min_max = isset($arr_price_min_max[$target_id][$oneStock["block_id"]]) ? $arr_price_min_max[$target_id][$oneStock["block_id"]] : $arr_price_min_max[$target_id][0];
									}
									$min = !empty($price_min_max["min"]) ? (int)$price_min_max["min"] : "";
									$max = !empty($price_min_max["max"]) ? (int)$price_min_max["max"] : "";
									$arr_data_code[$oneStock["ms_code"]]["min"] = $min;
									$arr_data_code[$oneStock["ms_code"]]["max"] = $max;
									/*end giá min max*/
								}
							}
							$arr_ms_code_new = array_diff($arr_ms_code,$arr_code_old);
							$total_stock_new = !empty($arr_ms_code_new) ? count($arr_ms_code_new) : 0;
						}else{
							$data = array(
								"title_log"	=>	'File Gooogle Sheet đã bị thay đổi',
								"type"	=>	1,	//0:tổng hợp,1:drive,2:hình ảnh,3:copy,
								"result_type"	=>	"change_field",	//change_field,copy_speadsheet,read_speadsheet
							);
							$clsLogCrawl->log($agency_id,$target_id, $data, $stock_type);
							echo json_encode(array(
								'result'	=>	false,
								'msg' => 'File Gooogle Sheet đã bị thay đổi. Hãy cập nhật lại cấu hình'
							));	die();
						}
						$lstStock = array_values($arr_data_code);
						# Cập nhật các căn thành đã bán
						$field = "{$clsStock->pkey},`ms_code`,`status_id`,`more_information`";
						$g_cond = "`stock_type`='{$stock_type}' AND `project_id`='{$target_id}' AND `agency_id`='{$agency_id}' 
						AND (`status_id`>0 AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."')";
						if(!empty($arr_stock_id_not_in)){
							$g_cond.= " AND `stock_id` not in(".implode(',', $arr_stock_id_not_in).")";
						}
						if($target_id == 3) {
							$g_cond.= " AND `block_id`<>'10933'";
						}
						$list_sold_stocks = $clsStock->getAll($g_cond, $field);
						// var_dump($list_sold_stocks);die;
						if(!empty($list_sold_stocks)){
							foreach($list_sold_stocks as $key => $val){
								$logs = array();
								$more_information = $val['more_information'];
								$more_information = $clsISO->to_array_json($more_information);
								$more_information['status_id'] = _STOCK_STATUS_SOLD_ID;
								$more_information['user_id_update_sold'] = $profile_id;
								
								$m_field = "{$clsStockMeta->pkey},logs";
								$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$val[$clsStock->pkey]."'", $m_field);
								if(!empty($oneStockMeta)){
									$logs = $oneStockMeta['logs'];
									$logs = $clsISO->to_array_json($logs);
								} else {
									$clsStockMeta->insert(array(
										'stock_id' => $val[$clsStock->pkey],
										'reg_date' => time(),
										'upd_date' => time()
									));
									$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$val[$clsStock->pkey]."'", $m_field);
								}
								$logs[$clsISO->getUniqid()] = array(
									'reg_date' => time(), 
									'user_id' => $profile_id,
									'from_id' => $val['status_id'],
									'to_id' => _STOCK_STATUS_SOLD_ID,
									'field' => 'status_id',
									'from' => '_front',
								);
								if($clsStock->updateOne($val[$clsStock->pkey], array(
									'ms_date' => time(),
									'status_id' => _STOCK_STATUS_SOLD_ID,
									'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
								))) {
									++$total_stock_sold;
									if(!empty($oneStockMeta) && !empty($logs)){
										$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
											'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
											'upd_date' => time()
										));
									}
								}
							}
							unset($list_sold_stocks);
						}
						// var_dump($lstStock); die();
						if(!empty($lstStock)) {
							foreach ($lstStock as $k_stock => $v_stock){
								$oneStock = $v_stock["oneStock"];
								$stock_id = $v_stock['stock_id'];
								$ms_code = $v_stock["ms_code"];
								$ms_code = preg_replace('/\s+/', '', $ms_code);
								if(!empty($oneStock)) {
									$ms_codes[] = $ms_code;
									$lst_stock_id[$target_id][] = $stock_id;
								}
								#
								$total_price_vat = $core->get_price_field($v_stock, "total_price_vat", 0);
								$total_price_early = $core->get_price_field($v_stock, "total_price_early", 0);
								$type_name = $core->get_field($v_stock, "type_id", "");
								$status_name = $core->get_field($v_stock, "status_id", "");
								$stock_hold_name = $core->get_field($v_stock, "stock_hold_id", "");
								$contract_type_name = $core->get_field($v_stock, "contract_type_id", "");
								$invest_fund_name = $core->get_field($v_stock, "invest_fund_id", "");
								$sale_status_name = $core->get_field($v_stock, "sale_status_id", "");
								$agent_lock_name = $core->get_field($v_stock, "agent_lock_id", "");
								$deposit_agent_name = $core->get_field($v_stock, "deposit_agent_id", "");
								$bank_second_name = $core->get_field($v_stock, "bank_second_id", "");
								$bank_name = $core->get_field($v_stock, "bank_id", "");
								$home_direction_name = $core->get_field($v_stock, "home_direction_id", "");
								$TCBG = $core->get_field($v_stock, "TCBG", "");
								$notes = $core->get_field($v_stock, "notes", "");
								$csbh = $core->get_field($v_stock, "csbh", "");
								$DT_TT = $core->get_field($v_stock, "DT_TT", "");
								$DT_Tim = $core->get_field($v_stock, "DT_Tim", "");
								$price_temporary_ns = $core->get_field($v_stock, "price_temporary_ns", "");
								$link_smartchip = $core->get_field($v_stock, "link_smartchip", "");
								if(empty($oneStock)) {
									$arr_not_upd[] = $ms_code;
									continue;
								}
								$more_information = $oneStock['more_information'];
								$more_information_stock = $clsISO->to_array_json($more_information);
								#
								$logs = array(); $m_field = "{$clsStockMeta->pkey},`logs`"; 
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
								$upd_field['agency_id'] = $agency_id;
								$upd_field['status_id'] = $stock_status_id;
								$more_information_stock['ms_code'] = $ms_code;		
								$more_information_stock['agency_id'] = $agency_id;								
								$more_information_stock['status_id'] = $stock_status_id;	
								if(!empty($type_name)) {
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_TYPE_VILLA' and (`property_code`='".$type."' or `slug_vn`='".$core->replaceSpace($type)."' or `slug`='".$core->replaceSpace($type)."')", $clsProperty->pkey);
									if(!empty($tmp)) {
										$type_id = $tmp[$clsProperty->pkey];
										$upd_field["type_id"] = $type_id;
										$more_information_stock["type_id"] = $type_id;
									}
								}
								if(!empty($status_name)) {
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_STATUS' and `slug`='".$core->replaceSpace($status)."'", $clsProperty->pkey);
									if(!empty($tmp)) {
										$status_id = $tmp[$clsProperty->pkey];
										$upd_field["status_id"] = $status_id;
										$more_information_stock["status_id"] = $status_id;
									}
								}
								if(!empty($stock_hold_name)) {
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_STOCK_HOLD' and `slug`='".$core->replaceSpace($stock_hold)."'", $clsProperty->pkey);
									if(!empty($tmp)) {
										$stock_hold_id = $tmp[$clsProperty->pkey];
										$more_information_stock["stock_hold_id"] = $stock_hold_id;
									}
								}
								if(!empty($contract_type_name)) {
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_CONTRACT_TYPE' and `slug`='".$core->replaceSpace($contract_type_name)."'", $clsProperty->pkey);
									if(!empty($tmp)){
										$contract_type_id = $tmp[$clsProperty->pkey];
									} else {
										$contract_type_id = $clsProperty->getMaxId();
										$clsProperty->insert(array(
											$clsProperty->pkey => $contract_type_id,
											'property_type' => '_CONTRACT_TYPE',
											'title' => $contract_type_name,
											'slug' => $core->replaceSpace($contract_type_name),
											'order_no' => $clsProperty->getMaxorderNo(),
											'user_id' => 0,
											'user_id_update' => 0,
											'reg_date' => time(),
											'upd_date' => time()
										));
									}
									$more_information_stock["contract_type_id"] = $contract_type_id;
								}
								if(!empty($contract_subject)) {
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_CONTRACT_SUBJECT' and `slug`='".$core->replaceSpace($tblData[$i][$key])."'", $clsProperty->pkey);
									if(!empty($tmp)){
										$contract_subject_id = $tmp[$clsProperty->pkey];
									} else {
										$contract_subject_id = $clsProperty->getMaxId();
										$clsProperty->insert(array(
											$clsProperty->pkey => $contract_subject_id,
											'property_type' => '_CONTRACT_SUBJECT',
											'title' => $contract_subject,
											'slug' => $core->replaceSpace($contract_subject),
											'order_no' => $clsProperty->getMaxorderNo(),
											'user_id' => 0,
											'user_id_update' => 0,
											'reg_date' => time(),
											'upd_date' => time()
										));
									}
									$more_information_stock["contract_subject_id"] = $contract_subject_id;
								}
								if(!empty($invest_fund_name)) {
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_INVEST_FUND' and `slug`='".$core->replaceSpace($invest_fund_name)."'", $clsProperty->pkey);
									if(!empty($tmp)){
										$invest_fund_id = $tmp[$clsProperty->pkey];
									} else {
										$invest_fund_id = $clsProperty->getMaxId();
										$clsProperty->insert(array(
											$clsProperty->pkey => $invest_fund_id,
											'property_type' => '_INVEST_FUND',
											'title' => $invest_fund_name,
											'slug' => $core->replaceSpace($invest_fund_name),
											'order_no' => $clsProperty->getMaxorderNo(),
											'user_id' => 0,
											'user_id_update' => 0,
											'reg_date' => time(),
											'upd_date' => time()
										));
									}
									$more_information_stock["invest_fund_id"] = $invest_fund_id;
								}
								if(!empty($sale_status_name)) {
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_SALE_STATUS' and `slug`='".$core->replaceSpace($sale_status_name)."'", $clsProperty->pkey);
									if(!empty($tmp)){
										$sale_status_id = $tmp[$clsProperty->pkey];
									} else {
										$sale_status_id = $clsProperty->getMaxId();
										$clsProperty->insert(array(
											$clsProperty->pkey => $sale_status_id,
											'property_type' => '_SALE_STATUS',
											'title' => $sale_status_name,
											'slug' => $core->replaceSpace($sale_status_name),
											'order_no' => $clsProperty->getMaxorderNo(),
											'user_id' => 0,
											'user_id_update' => 0,
											'reg_date' => time(),
											'upd_date' => time()
										));
									}
									$more_information_stock["sale_status_id"] = $sale_status_id;
								}
								if(!empty($agent_lock_name)) {
									$tmp = $clsProperty->getByCond("`property_type`='_AGENCY' and (`slug_vn`='".$core->replaceSpace($agent_lock_name)."' or `slug`='".$core->replaceSpace($agent_lock_name)."')", $clsProperty->pkey);
									if(!empty($tmp)){
										$agent_lock_id = $tmp[$clsProperty->pkey];
									} else {
										$agent_lock_id = $clsProperty->getMaxId();
										$clsProperty->insert(array(
											$clsProperty->pkey => $agent_lock_id,
											'property_type' => '_AGENCY',
											'title' => $agent_lock_name,
											'slug' => $core->replaceSpace($agent_lock_name),
											'order_no' => $clsProperty->getMaxorderNo(),
											'user_id' => 0,
											'user_id_update' => 0,
											'reg_date' => time(),
											'upd_date' => time()
										));
									}
									$more_information_stock["agent_lock_id"] = $agent_lock_id;
								}
								if(!empty($deposit_agent_name)) {
									$tmp = $clsProperty->getByCond("`property_type`='_AGENCY' and (`slug_vn`='".$core->replaceSpace($deposit_agent_name)."' or `slug`='".$core->replaceSpace($deposit_agent_name)."')", $clsProperty->pkey);
									if(!empty($tmp)){
										$deposit_agent_id = $tmp[$clsProperty->pkey];
									} else {
										$deposit_agent_id = $clsProperty->getMaxId();
										$clsProperty->insert(array(
											$clsProperty->pkey => $deposit_agent_id,
											'property_type' => '_AGENCY',
											'title' => $deposit_agent_name,
											'slug' => $core->replaceSpace($deposit_agent_name),
											'order_no' => $clsProperty->getMaxorderNo(),
											'user_id' => 0,
											'user_id_update' => 0,
											'reg_date' => time(),
											'upd_date' => time()
										));
									}
									$more_information_stock["deposit_agent_id"] = $deposit_agent_id;
								}
								if(!empty($bank_second_name)) {
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_BANK' and slug='".$core->replaceSpace($bank_second_name)."'", $clsProperty->pkey);
									if(!empty($tmp)){
										$bank_second_id = $tmp[$clsProperty->pkey];
									} else {
										$bank_second_id = $clsProperty->getMaxId();
										$clsProperty->insert(array(
											$clsProperty->pkey => $bank_second_id,
											'property_type' => '_BANK',
											'title' => $bank_second_name,
											'slug' => $core->replaceSpace($bank_second_name),
											'order_no' => $clsProperty->getMaxorderNo(),
											'user_id' => 0,
											'user_id_update' => 0,
											'reg_date' => time(),
											'upd_date' => time()
										));
									}
									$more_information_stock["bank_second_id"] = $bank_second_id;
								}
								if(!empty($bank_name)) {
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_BANK' and slug='".$core->replaceSpace($bank_name)."'", $clsProperty->pkey);
									if(!empty($tmp)){
										$bank_id = $tmp[$clsProperty->pkey];
									} else {
										$bank_id = $clsProperty->getMaxId();
										$clsProperty->insert(array(
											$clsProperty->pkey => $bank_id,
											'property_type' => '_BANK',
											'title' => $bank_name,
											'slug' => $core->replaceSpace($bank_name),
											'order_no' => $clsProperty->getMaxorderNo(),
											'user_id' => 0,
											'user_id_update' => 0,
											'reg_date' => time(),
											'upd_date' => time()
										));
									}
									$more_information_stock["bank_id"] = $bank_id;
								}
								if(!empty($home_direction_name)) {
									$tmp = $clsProperty->getByCond("`property_type`='_DIRECTION' and slug='".$core->replaceSpace($home_direction_name)."'", $clsProperty->pkey);
									if(!empty($tmp)) {
										$home_direction_id = $tmp[$clsProperty->pkey];
										$upd_field["home_direction_id"] = $home_direction_id;
										$more_information_stock["home_direction_id"] = $home_direction_id;
									}
								}
								if(!empty($TCBG)) {
									$more_information_stock["TCBG"] = $TCBG;
								}
								if(!empty($notes)) {
									$more_information_stock["notes"] = $notes;
								}
								if(!empty($csbh)) {
									$more_information_stock["csbh"] = $csbh;
								}
								if(!empty($DT_TT)) {
									$more_information_stock["DT_TT"] = $DT_TT;
								}
								if(!empty($DT_Tim)) {
									$more_information_stock["DT_Tim"] = $DT_Tim;
								}
								$price_temporary_ns = !empty($price_temporary_ns) ? $price_temporary_ns : $link_smartchip;
								$more_information_stock["price_temporary_ns"] = $price_temporary_ns;
								if(!empty($total_price_vat)){
									$upd_field['total_price_vat'] = $clsStock->getPriceOriginV2($total_price_vat);
								} else {
									if(!empty($total_price_early)){
										$upd_field['total_price_vat'] = $clsStock->getPriceOriginV2($total_price_early);
									}	
								}
								foreach ($v_stock as $p_field => $val) {
									if(in_array($p_field, array('total_price','total_price_vat','total_price_early'
										,'total_price_progress','total_price_bank','total_price_bank_30','total_price_bank_36'
										,'total_price_bank_12','total_price_bank_18'))){
										$price_tmp = !empty($val) ? $clsStock->getPriceOriginV2($val) : 0;								
										if((!isset($more_information_stock[$p_field]) || (!empty($more_information_stock[$p_field]) 
											&& $more_information_stock[$p_field] != $price_tmp))){
											$logs[$clsISO->getUniqid()] = array(
												'reg_date' => time(),
												'user_id' => $profile_id,
												'from_value' => $more_information_stock[$p_field],
												'to_value' => $price_tmp,
												'field' => $p_field,							
												'from' => '_front',
											);
										}
										$more_information_stock[$p_field] = $price_tmp;
									}
								}	
								$upd_field['upd_date'] = time();
								$upd_field['more_information'] = json_encode($more_information_stock, JSON_UNESCAPED_UNICODE);
								//	$clsStock->setDeBug(1);
								if($clsStock->updateOne($oneStock[$clsStock->pkey], $upd_field)){
									$total_updated += 1;
									$arr_upd[] = $ms_code;
									if(!empty($oneStockMeta) && !empty($logs)){
										$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
											'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
											'upd_date' => time()
										));	
									}
								} else{
									$arr_not_upd[] = $ms_code;
								}
							}
						}
						if($total_updated > 0) {				
							#activity log	
							$clsActivityLog = new ActivityLog();
							$log = $clsActivityLog->addActivityLog("Stock","update");
						}
					} else {
						$field = "{$clsStock->pkey},`status_id`,`more_information`";
						$g_cond = "`stock_type`='".$stock_type."' AND `agency_id`='{$agency_id}' AND `project_id`='{$target_id}' AND (`status_id`>0 AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."')";
						if($target_id == 3) {
							$g_cond.= " AND `block_id`<>'10933'";
						}
						$list_stocks = $clsStock->getAll($g_cond, $field);
						if(!empty($list_stocks)){
							$total_updated = 0;
							foreach($list_stocks as $key => $val){
								$logs = array();
								$more_information = $val['more_information'];
								$more_information = $clsISO->to_array_json($more_information);
								$more_information['status_id'] = _STOCK_STATUS_SOLD_ID;
								$more_information['user_id_update_sold'] = $profile_id;
								
								$m_field = "{$clsStockMeta->pkey},logs";
								$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$val[$clsStock->pkey]."'", $m_field);
								if(!empty($oneStockMeta)){
									$logs = $oneStockMeta['logs'];
									$logs = $clsISO->to_array_json($logs);
								} else {
									$clsStockMeta->insert(array(
										'stock_id' => $val[$clsStock->pkey],
										'reg_date' => time(),
										'upd_date' => time()
									));
									$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$val[$clsStock->pkey]."'", $m_field);
								}
								$logs[$clsISO->getUniqid()] = array(
									'reg_date' => time(), 
									'user_id' => $profile_id,
									'from_id' => $val['status_id'],
									'to_id' => _STOCK_STATUS_SOLD_ID,
									'field' => 'status_id',								
									'from' => '_front',
								);
								if($clsStock->updateOne($val[$clsStock->pkey], array(
									'ms_date' => time(),
									'upd_date' => time(),
									'status_id' => _STOCK_STATUS_SOLD_ID,
									'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
								))) {
									++$total_stock_sold;
									$arr_upd[] = $ms_code;
									if(!empty($oneStockMeta) && !empty($logs)){
										$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
											'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
											'upd_date' => time()
										));	
									}
								}
							}
							if($total_updated > 0) {				
								#activity log		
								$clsActivityLog = new ActivityLog();
								$log = $clsActivityLog->addActivityLog("Stock","update");
							}
							unset($tmp);
						}
					}
					//log cập nhật
					$arr_ms_code_new = array_intersect($arr_ms_code_new,$arr_upd);
					$total_stock_new = !empty($arr_ms_code_new) ? count($arr_ms_code_new) : 0;					
					// $clsLogCrawl->insertLog('update', $stock_type, $block_id, $agency_id, $data, "_front");
					#log new
					$arr_data_upd = array(
						"total_stock_sold"	=>	$total_stock_sold,
						"total_stock_new"	=>	$total_stock_new,
						"total_stock"	=>	$total_updated,
						"stock_not_upd"	=>	$arr_not_upd,
						"data_log"	=>	$arr_data,
						"title_log"	=>	'Tổng quỹ: '.$total_updated.', Đã bán: '.$total_stock_sold.', Nhập mới: '.$total_stock_new,
						"type"	=>	1,	//0:tổng hợp,1:drive,2:hình ảnh,3:copy,
						"result_type"	=>	"update",	//change_field,copy_speadsheet,read_speadsheet
					);
					$clsLogCrawl->log($agency_id,$target_id, $arr_data_upd, $stock_type);
					#luu bang tam
					$clsTmpStockAgent->updateStockTmp($agency_id,$stock_type,$target_id,$ms_codes);			
					#tong hop quy dai ly
					/*$resStockCrawl = $clsStockAgent->updateStockCrawl($agency_id);*/
					// Start Logs 
					$stock_ids = !empty($lst_stock_id[$target_id]) ? $lst_stock_id[$target_id] : array();
//					$clsStockLog->setDeBug(1);
					$tmp = $clsStockLog->getByCond("`stock_type`='{$stock_type}' AND `agency_id`='{$agency_id}' AND `project_id`='{$target_id}' AND FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".date('d/m/Y')."'", $clsStockLog->pkey);
//					$clsISO->print_pre($tmp);die;
					if(!empty($tmp)){
						$clsStockLog->updateOne($tmp[$clsStockLog->pkey], array(
							'more_information' => json_encode($stock_ids, JSON_UNESCAPED_UNICODE)
						));
					} else {
						$clsStockLog->insert(array(
							'stock_type' => $stock_type,
							'agency_id' => $agency_id,
							'project_id' => $target_id,
							'more_information' => json_encode($stock_ids, JSON_UNESCAPED_UNICODE),
							'reg_date' => time(),
							'user_id' => $profile_id
						));
					}
					/** End */
					
				}else{
					echo json_encode(array(
						'result'	=>	false,
						'msg' => 'Lỗi đọc file dữ liệu'
					));	die();
				}
				
			}else{
				echo json_encode(array(
					'result'	=>	false,
					'msg' => 'Đại lý chưa có cấu hình dự án này'
				));	die();
			}
		}else{
			echo json_encode(array(
				'result'	=>	false,
				'msg' => 'Đại lý chưa có cấu hình dự án này'
			));	die();
		}
	}
	echo json_encode(array(
		'result'	=>	true,
		'msg' => 'Tổng quỹ: '.$total_updated.', Đã bán: '.$total_stock_sold.', Nhập mới: '.$total_stock_new,
		'total_updated' => $total_updated,
		'total_stock_sold' => $total_stock_sold,
		'total_stock_new' => $total_stock_new,
		'stock_not_upd' => $arr_not_upd,
		'arr_upd' => $arr_upd,
	));	die();
}
function lowfloor_crawl_agency_lowfloor(){
//	 ini_set('display_errors', '1');
//	 ini_set('display_startup_errors', '1');
//	 error_reporting(E_ALL);
	ini_set('memory_limit', '5048M');
	global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID,$profile_id,$clsConfiguration;
	$clsCrawlLocal = new CrawlLocal(); // CrawlLocal tự khởi tạo Crawl khi cần (sheet private); bỏ new Crawl() thừa ở đây để tránh OAuth ~1s
	$clsProperty = new Property();
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	$clsStockLog = new StockLog();
	$clsLogCrawl = new LogCrawl();
	$clsStockAgent = new StockAgent();
	$clsTmpStockAgent = new TmpStockAgent();

	// Gộp N+1 tra cứu property: bọc $clsProperty để memo getByCond trong 1 lần crawl.
	// Mỗi căn tra ~12 lần (type/status/bank/agent...) mà giá trị lặp lại giữa các căn → cache theo tham số tra,
	// chỉ query DB lần đầu cho mỗi điều kiện. insert() xoá cache để căn sau thấy property vừa tạo (không insert trùng).
	// Các method/thuộc tính dùng trong hàm này: getByCond, insert, getMaxId, getMaxorderNo, pkey — uỷ quyền cho object gốc.
	$clsProperty = new class($clsProperty) {
		private $real;
		private $cache = array();
		public $pkey;
		public function __construct($real) { $this->real = $real; $this->pkey = $real->pkey; }
		public function getByCond(...$args) {
			$key = md5(serialize($args));
			if (!array_key_exists($key, $this->cache)) {
				$this->cache[$key] = call_user_func_array(array($this->real, 'getByCond'), $args);
			}
			return $this->cache[$key];
		}
		public function insert($data) { $this->cache = array(); return $this->real->insert($data); }
		public function getMaxId() { return $this->real->getMaxId(); }
		public function getMaxorderNo() { return $this->real->getMaxorderNo(); }
	};
	
	#- Required Library
	require_once (DIR_INCLUDES.'/json_master/autoload.php');				
	require_once (DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
	
	$agency_id = (int) Input::post("agency_id", 0);
	$target_id = (int) Input::post("target_id", 0);
	$stock_type = _BLOCK_TYPE_LOWFLOOR_SALE;	
	
	/*giá min max*/
	$field_config_price = $clsConfiguration->getValue('field_config_price');
	$field_config_price = $clsISO->to_array_json($field_config_price);
	$arr_price_min_max = [];
	if(!empty($field_config_price)) {
		foreach ($field_config_price as $key => $val) {
			if($val["stock_type"] == $stock_type) {
				$arr_price_min_max[$val["project_id"]][$val["block_id"]] = $val;
			}
			
		}
	}
	/*end giá min max*/
	
	$oneAgency = $clsProperty->getByCond("`property_type`='_AGENCY' AND `property_id`='{$agency_id}' 
	AND JSON_SEARCH(`more_information`,'one','1',NULL,'$.crawl_lowfloor.*.is_crawl') IS NOT NULL");
	$total_stock_sold = $total_stock_new = $total_updated = 0;
	$min_max_block = [];
	if(!empty($oneAgency) && !empty($target_id)) {
		$more_information = $oneAgency['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$crawl_lowfloors = $core->get_field($more_information, "crawl_lowfloor", []);
		$stock_status_id = _STOCK_STATUS_LOCK_ID;
		if(isset($more_information['stock_status_id']) && !empty($more_information['stock_status_id'])){
			$stock_status_id = $more_information['stock_status_id'];
		}
		$arr_crawl = $arr_not_upd = $arr_upd = $arr_data = $arr_ms_code_new = $ms_codes = array();
		$crawl_lowfloor = $crawl_lowfloors[$target_id];
		if(!empty($crawl_lowfloor["is_crawl"])) {
			$spreadsheetId = $crawl_lowfloor['sheetID'];
			$lst_range = $crawl_lowfloor['sheet_name'];
			if(!empty(trim($spreadsheetId)) && !empty($lst_range)) {
				$ranges = explode("|",$lst_range);
				foreach ($ranges as $k => $range) {
					$ranges[$k] = $range."!A1:AZ500";
				}
				#cache demo
				$cachedName = sprintf('%s_%s.json', $oneAgency["title"], $target_id);
				$cachedFile = DIR_CACHE_JSON.'/crawl/'.$cachedName;
				if(file_exists($cachedFile) && 1==2){
					$decoder = new Webmozart\Json\JsonDecoder();
					$res = $decoder->decodeFile($cachedFile);
					// @unlink($cachedFile);
				}else{
					$res = $clsCrawlLocal->getDataLowFloor($spreadsheetId,$ranges,$target_id,$agency_id,$stock_type,$crawl_lowfloor);
//					$encoder = new Webmozart\Json\JsonEncoder();
//					$encoder->encodeFile($res, $cachedFile); 
				}
//				$clsISO->print_pre($res);die;
				#end cache demo
				$lst_stock_id = [];
				if(!empty($res["result"])) {
					$arr_data = !empty($res["tblData"]) ? $res["tblData"] : array();
					if(!empty($arr_data)) {
						$arr_data_code = $lstStock = array();
						$total_record = @count($arr_data);
						$arr_stock_code_not_in = $arr_stock_id_not_in = array();
						foreach ($arr_data as $key => $val) {
							$ms_code = $val['ms_code'];
							if (!empty($ms_code) && preg_match(REGEX_MS_CODE, $ms_code)) {
								$val['ms_code'] = addslashes($ms_code);
								$arr_data_code[$ms_code] = $val;
							}
							unset($ms_code);
						}
						if(!empty($arr_data_code)) {
							$arr_ms_code = array_keys($arr_data_code);
							$str_code_in = implode("','",$arr_ms_code);
							$arr_code_old = [];
							$list_agency_stocks = $clsStock->getAll("`stock_type`='{$stock_type}' AND `project_id`='{$target_id}' AND (`agency_id` <> '"._AGENCY_FH_ID."' OR (`agency_id` = '"._AGENCY_FH_ID."' AND `status_id`='"._STOCK_STATUS_SOLD_ID."')) AND `ms_code` IN ('".$str_code_in."')",$clsStock->pkey.',more_information,ms_code,status_id,block_id');
							
							if(!empty($list_agency_stocks)) {
								foreach ($list_agency_stocks as $k_stock => $oneStock) {
									$arr_stock_id_not_in[] = $oneStock[$clsStock->pkey];
									$arr_stock_code_not_in[] = $oneStock["ms_code"];
									$arr_data_code[$oneStock["ms_code"]]["stock_id"] = $oneStock["stock_id"];
									$arr_data_code[$oneStock["ms_code"]]["oneStock"] = $oneStock;
									if($oneStock['status_id'] == _STOCK_STATUS_LOCK_ID){
										$arr_code_old[] = $oneStock["ms_code"];
									}
									
									/*giá min max*/
									if(!empty($arr_price_min_max[$target_id])) {
										$price_min_max = isset($arr_price_min_max[$target_id][$oneStock["block_id"]]) ? $arr_price_min_max[$target_id][$oneStock["block_id"]] : $arr_price_min_max[$target_id][0];
									}
									$min = !empty($price_min_max["min"]) ? (int)$price_min_max["min"] : "";
									$max = !empty($price_min_max["max"]) ? (int)$price_min_max["max"] : "";
									$arr_data_code[$oneStock["ms_code"]]["min"] = $min;
									$arr_data_code[$oneStock["ms_code"]]["max"] = $max;
									/*end giá min max*/
								}
							}
							$arr_ms_code_new = array_diff($arr_ms_code,$arr_code_old);
							$total_stock_new = !empty($arr_ms_code_new) ? count($arr_ms_code_new) : 0;
						}else{
							$data = array(
								"title_log"	=>	'File Gooogle Sheet đã bị thay đổi',
								"type"	=>	1,	//0:tổng hợp,1:drive,2:hình ảnh,3:copy,
								"result_type"	=>	"change_field",	//change_field,copy_speadsheet,read_speadsheet
							);
							$clsLogCrawl->log($agency_id,$target_id, $data, $stock_type);
							echo json_encode(array(
								'result'	=>	false,
								'msg' => 'File Gooogle Sheet đã bị thay đổi. Hãy cập nhật lại cấu hình'
							));	die();
						}
						$lstStock = array_values($arr_data_code);
						# Cập nhật các căn thành đã bán
						$field = "{$clsStock->pkey},`ms_code`,`status_id`,`more_information`";
						$g_cond = "`stock_type`='{$stock_type}' AND `project_id`='{$target_id}' AND `agency_id`='{$agency_id}' 
						AND (`status_id`>0 AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."')";
						if(!empty($arr_stock_id_not_in)){
							$g_cond.= " AND `stock_id` not in(".implode(',', $arr_stock_id_not_in).")";
						}
						if($target_id == 3) {
							$g_cond.= " AND `block_id`<>'10933'";
						}
						$list_sold_stocks = $clsStock->getAll($g_cond, $field);
						// var_dump($list_sold_stocks);die;
						if(!empty($list_sold_stocks)){
							foreach($list_sold_stocks as $key => $val){
								$logs = array();
								$more_information = $val['more_information'];
								$more_information = $clsISO->to_array_json($more_information);
								$more_information['status_id'] = _STOCK_STATUS_SOLD_ID;
								$more_information['user_id_update_sold'] = $profile_id;
								
								$m_field = "{$clsStockMeta->pkey},logs";
								$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$val[$clsStock->pkey]."'", $m_field);
								if(!empty($oneStockMeta)){
									$logs = $oneStockMeta['logs'];
									$logs = $clsISO->to_array_json($logs);
								} else {
									$oneStockMeta = [
										$clsStockMeta->pkey => $clsStockMeta->getMaxId(),
										'stock_id' => $val[$clsStock->pkey],
									];
									$clsStockMeta->insert(array(
										'stock_id' => $val[$clsStock->pkey],
										'reg_date' => time(),
										'upd_date' => time()
									));
									//$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$val[$clsStock->pkey]."'", $m_field);
								}
								$logs[$clsISO->getUniqid()] = array(
									'reg_date' => time(), 
									'user_id' => $profile_id,
									'from_id' => $val['status_id'],
									'to_id' => _STOCK_STATUS_SOLD_ID,
									'field' => 'status_id',
									'from' => '_front',
								);
								if($clsStock->updateOne($val[$clsStock->pkey], array(
									'ms_date' => time(),
									'status_id' => _STOCK_STATUS_SOLD_ID,
									'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
								))) {
									++$total_stock_sold;
									if(!empty($oneStockMeta) && !empty($logs)){
										$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
											'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
											'upd_date' => time()
										));
									}
								}
							}
							unset($list_sold_stocks);
						}
//						$clsISO->print_pre($lstStock);die;
						if(!empty($lstStock)) {
							foreach ($lstStock as $k_stock => $v_stock){
								$oneStock = $v_stock["oneStock"];
								$stock_id = $v_stock['stock_id'];
								$ms_code = $v_stock["ms_code"];
								$ms_code = preg_replace('/\s+/', '', $ms_code);
								if(!empty($oneStock)) {
									$ms_codes[] = $ms_code;
									$lst_stock_id[$target_id][] = $stock_id;
								}
								#
								$total_price_vat = $core->get_price_field($v_stock, "total_price_vat", 0);
								$total_price_early = $core->get_price_field($v_stock, "total_price_early", 0);
								$type_name = $core->get_field($v_stock, "type_id", "");
								$status_name = $core->get_field($v_stock, "status_id", "");
								$stock_hold_name = $core->get_field($v_stock, "stock_hold_id", "");
								$contract_type_name = $core->get_field($v_stock, "contract_type_id", "");
								$invest_fund_name = $core->get_field($v_stock, "invest_fund_id", "");
								$sale_status_name = $core->get_field($v_stock, "sale_status_id", "");
								$agent_lock_name = $core->get_field($v_stock, "agent_lock_id", "");
								$deposit_agent_name = $core->get_field($v_stock, "deposit_agent_id", "");
								$bank_second_name = $core->get_field($v_stock, "bank_second_id", "");
								$bank_name = $core->get_field($v_stock, "bank_id", "");
								$home_direction_name = $core->get_field($v_stock, "home_direction_id", "");
								$TCBG = $core->get_field($v_stock, "TCBG", "");
								$notes = $core->get_field($v_stock, "notes", "");
								$csbh = $core->get_field($v_stock, "csbh", "");
								$DT_TT = $core->get_field($v_stock, "DT_TT", "");
								$DT_Tim = $core->get_field($v_stock, "DT_Tim", "");
								$price_temporary_ns = $core->get_field($v_stock, "price_temporary_ns", "");
								$link_smartchip = $core->get_field($v_stock, "link_smartchip", "");
								if(empty($oneStock)) {
									$arr_not_upd[] = $ms_code;
									continue;
								}
								$more_information = $oneStock['more_information'];
								$more_information_stock = $clsISO->to_array_json($more_information);
								#
								$logs = array(); $m_field = "{$clsStockMeta->pkey},`logs`"; 
								$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='{$stock_id}'", $m_field);
								$check_log = 0;
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
								$upd_field['agency_id'] = $agency_id;
								$upd_field['status_id'] = $stock_status_id;
								$more_information_stock['ms_code'] = $ms_code;		
								$more_information_stock['agency_id'] = $agency_id;								
								$more_information_stock['status_id'] = $stock_status_id;	
								if(!empty($type_name)) {
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_TYPE_VILLA' and (`property_code`='".$type."' or `slug_vn`='".$core->replaceSpace($type)."' or `slug`='".$core->replaceSpace($type)."')", $clsProperty->pkey);
									if(!empty($tmp)) {
										$type_id = $tmp[$clsProperty->pkey];
										$upd_field["type_id"] = $type_id;
										$more_information_stock["type_id"] = $type_id;
									}
								}
								if(!empty($status_name)) {
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_STATUS' and `slug`='".$core->replaceSpace($status)."'", $clsProperty->pkey);
									if(!empty($tmp)) {
										$status_id = $tmp[$clsProperty->pkey];
										$upd_field["status_id"] = $status_id;
										$more_information_stock["status_id"] = $status_id;
									}
								}
								if(!empty($stock_hold_name)) {
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_STOCK_HOLD' and `slug`='".$core->replaceSpace($stock_hold)."'", $clsProperty->pkey);
									if(!empty($tmp)) {
										$stock_hold_id = $tmp[$clsProperty->pkey];
										$more_information_stock["stock_hold_id"] = $stock_hold_id;
									}
								}
								if(!empty($contract_type_name)) {
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_CONTRACT_TYPE' and `slug`='".$core->replaceSpace($contract_type_name)."'", $clsProperty->pkey);
									if(!empty($tmp)){
										$contract_type_id = $tmp[$clsProperty->pkey];
									} else {
										$contract_type_id = $clsProperty->getMaxId();
										$clsProperty->insert(array(
											$clsProperty->pkey => $contract_type_id,
											'property_type' => '_CONTRACT_TYPE',
											'title' => $contract_type_name,
											'slug' => $core->replaceSpace($contract_type_name),
											'order_no' => $clsProperty->getMaxorderNo(),
											'user_id' => 0,
											'user_id_update' => 0,
											'reg_date' => time(),
											'upd_date' => time()
										));
									}
									$more_information_stock["contract_type_id"] = $contract_type_id;
								}
								if(!empty($contract_subject)) {
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_CONTRACT_SUBJECT' and `slug`='".$core->replaceSpace($tblData[$i][$key])."'", $clsProperty->pkey);
									if(!empty($tmp)){
										$contract_subject_id = $tmp[$clsProperty->pkey];
									} else {
										$contract_subject_id = $clsProperty->getMaxId();
										$clsProperty->insert(array(
											$clsProperty->pkey => $contract_subject_id,
											'property_type' => '_CONTRACT_SUBJECT',
											'title' => $contract_subject,
											'slug' => $core->replaceSpace($contract_subject),
											'order_no' => $clsProperty->getMaxorderNo(),
											'user_id' => 0,
											'user_id_update' => 0,
											'reg_date' => time(),
											'upd_date' => time()
										));
									}
									$more_information_stock["contract_subject_id"] = $contract_subject_id;
								}
								if(!empty($invest_fund_name)) {
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_INVEST_FUND' and `slug`='".$core->replaceSpace($invest_fund_name)."'", $clsProperty->pkey);
									if(!empty($tmp)){
										$invest_fund_id = $tmp[$clsProperty->pkey];
									} else {
										$invest_fund_id = $clsProperty->getMaxId();
										$clsProperty->insert(array(
											$clsProperty->pkey => $invest_fund_id,
											'property_type' => '_INVEST_FUND',
											'title' => $invest_fund_name,
											'slug' => $core->replaceSpace($invest_fund_name),
											'order_no' => $clsProperty->getMaxorderNo(),
											'user_id' => 0,
											'user_id_update' => 0,
											'reg_date' => time(),
											'upd_date' => time()
										));
									}
									$more_information_stock["invest_fund_id"] = $invest_fund_id;
								}
								if(!empty($sale_status_name)) {
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_SALE_STATUS' and `slug`='".$core->replaceSpace($sale_status_name)."'", $clsProperty->pkey);
									if(!empty($tmp)){
										$sale_status_id = $tmp[$clsProperty->pkey];
									} else {
										$sale_status_id = $clsProperty->getMaxId();
										$clsProperty->insert(array(
											$clsProperty->pkey => $sale_status_id,
											'property_type' => '_SALE_STATUS',
											'title' => $sale_status_name,
											'slug' => $core->replaceSpace($sale_status_name),
											'order_no' => $clsProperty->getMaxorderNo(),
											'user_id' => 0,
											'user_id_update' => 0,
											'reg_date' => time(),
											'upd_date' => time()
										));
									}
									$more_information_stock["sale_status_id"] = $sale_status_id;
								}
								if(!empty($agent_lock_name)) {
									$tmp = $clsProperty->getByCond("`property_type`='_AGENCY' and (`slug_vn`='".$core->replaceSpace($agent_lock_name)."' or `slug`='".$core->replaceSpace($agent_lock_name)."')", $clsProperty->pkey);
									if(!empty($tmp)){
										$agent_lock_id = $tmp[$clsProperty->pkey];
									} else {
										$agent_lock_id = $clsProperty->getMaxId();
										$clsProperty->insert(array(
											$clsProperty->pkey => $agent_lock_id,
											'property_type' => '_AGENCY',
											'title' => $agent_lock_name,
											'slug' => $core->replaceSpace($agent_lock_name),
											'order_no' => $clsProperty->getMaxorderNo(),
											'user_id' => 0,
											'user_id_update' => 0,
											'reg_date' => time(),
											'upd_date' => time()
										));
									}
									$more_information_stock["agent_lock_id"] = $agent_lock_id;
								}
								if(!empty($deposit_agent_name)) {
									$tmp = $clsProperty->getByCond("`property_type`='_AGENCY' and (`slug_vn`='".$core->replaceSpace($deposit_agent_name)."' or `slug`='".$core->replaceSpace($deposit_agent_name)."')", $clsProperty->pkey);
									if(!empty($tmp)){
										$deposit_agent_id = $tmp[$clsProperty->pkey];
									} else {
										$deposit_agent_id = $clsProperty->getMaxId();
										$clsProperty->insert(array(
											$clsProperty->pkey => $deposit_agent_id,
											'property_type' => '_AGENCY',
											'title' => $deposit_agent_name,
											'slug' => $core->replaceSpace($deposit_agent_name),
											'order_no' => $clsProperty->getMaxorderNo(),
											'user_id' => 0,
											'user_id_update' => 0,
											'reg_date' => time(),
											'upd_date' => time()
										));
									}
									$more_information_stock["deposit_agent_id"] = $deposit_agent_id;
								}
								if(!empty($bank_second_name)) {
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_BANK' and slug='".$core->replaceSpace($bank_second_name)."'", $clsProperty->pkey);
									if(!empty($tmp)){
										$bank_second_id = $tmp[$clsProperty->pkey];
									} else {
										$bank_second_id = $clsProperty->getMaxId();
										$clsProperty->insert(array(
											$clsProperty->pkey => $bank_second_id,
											'property_type' => '_BANK',
											'title' => $bank_second_name,
											'slug' => $core->replaceSpace($bank_second_name),
											'order_no' => $clsProperty->getMaxorderNo(),
											'user_id' => 0,
											'user_id_update' => 0,
											'reg_date' => time(),
											'upd_date' => time()
										));
									}
									$more_information_stock["bank_second_id"] = $bank_second_id;
								}
								if(!empty($bank_name)) {
									$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_BANK' and slug='".$core->replaceSpace($bank_name)."'", $clsProperty->pkey);
									if(!empty($tmp)){
										$bank_id = $tmp[$clsProperty->pkey];
									} else {
										$bank_id = $clsProperty->getMaxId();
										$clsProperty->insert(array(
											$clsProperty->pkey => $bank_id,
											'property_type' => '_BANK',
											'title' => $bank_name,
											'slug' => $core->replaceSpace($bank_name),
											'order_no' => $clsProperty->getMaxorderNo(),
											'user_id' => 0,
											'user_id_update' => 0,
											'reg_date' => time(),
											'upd_date' => time()
										));
									}
									$more_information_stock["bank_id"] = $bank_id;
								}
								if(!empty($home_direction_name)) {
									$tmp = $clsProperty->getByCond("`property_type`='_DIRECTION' and slug='".$core->replaceSpace($home_direction_name)."'", $clsProperty->pkey);
									if(!empty($tmp)) {
										$home_direction_id = $tmp[$clsProperty->pkey];
										$upd_field["home_direction_id"] = $home_direction_id;
										$more_information_stock["home_direction_id"] = $home_direction_id;
									}
								}
								if(!empty($TCBG)) {
									$more_information_stock["TCBG"] = $TCBG;
								}
								if(!empty($notes)) {
									$more_information_stock["notes"] = $notes;
								}
								if(!empty($csbh)) {
									$more_information_stock["csbh"] = $csbh;
								}
								if(!empty($DT_TT)) {
									$more_information_stock["DT_TT"] = $DT_TT;
								}
								if(!empty($DT_Tim)) {
									$more_information_stock["DT_Tim"] = $DT_Tim;
								}
								$price_temporary_ns = !empty($price_temporary_ns) ? $price_temporary_ns : $link_smartchip;
								$more_information_stock["price_temporary_ns"] = $price_temporary_ns;
								if(!empty($total_price_vat)){
									$upd_field['total_price_vat'] = $clsStock->getPriceOriginV2($total_price_vat);
								} else {
									if(!empty($total_price_early)){
										$upd_field['total_price_vat'] = $clsStock->getPriceOriginV2($total_price_early);
									}	
								}
								foreach ($v_stock as $p_field => $val) {
									if(in_array($p_field, array('total_price','total_price_vat','total_price_early'
										,'total_price_progress','total_price_bank','total_price_bank_30','total_price_bank_36'
										,'total_price_bank_12','total_price_bank_18'))){
										$price_tmp = !empty($val) ? $clsStock->getPriceOriginV2($val) : 0;								
										if((!isset($more_information_stock[$p_field]) || (!empty($more_information_stock[$p_field]) 
											&& $more_information_stock[$p_field] != $price_tmp))){
											$logs[$clsISO->getUniqid()] = array(
												'reg_date' => time(),
												'user_id' => $profile_id,
												'from_value' => $more_information_stock[$p_field],
												'to_value' => $price_tmp,
												'field' => $p_field,							
												'from' => '_front',
											);
											if(empty($check_log)) {
												$check_log = 1;
											}
										}
										$more_information_stock[$p_field] = $price_tmp;
									}
								}	
								$upd_field['upd_date'] = time();
								$upd_field['more_information'] = json_encode($more_information_stock, JSON_UNESCAPED_UNICODE);
								//	$clsStock->setDeBug(1);
								if($clsStock->updateOne($oneStock[$clsStock->pkey], $upd_field)){
									$total_updated += 1;
									$arr_upd[] = $ms_code;
									if(!empty($oneStockMeta) && !empty($logs) && !empty($check_log)){
										$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
											'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
											'upd_date' => time()
										));	
									}
								} else{
									$arr_not_upd[] = $ms_code;
								}
							}
						}
						if($total_updated > 0) {				
							#activity log	
							$clsActivityLog = new ActivityLog();
							$log = $clsActivityLog->addActivityLog("Stock","update");
						}
					} else {
						$field = "{$clsStock->pkey},`status_id`,`more_information`";
						$g_cond = "`stock_type`='".$stock_type."' AND `agency_id`='{$agency_id}' AND `project_id`='{$target_id}' AND (`status_id`>0 AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."')";
						if($target_id == 3) {
							$g_cond.= " AND `block_id`<>'10933'";
						}
						$list_stocks = $clsStock->getAll($g_cond, $field);
						if(!empty($list_stocks)){
							$total_updated = 0;
							foreach($list_stocks as $key => $val){
								$logs = array();
								$more_information = $val['more_information'];
								$more_information = $clsISO->to_array_json($more_information);
								$more_information['status_id'] = _STOCK_STATUS_SOLD_ID;
								$more_information['user_id_update_sold'] = $profile_id;
								
								$m_field = "{$clsStockMeta->pkey},logs";
								$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$val[$clsStock->pkey]."'", $m_field);
								if(!empty($oneStockMeta)){
									$logs = $oneStockMeta['logs'];
									$logs = $clsISO->to_array_json($logs);
								} else {
									$clsStockMeta->insert(array(
										'stock_id' => $val[$clsStock->pkey],
										'reg_date' => time(),
										'upd_date' => time()
									));
									$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$val[$clsStock->pkey]."'", $m_field);
								}
								$logs[$clsISO->getUniqid()] = array(
									'reg_date' => time(), 
									'user_id' => $profile_id,
									'from_id' => $val['status_id'],
									'to_id' => _STOCK_STATUS_SOLD_ID,
									'field' => 'status_id',								
									'from' => '_front',
								);
								if($clsStock->updateOne($val[$clsStock->pkey], array(
									'ms_date' => time(),
									'upd_date' => time(),
									'status_id' => _STOCK_STATUS_SOLD_ID,
									'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
								))) {
									++$total_stock_sold;
									$arr_upd[] = $ms_code;
									if(!empty($oneStockMeta) && !empty($logs)){
										$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
											'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
											'upd_date' => time()
										));	
									}
								}
							}
							if($total_updated > 0) {				
								#activity log		
								$clsActivityLog = new ActivityLog();
								$log = $clsActivityLog->addActivityLog("Stock","update");
							}
							unset($tmp);
						}
					}
					//log cập nhật
					$arr_ms_code_new = array_intersect($arr_ms_code_new,$arr_upd);
					$total_stock_new = !empty($arr_ms_code_new) ? count($arr_ms_code_new) : 0;					
					// $clsLogCrawl->insertLog('update', $stock_type, $block_id, $agency_id, $data, "_front");
					#log new
					$arr_data_upd = array(
						"total_stock_sold"	=>	$total_stock_sold,
						"total_stock_new"	=>	$total_stock_new,
						"total_stock"	=>	$total_updated,
						"stock_not_upd"	=>	$arr_not_upd,
						"data_log"	=>	$arr_data,
						"title_log"	=>	'Tổng quỹ: '.$total_updated.', Đã bán: '.$total_stock_sold.', Nhập mới: '.$total_stock_new,
						"type"	=>	1,	//0:tổng hợp,1:drive,2:hình ảnh,3:copy,
						"result_type"	=>	"update",	//change_field,copy_speadsheet,read_speadsheet
					);
					$clsLogCrawl->log($agency_id,$target_id, $arr_data_upd, $stock_type);
					#luu bang tam
					$clsTmpStockAgent->updateStockTmp($agency_id,$stock_type,$target_id,$ms_codes);			
					#tong hop quy dai ly
					/*$resStockCrawl = $clsStockAgent->updateStockCrawl($agency_id);*/
					// Start Logs 
					$stock_ids = !empty($lst_stock_id[$target_id]) ? $lst_stock_id[$target_id] : array();
//					$clsStockLog->setDeBug(1);
					$tmp = $clsStockLog->getByCond("`stock_type`='{$stock_type}' AND `agency_id`='{$agency_id}' AND `project_id`='{$target_id}' AND FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".date('d/m/Y')."'", $clsStockLog->pkey);
//					$clsISO->print_pre($tmp);die;
					if(!empty($tmp)){
						$clsStockLog->updateOne($tmp[$clsStockLog->pkey], array(
							'more_information' => json_encode($stock_ids, JSON_UNESCAPED_UNICODE)
						));
					} else {
						$clsStockLog->insert(array(
							'stock_type' => $stock_type,
							'agency_id' => $agency_id,
							'project_id' => $target_id,
							'more_information' => json_encode($stock_ids, JSON_UNESCAPED_UNICODE),
							'reg_date' => time(),
							'user_id' => $profile_id
						));
					}
					/** End */
					
				}else{
					echo json_encode(array(
						'result'	=>	false,
						'msg' => 'Lỗi đọc file dữ liệu'
					));	die();
				}
				
			}else{
				echo json_encode(array(
					'result'	=>	false,
					'msg' => 'Đại lý chưa có cấu hình dự án này'
				));	die();
			}
		}else{
			echo json_encode(array(
				'result'	=>	false,
				'msg' => 'Đại lý chưa có cấu hình dự án này'
			));	die();
		}
	}
	echo json_encode(array(
		'result'	=>	true,
		'msg' => 'Tổng quỹ: '.$total_updated.', Đã bán: '.$total_stock_sold.', Nhập mới: '.$total_stock_new,
		'total_updated' => $total_updated,
		'total_stock_sold' => $total_stock_sold,
		'total_stock_new' => $total_stock_new,
		'stock_not_upd' => $arr_not_upd,
		'arr_upd' => $arr_upd,
	));	die();
}
?>