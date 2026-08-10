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
function default_crawl_highfloor(){
	global $assign_list, $_CONFIG, $_SITE_ROOT, $mod, $act, $_LANG_ID, $oneSetting, $clsConfiguration,
	       $core, $clsModule, $clsButtonNav, $clsISO, $title_page, $description_page, $oneProfile, $profile_id, $dbconn;
           
	$clsProperty = new Property();
	$clsLogCrawl = new LogCrawl();
	$clsCrawl    = new Crawl();
	$clsStock    = new Stock();
	$clsProfile  = new Profile();
	$clsUser     = new User();
    
	$assign_list['clsProperty'] = $clsProperty;

	// 1. Lấy thông tin block_permiss
	$more_information_user = $oneProfile['more_information'] ?? '';
	$block_permiss = $core->get_field($more_information_user, 'block_permiss', []);
	$assign_list['block_permiss'] = $block_permiss;
	
	// 2. Xử lý Log 3 ngày gần nhất (Tối ưu vòng lặp & Hashing)
	$clsStockLog = new StockLog();
	$start_time = strtotime(date("d-m-Y 00:00:00", strtotime("-3 days")));
	$end_time   = strtotime(date("d-m-Y 23:59:59"));
//    $dbconn->debug=true;
	$lstStockLog = $clsStockLog->getAll("`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' AND `reg_date` BETWEEN {$start_time} AND {$end_time}");
//	$clsISO->print_pre($lstStockLog);die;
	$arrCheckHighFloor = [];
	if (!empty($lstStockLog)) {
		$arr_logHighFloor = [];
		foreach ($lstStockLog as $val) {
			$more_info = $clsISO->to_array_json($val['more_information'] ?? '');
			$arr_logHighFloor[$val["agency_id"]][$val["block_id"]][] = $more_info;
		}

		foreach ($arr_logHighFloor as $agency_id => $logBlock) {
			foreach ($logBlock as $block_id => $logs) {
				$in_use = 1;
				$count_logs = count($logs);
				if($count_logs > 1) {
					$in_use = 0;
					for($i = 1; $i < $count_logs; $i++) {
						if($clsISO->hashArray($logs[$i]) != $clsISO->hashArray($logs[$i-1])) {
							$in_use = 1;
							break; 
						}
					}
				}
				$arrCheckHighFloor[$agency_id][$block_id] = $in_use;
			}		
		}
	}

	// 3. Lấy danh sách đại lý (Agency)
	$list_agency = $clsProperty->getAll("`is_trash`=0 AND `is_locked`=0 AND `property_id`<>'"._AGENCY_FH_ID."' AND `property_type`='_AGENCY' ORDER BY `order_no` ASC", "{$clsProperty->pkey},title,more_information");
	
	// 4. Lấy tổng quỹ căn (Stock)
	$arr_block_stock = [];
	$lstTotal_stock = $clsStock->getAll("`is_trash`=0 AND `status_id`>0 AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."' AND `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' GROUP BY `block_id`,`agency_id`", "COUNT(`stock_id`) as `total_stock`, `block_id`, `agency_id`");
	
	if(!empty($lstTotal_stock)) {
		foreach ($lstTotal_stock as $val) {
			$arr_block_stock[$val["agency_id"]][$val['block_id']] = $val['total_stock'];
		}
	}

	// 5. Lấy danh sách phân khu (Blocks)
	$arr_block = $clsProperty->getArraySearchByKey("_BLOCK");
	$cond_block = "`parent_id`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' AND `property_type`='_BLOCK' AND (JSON_SEARCH(`more_information`,'one','1',NULL,'$.is_out_stock') IS NULL OR JSON_EXTRACT(`more_information`,'$.is_out_stock') = '0')";
	
	$listBlocksDB = $clsProperty->getAll($cond_block . " ORDER BY `for_id` ASC", "`{$clsProperty->pkey}`,`title`,JSON_EXTRACT(`more_information`,'$.on_sale') AS `on_sale`");
	
	$list_blocks = [];
	$arr_block_id = [];
	foreach ($listBlocksDB as $val) {
		if($val["on_sale"] == 1) {
			$list_blocks[$val[$clsProperty->pkey]] = $val['title'];
			$arr_block_id[] = $val[$clsProperty->pkey];
		}		
	}

    // ==========================================
    // 6. BATCH QUERY LOG CRAWL (TỐI ƯU JSON PARSING LÊN PHP)
    // ==========================================
    $one_week_ago = strtotime('-1 weeks');
    // Chỉ query 3 cột cần thiết, sắp xếp ASC (cũ đến mới)
    $all_logs_db = $clsLogCrawl->getAll("`stock_type` = '"._BLOCK_TYPE_HIGHLEVEL_SALE."' AND `date` >= {$one_week_ago} ORDER BY `date` ASC", "agency_id, date, description");
    
    $mapped_logs = [];
    if (!empty($all_logs_db)) {
        foreach ($all_logs_db as $log) {
            $agency_id_log = $log['agency_id'];
            
            // Decode chuỗi JSON
            $desc = $clsISO->to_array_json($log['description'] ?? '');
            
            // Kiểm tra tồn tại mảng "logs"
            if (!empty($desc['logs']) && is_array($desc['logs'])) {
                foreach ($desc['logs'] as $blk_id => $log_array) {
                    
                    if (is_array($log_array) && !empty($log_array)) {
                        // Lấy ra log cuối cùng trong mảng của block này (giống với end() ở code cũ của bạn)
                        $blk_log = end($log_array);
                        
                        // Gán vào $mapped_logs. 
                        // Vì SQL sắp xếp ASC, các dòng log mới ở DB sẽ tự động ghi đè (overwrite) dòng cũ.
                        $mapped_logs[$agency_id_log][$blk_id] = [
                            'time'        => $blk_log['time'] ?? $log['date'],
                            'result_type' => $blk_log['result_type'] ?? '',
                            '_from_site'  => $blk_log['_from_site'] ?? '',
                            'user_id'     => $blk_log['user_id'] ?? 0
                        ];
                    }
                }
            }
        }
    }
//	$clsISO->print_pre($mapped_logs);die;

	$totalStock = 0;
	$arr_total_block = [];
	$arr_cache_user = []; // Quản lý cache dữ liệu user để tránh query lại
    $is_super_admin = $clsISO->checkSupper();

	// 7. Xử lý Map Data (Vòng lặp thuần túy trên RAM, Không query DB)
	foreach ($list_agency as $key => $val) {
		$agency_id = $val["property_id"];
		$more_information = $clsISO->to_array_json($val['more_information'] ?? '');
		
		$block_not_upd = $more_information["block_not_upd"] ?? [];
		if($clsCrawl->isSubset_diff($arr_block_id, $block_not_upd)) {
			unset($list_agency[$key]);
			continue;
		}

		$block_crawl = $more_information['block_crawl'] ?? [];
		$list_agency[$key]['more_information'] = $more_information;
		$list_agency[$key]['spreadsheetId']    = $more_information["spreadsheetId"] ?? '';
		
		$total_dq = 0;
		$is_crawl = 0;

		foreach ($list_blocks as $block_id => $block_code) {
			if($is_super_admin || $clsISO->checkItemInArray($block_id, $block_permiss)) {
				
                // Gọi log từ mảng $mapped_logs đã load sẵn (O(1))
                $log = $mapped_logs[$agency_id][$block_id] ?? [];
                
				$total_stock = $arr_block_stock[$agency_id][$block_id] ?? 0;
				
				$block_crawl[$block_id]["time"] = !empty($log) ? $clsLogCrawl->getTimeAgo($log["time"]) : "--";
				$block_crawl[$block_id]["total_stock"] = $total_stock;
				
				$arr_total_block[$block_id] = ($arr_total_block[$block_id] ?? 0) + $total_stock;
				
				if(!empty($log)){
					$is_success = ($log["result_type"] == "update");
					$block_crawl[$block_id]["html_result"] = $is_success ? "<span class='text-success'>Thành công</span>" : "<span class='text-danger'>Thất bại</span>";
					$block_crawl[$block_id]["is_success"]  = $is_success ? 1 : 0;
				}
                
				$total_dq   += $total_stock;
				$totalStock += $total_stock;
				$block_crawl[$block_id]["link_stock"] = "/tool.html?project_id=".($arr_block[$block_id]["for_id"] ?? '')."&block_ids=".$block_id."&agency_ids=".$agency_id;
				
				if(!empty($block_crawl[$block_id]['is_crawl'])){
					$is_crawl = 1;
				}

				// Xử lý Profile User
				$pro_id = 0;
				if(!empty($log)) {
					if($log["_from_site"] == "_admin") {
                        $uid = $log["user_id"];
                        if (!isset($arr_cache_user['uid_'.$uid])) {
                            $oneUser = $clsUser->getOne($uid, "more_information");
                            $more_user = $clsISO->to_array_json($oneUser["more_information"] ?? '');
                            $arr_cache_user['uid_'.$uid] = $more_user["staff_permiss_id"] ?? 0;
                        }
                        $pro_id = $arr_cache_user['uid_'.$uid];
					} else {
						$pro_id = $log["user_id"] ?? 0;
					}
					
                    // Lấy LastName nếu chưa có cache
					if($pro_id && !isset($arr_cache_user['pro_'.$pro_id])) {
						$arr_cache_user['pro_'.$pro_id] = $clsProfile->getLastName($pro_id, 0, 0);
					}
					
					if($pro_id) {
						$block_crawl[$block_id]["user_upd"] = '<span class="cursor-pointer" data-url="/index.php?mod=home&act=load_profile_popover&user_id='.$pro_id.'" data-toggle="webui-popover" data-trigger="hover" data-width="350">'.($arr_cache_user['pro_'.$pro_id] ?? '').'</span>';
					}
				}

				$block_crawl[$block_id]["is_changed"] = (isset($arrCheckHighFloor[$agency_id][$block_id]) && $arrCheckHighFloor[$agency_id][$block_id] == 1) ? 0 : 1;
			} else {
				unset($list_blocks[$block_id]);
			}			
		}	

		$list_agency[$key]["order_no"]    = $is_crawl ? 1 : 2;
		$list_agency[$key]['block_crawl'] = $block_crawl;
		$list_agency[$key]['total_stock'] = $total_dq;
	}

	// 8. Sắp xếp lại danh sách đại lý theo order_no
	$total_sales_arrs = array_column($list_agency, 'order_no');
	array_multisort($total_sales_arrs, SORT_ASC, $list_agency);

	// 9. Gán ra view
	$assign_list["list_blocks"]     = $list_blocks;
	$assign_list["list_agency"]     = $list_agency;
	$assign_list["arr_total_block"] = $arr_total_block;
	$assign_list["totalStock"]      = $totalStock;
	
	/*============= Title & Description Page ==================*/
	$assign_list["title_page"]       = $title_page       = 'Cập nhật bảng hàng Excel cao tầng - '.PAGE_NAME;
	$assign_list["description_page"] = $description_page = ' Cập nhật bảng hàng Excel cao tầng - '.PAGE_NAME;
}
function default_crawl_highfloorOld(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act,$_LANG_ID,$oneSetting,$clsConfiguration,
	$core,$clsModule,$clsButtonNav,$clsISO,$title_page,$description_page,$oneProfile,$profile_id,$dbconn;
	$clsProperty = new Property();
	$clsLogCrawl = new LogCrawl();
	$clsCrawl = new Crawl();
	$clsStock = new Stock();
	$clsProfile = new Profile();
	$clsUser = new User();
	$assign_list['clsProperty'] = $clsProperty;
	// $clsISO->print_pre($oneProfile);die;
	$more_information_user = $oneProfile['more_information'];
	$block_permiss = $core->get_field($more_information_user, 'block_permiss', []);
	$assign_list['block_permiss'] = $block_permiss;
	
	$oneLog = $clsLogCrawl->getByCond("FROM_UNIXTIME(`date`,'%d/%m/%Y')='".date("d/m/Y")."' AND `agency_id`='242' AND `stock_type`='178'");
	###
	$clsStockLog = new StockLog();
	$start_time = strtotime("-3 days");
	$start_time = strtotime(date("d-m-Y 00:00:00",$start_time));
	$end_time = strtotime(date("d-m-Y 23:59:59"));
	if($clsISO->_DEV()){
//		$dbconn->debug=true;
	}
	$lstStockLog = $clsStockLog->getAll("`stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' AND `reg_date` BETWEEN {$start_time} AND {$end_time}");
	if($clsISO->_DEV()){
//		$clsISO->print_pre($lstStockLog);die;
	}
	$arr_logHighFloor = $arr_logLowFloor = [];
	foreach ($lstStockLog as $key => $val) {
		$more_information = $clsISO->to_array_json($val['more_information']);
		$arr_logHighFloor[$val["agency_id"]][$val["block_id"]][] = $more_information;
	}
	$arrCheckHighFloor = [];
	foreach ($arr_logHighFloor as $agency_id => $logBlock) {
		foreach ($logBlock as $block_id => $logs) {
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
			$arrCheckHighFloor[$agency_id][$block_id] = $in_use;
		}		
	}
//	$clsISO->print_pre($arrCheckHighFloor);
//	$clsISO->print_pre($lstStockLog);die;
	
	$list_agency = $clsProperty->getAll("`is_trash`=0 AND `is_locked`=0 AND `property_id`<>'"._AGENCY_FH_ID."' 
	AND `property_type`='_AGENCY' order by `order_no` ASC", "{$clsProperty->pkey},title,more_information");
	#tổng quỹ căn đại lý theo phân khu
	$arr_block_stock = [];
	$lstTotal_stock_block_agency = $clsStock->getAll("`is_trash`=0 AND `status_id`>0 AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."' AND `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' GROUP BY `block_id`,`agency_id`","COUNT(`stock_id`) as `total_stock`, `block_id`, `agency_id`");
	if(!empty($lstTotal_stock_block_agency)) {
		foreach ($lstTotal_stock_block_agency as $key => $val) {
			$arr_block_stock[$val["agency_id"]][$val['block_id']] = $val['total_stock'];
		}
	}
	$totalStock = 0;
	$arr_total_block = $arr_log = [];
	$arr_block = $clsProperty->getArraySearchByKey("_BLOCK");
//	$list_blocks = _PROJECT_BLOCK_CRAWL;
	$cond = "`parent_id`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' AND `property_type`='_BLOCK' AND (JSON_SEARCH(`more_information`,'one','1',NULL,'$.is_out_stock') IS NULL OR JSON_EXTRACT(`more_information`,'$.is_out_stock') = '0')";
	$lst_block = $arr_block_id = [];
	$listBlocks = $clsProperty->getAll($cond . " ORDER BY `for_id` ASC","`{$clsProperty->pkey}`,`title`,JSON_EXTRACT(`more_information`,'$.on_sale') AS `on_sale`");
	foreach ($listBlocks as $key => $val) {
		if($val["on_sale"] == 1) {
			$list_blocks[$val[$clsProperty->pkey]] = $val['title'];
			$arr_block_id[] = $val[$clsProperty->pkey];
		}		
	}
	$total = 0;
	$arr_cache_user = $arr_cache_user_admin = array();
	foreach ($list_agency as $key => $val) {
		$agency_id = $val["property_id"];
		$more_information = $clsISO->to_array_json($val['more_information']);
		$block_not_upd = !empty($more_information["block_not_upd"]) ? $more_information["block_not_upd"] : array();
		if($clsCrawl->isSubset_diff($arr_block_id,$block_not_upd)) {
			unset($list_agency[$key]);
			continue;
		}
		$block_crawl = !empty($more_information['block_crawl']) ? $more_information['block_crawl'] : array();
		$list_agency[$key]['more_information'] = $more_information;
		$list_agency[$key]['spreadsheetId'] = $more_information["spreadsheetId"];
		$total_dq = $is_crawl = $total_dont_upd = 0;
		foreach ($list_blocks as $block_id=>$block_code) {
			if($clsISO->checkItemInArray($block_id,$block_permiss) || $clsISO->checkSupper()) {
				$list_logs_json = $clsLogCrawl->getByCond("agency_id = '{$agency_id}'
					  AND stock_type = '"._BLOCK_TYPE_HIGHLEVEL_SALE."'
					  AND `date` >= '".strtotime('-1 weeks')."' 
					  AND JSON_CONTAINS_PATH(description,'one', CONCAT('$.logs.\"', ".$block_id.", '\"'))
					ORDER BY `date` DESC","JSON_EXTRACT(description, CONCAT('$.logs.\"', ".$block_id.", '\"')) AS log_array");
				$log_array = !empty($list_logs_json["log_array"]) ? $clsISO->to_array_json($list_logs_json["log_array"]) : array();
//				$log_array = !empty($arr_log[$agency_id][$block_id]) ? $arr_log[$agency_id][$block_id] : array();
//				$clsISO->print_pre($arr_log[$agency_id]);die;
				$log = !empty($log_array) ? end($log_array) : array();
				$total_stock = !empty($arr_block_stock[$agency_id][$block_id]) ? $arr_block_stock[$agency_id][$block_id] : 0;
				$block_crawl[$block_id]["time"] = !empty($log) ? $clsLogCrawl->getTimeAgo($log["time"]) : "--";
				$block_crawl[$block_id]["total_stock"] = $total_stock;
				if(isset($arr_total_block[$block_id])) {
					$arr_total_block[$block_id] += $total_stock;
				}else{
					$arr_total_block[$block_id] = $total_stock;
				}
				if(!empty($log)){
					$block_crawl[$block_id]["html_result"] = ($log["result_type"]=="update")?"<span class='text-success'>Thành công</span>" : "<span class='text-danger'>Thất bại</span>";
					$block_crawl[$block_id]["is_success"] = ($log["result_type"]=="update")? 1 : 0;
				}				
				$total_dq += $total_stock;
				$totalStock += $total_stock;
				$block_crawl[$block_id]["link_stock"] = "/tool.html?project_id=".$arr_block[$block_id]["for_id"]."&block_ids=".$block_id."&agency_ids=".$agency_id;
				if(!empty($block_crawl[$block_id]['is_crawl'])){
					$is_crawl = 1;
					++$total;
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
					$block_crawl[$block_id]["user_upd"] = '<span class="cursor-pointer" data-url="/index.php?mod=home&act=load_profile_popover&user_id='.$pro_id.'" data-toggle="webui-popover" data-trigger="hover" data-width="350">'.$arr_cache_user[$pro_id].'</span>';
				}
				unset($log);
//				var_dump($agency_id,$block_id);
//				$clsISO->print_pre($arrCheckHighFloor[$agency_id][$block_id]);
				$block_crawl[$block_id]["is_changed"] = (!empty($arrCheckHighFloor[$agency_id][$block_id]) && $arrCheckHighFloor[$agency_id][$block_id] == 1) ? 0 : 1;
			}else{
				unset($list_blocks[$block_id]);
			}			
		}	
		if(!empty($is_crawl)) {
			$list_agency[$key]["order_no"] = 1;
		}else{
			$list_agency[$key]["order_no"] = 2;
		}
		$list_agency[$key]['block_crawl'] = $block_crawl;
		$list_agency[$key]['total_stock'] = $total_dq;
	}
//	die;
	$total_sales_arrs = @array_column($list_agency, 'order_no');
	@array_multisort($total_sales_arrs, SORT_ASC, $list_agency);
	$assign_list["list_blocks"] = $list_blocks;
	$assign_list["list_agency"] = $list_agency;
	$assign_list["arr_total_block"] = $arr_total_block;
	$assign_list["totalStock"] = $totalStock;
	/*=============Title & Description Page==================*/
	$title_page = 'Cập nhật bảng hàng Excel cao tầng - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = ' Cập nhật bảng hàng Excel cao tầng - '.PAGE_NAME;
	$assign_list["description_page"] = $description_page;
}
function default_open_agency(){
	// ini_set('display_errors', '1');
	// ini_set('display_startup_errors', '1');
	// error_reporting(E_ALL);
	global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	$clsProperty = new Property();
	$smarty->assign('clsProperty',$clsProperty);
	###
	$uid = $clsISO->getUniqid();
	$agency_id = Input::post('agency_id', 0);
	$oneItem = $clsProperty->getOne($agency_id);
	$more_information = $clsISO->to_array_json($oneItem['more_information']);
	$block_crawl = !empty($more_information['block_crawl']) ? $more_information['block_crawl'] : array();
	$lst_block = [
		_PROJECT_BLOCK_MTS_ID	=>	"MTS",
		_PROJECT_BLOCK_MLS_ID	=>	"MLS",
		_PROJECT_BLOCK_MGA_ID	=>	"MGA",
		_PROJECT_BLOCK_LSB_ID	=>	"LSB",
	];
	// Output
	$smarty->assign('agency_id',$agency_id);
	$smarty->assign('uid',$uid);
	$smarty->assign('oneItem',$oneItem);
	$smarty->assign('lst_block',$lst_block);
	$smarty->assign('block_crawl',$block_crawl);
	// Return
	$html = $core->build('_ajax.open_agency.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
	)); die();
}
function default_save_agency(){
	global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	$clsSetting = new Setting();
	$clsProperty = new Property();
	###
	$agency_id = (int)Input::post("agency_id",0);
	$block_crawl = Input::post("block_crawl",array());
	$res = ["result"	=>	false,"msg"	=>	"error"];
	if(!empty($agency_id)) {
		$more_information = $clsProperty->getOneField('more_information', $agency_id);
		// $clsISO->print_pre($more_information); die();
		$more_information = $clsISO->to_array_json($more_information);
		$more_information['block_crawl'] = $block_crawl;
		if($clsProperty->updateOne($agency_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$res = ["result"	=>	true,"msg"	=>	"_success"];
		}
	}
	// Return
	echo json_encode($res); die();
}
function default_handle_status(){
	global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	$clsProperty = new Property();
	$smarty->assign('clsProperty',$clsProperty);
	###
	$agency_id = (int)Input::post("agency_id",0);
	$block_id = (int)Input::post("block_id",0);
	$is_crawl = (int)Input::post("is_crawl",0);
	$block_crawl = Input::post("block_crawl", array());
	$res = ["result" =>	false,"msg"	=>	"error"];
	if(!empty($agency_id) && !empty($block_id)) {
		$more_information = $clsProperty->getOneField('more_information', $agency_id);
		$more_information = $clsISO->to_array_json($more_information);
		$block_crawl = $core->get_field($more_information, "block_crawl", []);
		$block_crawl[$block_id]["is_crawl"] = $is_crawl;
		$more_information['block_crawl'] = $block_crawl;
		if($clsProperty->updateOne($agency_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$res = ["result"	=>	true,"msg"	=>	"_success"];
		}
	}
	// Return
	echo json_encode($res); die();
}
function default_crawl_agencyOld(){
	global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID,$profile_id,$clsConfiguration;
	ini_set('memory_limit', '7048M');
	set_time_limit(120);
	$clsCrawl = new Crawl();
	$clsProperty = new Property();
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	$clsStockLog = new StockLog();
	$clsLogCrawl = new LogCrawl();
	$clsStockAgent = new StockAgent();
	$clsTmpStockAgent = new TmpStockAgent();
	
	/*giá min max*/
	$field_config_price = $clsConfiguration->getValue('field_config_price');
	$field_config_price = $clsISO->to_array_json($field_config_price);
	$arr_price_min_max = [];
	if(!empty($field_config_price)) {
		foreach ($field_config_price as $key => $val) {
			if($val["stock_type"] == _BLOCK_TYPE_HIGHLEVEL_SALE) {
				$arr_price_min_max[$val["project_id"]][$val["block_id"]] = $val;
			}			
		}
	}
	/*end giá min max*/
	#- Required Library
	require_once (DIR_INCLUDES.'/json_master/autoload.php');				
	require_once (DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
	$decoder = new Webmozart\Json\JsonDecoder();
	$cachedFile = DIR_CACHE_JSON.'/crawl/stock/stock_sold_admin.json';
	$arrCacheSold = $decoder->decodeFile($cachedFile);
	
	$block_id = (int) Input::post("block_id", 0);
	$agency_id = (int) Input::post("agency_id", 0);
	$stock_type = (int) Input::post("stock_type", _BLOCK_TYPE_HIGHLEVEL_SALE);
	$oneAgency = $clsProperty->getByCond("`property_type`='_AGENCY' AND `property_id`='{$agency_id}' 
	AND JSON_SEARCH(`more_information`,'one','1',NULL,'$.block_crawl.*.is_crawl') IS NOT NULL");
	//	$clsISO->print_pre($oneAgency); die();
	
	#cache stock sold	
	$lstStock_sold = !empty($arrCacheSold[$stock_type][$agency_id][$block_id]) ? $arrCacheSold[$stock_type][$agency_id][$block_id] : array();
	
	$total_stock_sold = $total_stock_new = $total_updated = 0;
	$spreadsheetId_crawl = "";
	if(!empty($oneAgency) && !empty($block_id)) {
		/*giá min max*/
		$oneBlock = $clsProperty->getOne($block_id,"for_id");
		$min = $max = "";
		if(!empty($arr_price_min_max[$oneBlock["for_id"]])) {
			$price_min_max = isset($arr_price_min_max[$oneBlock["for_id"]][$block_id]) ? $arr_price_min_max[$oneBlock["for_id"]][$block_id] : $arr_price_min_max[$oneBlock["for_id"]][0];
		}
		$min = !empty($price_min_max["min"]) ? (int)$price_min_max["min"] : "";
		$max = !empty($price_min_max["max"]) ? (int)$price_min_max["max"] : "";
		/*end giá min max*/
		
		$more_information = $oneAgency['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$block_crawls = $core->get_field($more_information, "block_crawl", []);
		$stock_status_id = _STOCK_STATUS_LOCK_ID;
		if(isset($more_information['stock_status_id']) && !empty($more_information['stock_status_id'])){
			$stock_status_id = $more_information['stock_status_id'];
		}
		$arr_crawl = $arr_not_upd = $arr_upd = $arr_data = $arr_ms_code_new = $ms_codes = $lst_stock_id = array();
		$block_crawl = $block_crawls[$block_id];
		if(!empty($block_crawl["is_crawl"])) {
			$spreadsheetId = $block_crawl['sheetID'];
			$lst_range = $block_crawl['sheet_name'];
			if(!empty(trim($spreadsheetId)) && !empty($lst_range)) {
				$ranges = explode("|",$lst_range);
				$cachedName = sprintf('%s_%s.json', $oneAgency["title"], $block_id);				
				$arr_ranges = [];
				$range_column = '!A1:ZZ500';	
//				if($agency_id == 265 && $block_id == 10794) {
//					$range_column = '!A:Q';	
//				}
				$arr_cdt_not_update = [];
				/*if($agency_id != 10954 && $block_id == 10684){
					$lstMs_code = $clsStockAgent->getAll("`agency_id`='10954' AND `target_id`='$block_id' AND `stock_type`='{$stock_type}'");
					if(!empty($lstMs_code)) {
						foreach ($lstMs_code as $key => $val){
							$arr_cdt_not_update[] = $clsCrawl->getCodeNotTemplate($val["ms_code"]);
						}
					}
				}*/
				
				foreach ($ranges as $range) {
					$arr_ranges[] = $range . $range_column;					
				}
				$res = $clsCrawl->getDataNew($spreadsheetId,$ranges,$block_id,$agency_id,$stock_type);
				if(!empty($res["result"])) {
					$arr_data = !empty($res["tblData"]) ? $res["tblData"] : array();
					if(!empty($arr_data)) {
						$arr_data_code = $lstStock = array();
						$total_record = @count($arr_data);
						$arr_stock_code_not_in = $arr_stock_id_not_in = array();
						foreach ($arr_data as $key => $val) {
							$ms_code = $val['ms_code'];
							if (!empty($ms_code) && preg_match(REGEX_MS_CODE, $ms_code)) {
								$val['ms_code'] = $ms_code;
								$ms_code = $clsCrawl->getCodeNotTemplate($val["ms_code"]);								
								if(!$clsISO->checkItemInArray($ms_code,$arr_cdt_not_update)) {
									$arr_data_code[$ms_code] = $val;
								}						
							}
							unset($ms_code);
						}
						if(!empty($arr_data_code)) {
							$arr_code_old = [];
							$arr_ms_code = array_keys($arr_data_code);
							$str_code_in = implode("','",$arr_ms_code);
							$list_agency_stocks = $clsStock->getAll("`stock_type`='{$stock_type}' AND `block_id`='{$block_id}' AND (`agency_id`<>'"._AGENCY_FH_ID."' OR (`agency_id` = '"._AGENCY_FH_ID."' AND `status_id`='"._STOCK_STATUS_SOLD_ID."')) AND REPLACE(REPLACE(REPLACE(`ms_code`,'_',''),'-',''),'.','') IN ('".$str_code_in."')");
							if(!empty($list_agency_stocks)) {
								foreach ($list_agency_stocks as $k_stock => $oneStock) {								
									#
									$ms_code = $oneStock["ms_code"];
									if(!$clsISO->checkItemInArray($ms_code,$lstStock_sold)) {
										$arr_stock_id_not_in[] = $oneStock[$clsStock->pkey];	
										$ms_code = $clsCrawl->getCodeNotTemplate($ms_code);
										$arr_stock_code_not_in[] = $oneStock["ms_code"];
										$arr_data_code[$ms_code]["stock_id"] = $oneStock["stock_id"];
										$arr_data_code[$ms_code]["oneStock"] = $oneStock;
										if($oneStock['status_id'] == _STOCK_STATUS_LOCK_ID){
											$arr_code_old[] = $oneStock["ms_code"];
										}
										/*giá min max*/
										$arr_data_code[$ms_code]["min"] = $min;
										$arr_data_code[$ms_code]["max"] = $max;
									}
									/*end giá min max*/
								}
								unset($list_agency_stocks,$ms_code);
							}
							$arr_ms_code_new = array_diff($arr_ms_code,$arr_code_old);
							$total_stock_new = !empty($arr_ms_code_new) ? count($arr_ms_code_new) : 0;
						}else{
//							$clsLogCrawl->insertLog('change_field', $stock_type, $block_id, $agency_id, "File Gooogle Sheet đã bị thay đổi", "_front");
							$data = array(
								"title_log"	=>	'File Gooogle Sheet đã bị thay đổi',
								"type"	=>	1,	//0:tổng hợp,1:drive,2:hình ảnh,3:copy,
								"result_type"	=>	"change_field",	//change_field,copy_speadsheet,read_speadsheet
							);
							$clsLogCrawl->log($agency_id,$block_id, $data, $stock_type);
							echo json_encode(array(
								'result'	=>	false,
								'msg' => 'File Gooogle Sheet đã bị thay đổi. Hãy cập nhật lại cấu hình'
							));	die();
						}
						$lstStock = array_values($arr_data_code);
						// Cập nhật các căn thành đã bán
						$field = "{$clsStock->pkey},`ms_code`,`status_id`,`more_information`";
						$g_cond = "`agency_id`='{$agency_id}' and (`status_id`>0 and `status_id`<>'"._STOCK_STATUS_SOLD_ID."') 
						and `stock_type`='{$stock_type}' AND `block_id`='{$block_id}'";
						if(!empty($arr_stock_id_not_in)){
							$g_cond.= " and `stock_id` not in(".implode(',', $arr_stock_id_not_in).")";
						}
						$list_sold_stocks = $clsStock->getAll($g_cond, $field);
						// var_dump($list_sold_stocks); die;
						if(!empty($list_sold_stocks)){
							foreach($list_sold_stocks as $key => $val){
								$logs = array();
								$more_information = $val['more_information'];
								$more_information = $clsISO->to_array_json($more_information);
								###
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
								$more_information['status_id'] = _STOCK_STATUS_SOLD_ID;
								$more_information['user_id_update_sold'] = $profile_id;
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
									if(!empty($oneStockMeta)){
										$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
											'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
											'upd_date' => time()
										));	
									}
								}
							}
							unset($list_sold_stocks);
						}
						if(!empty($lstStock)) {
							foreach ($lstStock as $k_stock => $v_stock){
								$oneStock = $v_stock["oneStock"];
								$stock_id = $v_stock['stock_id'];
								$ms_code = $v_stock["ms_code"];
								if(!empty($oneStock)) {
									$ms_code = $oneStock["ms_code"];
									$ms_codes[] = $ms_code;
									$lst_stock_id[] = $stock_id;
								}
								$total_price = $core->get_price_field($v_stock, "total_price", 0);
								$total_price_vat = $core->get_price_field($v_stock, "total_price_vat", 0);
								$total_price_early = $core->get_price_field($v_stock, "total_price_early", 0);
								$total_price_progress = $core->get_price_field($v_stock, "total_price_progress", 0);
								$total_price_bank = $core->get_price_field($v_stock, "total_price_bank", 0);
								$total_price_bank_half = $core->get_price_field($v_stock, "total_price_bank_half", 0);
								#
								$csbh = $core->get_field($v_stock, "csbh", "");
								$price_sheet_link = $core->get_field($v_stock, "price_sheet_link", "");
								$link_smartchip = $core->get_field($v_stock, "link_smartchip", "");
								$price_sheet_title = $core->get_field($v_stock, "price_sheet_title", "PTG TẠM TÍNH");
								$DT_TT = $core->get_field($v_stock, "DT_TT", 0);
								$DT_Tim = $core->get_field($v_stock, "DT_Tim", 0);
								$date_deposit_sign = $core->get_field($v_stock, "date_deposit_sign", "");
								#
								$more_information_stock = $oneStock['more_information'];
								$more_information_stock = $clsISO->to_array_json($more_information_stock);
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
								$more_information_stock['ms_code'] = $ms_code;
								$more_information_stock['agency_id'] = $agency_id;
								if(!empty($DT_TT)) $more_information_stock['DT_TT'] = $DT_TT;
								if(!empty($csbh)) $more_information_stock['csbh'] = $csbh;
								if(!empty($date_deposit_sign)) $more_information_stock['date_deposit_sign'] = $date_deposit_sign;
								if(!empty($DT_Tim)) $more_information_stock['DT_Tim'] = $DT_Tim;
								if(!empty($total_price) && $total_price != $more_information_stock["total_price"]) {
									$more_information_stock['total_price'] = $total_price;
									$logs = $clsCrawl->renderArrayLog($logs,"total_price",$more_information_stock["total_price"],$total_price);
								}
								if(!empty($total_price) && $total_price != $more_information_stock["total_price"]) {
									$more_information_stock['total_price'] = $total_price;
									$logs = $clsCrawl->renderArrayLog($logs,"total_price",$more_information_stock["total_price"],$total_price);
								}
								if(!empty($total_price_vat)) {
									$upd_field['total_price_vat'] = $total_price_vat;
									$more_information_stock['total_price_vat'] = $total_price_vat;
									if($total_price_vat != $oneStock["total_price_vat"]) {
										$logs = $clsCrawl->renderArrayLog($logs,"total_price_vat",$oneStock["total_price_vat"],$total_price_vat);	
									}
								}else if(!empty($total_price) && empty($total_price_vat)){ // có giá chưa VAT và không có giá full VAT
									$total_price_vat = $total_price * _PERCENT_PRICE_VAT; // * 1.12
									$total_price_vat = round($total_price_vat);
									$upd_field['total_price_vat'] = $total_price_vat;
									$more_information_stock["total_price_vat"] = $total_price_vat;
									if($total_price_vat != $oneStock["total_price_vat"]) {
										$logs = $clsCrawl->renderArrayLog($logs,"total_price_vat",$oneStock["total_price_vat"],$total_price_vat);
									}
								}else if(!empty($total_price_early) && empty($total_price_vat)){
									$upd_field['total_price_vat'] = $total_price_early;
									if($total_price_early != $oneStock["total_price_vat"]) {
										$logs = $clsCrawl->renderArrayLog($logs,"total_price_vat",$oneStock["total_price_vat"],$total_price_early);
									}
								}
								if(!empty($total_price_early) && $total_price_early != $more_information_stock["total_price_early"]) {
									$more_information_stock['total_price_early'] = $total_price_early;
									$logs = $clsCrawl->renderArrayLog($logs,"total_price_early",$more_information_stock["total_price_early"],$total_price_early);
								}
								if(!empty($total_price_progress) && $total_price_progress != $more_information_stock["total_price_progress"]) {
									$more_information_stock['total_price_progress'] = $total_price_progress;
									$logs = $clsCrawl->renderArrayLog($logs,"total_price_progress",$more_information_stock["total_price_progress"],$total_price_progress);
								}
								if(!empty($total_price_bank) && $total_price_bank != $more_information_stock["total_price_bank"]) {
									$more_information_stock['total_price_bank'] = $total_price_bank;
									$logs = $clsCrawl->renderArrayLog($logs,"total_price_bank",$more_information_stock["total_price_bank"],$total_price_bank);
								}
								if(!empty($total_price_bank_half) && $total_price_bank_half != $more_information_stock["total_price_bank_half"]) {
									$more_information_stock['total_price_bank_half'] = $total_price_bank_half;
									$logs = $clsCrawl->renderArrayLog($logs,"total_price_bank_half",$more_information_stock["total_price_bank_half"],$total_price_bank_half);
								}
								if(!empty($agency_id)) {
									$upd_field['agency_id'] = $agency_id;
									$more_information_stock['agency_id'] = $agency_id;
									if($agency_id != $oneStock["agency_id"]){
										$logs = $clsCrawl->renderArrayLog($logs,"agency_id",$oneStock["agency_id"],$agency_id);	
									}									
								}
								if(!empty($stock_status_id)) {
									$upd_field['status_id'] = $stock_status_id;
									$more_information_stock['status_id'] = $stock_status_id;
									if($stock_status_id != $oneStock["status_id"]) {
										$logs = $clsCrawl->renderArrayLog($logs,"status_id",$oneStock["status_id"],$stock_status_id);	
									}									
								}
								###
								$sheets = $more = array();
								$price_sheets = $core->get_field($more_information_stock, 'price_sheets', []);
								if(!empty($price_sheet_title) && (!empty($price_sheet_link) || !empty($link_smartchip))){
									$price_sheet_link = !empty($price_sheet_link) ? $price_sheet_link : $link_smartchip;
									$sheets[$clsISO->getUniqid()] = array(
										'title' => $price_sheet_title,
										'image' => $price_sheet_link
									);
								}
								if(!empty($sheets)){
									$price_sheets = array();
									$price_sheet_id = $clsISO->getUniqid();
									$price_sheets[$price_sheet_id]['sheets'] = $sheets;
									$price_sheets[$price_sheet_id]['reg_date'] = time();
									$price_sheets[$price_sheet_id]['upd_date'] = time();
									$price_sheets[$price_sheet_id]['csbh'] = $csbh;
									$price_sheets[$price_sheet_id]['user_id'] = $profile_id;
									$price_sheets[$price_sheet_id]['user_update_id'] = $profile_id;
									$price_sheets[$price_sheet_id]['from'] = "_front";
								}
								$more_information_stock['price_sheets']= $price_sheets;
								$upd_field['upd_date'] = time();
								$upd_field['more_information'] = json_encode($more_information_stock, JSON_UNESCAPED_UNICODE);
								
								if($clsStock->updateOne($oneStock[$clsStock->pkey], $upd_field)){
									++$total_updated;
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
						$list_stocks = $clsStock->getAll("`stock_type`='".$stock_type."' AND `agency_id`='{$agency_id}' 
						AND `block_id`='{$block_id}' AND (`status_id`>0 AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."')", $field);
						if(!empty($list_stocks)){
							$total_updated = 0;
							foreach($list_stocks as $key => $val){
								$logs = array();
								$more_information = $val['more_information'];
								$more_information = $clsISO->to_array_json($more_information);
								$more_information['status_id'] = _STOCK_STATUS_SOLD_ID;
								$more_information['user_id_update_sold'] = $profile_id;
								##
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
									if(!empty($oneStockMeta)){
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
					$clsLogCrawl->log($agency_id,$block_id, $arr_data_upd, $stock_type);
														
					#luu bang tam
					$clsTmpStockAgent->updateStockTmp($agency_id,$stock_type,$block_id,$ms_codes);			
					#tong hop quy dai ly
					/*$resStockCrawl = $clsStockAgent->updateStockCrawl($agency_id);*/
					
					// Logs dự báo thay đổi file
					$logs_field = "{$clsStockLog->pkey},`more_information`";
					$tmp = $clsStockLog->getByCond("`stock_type`='{$stock_type}' AND `agency_id`='{$agency_id}' 
						AND `block_id`='{$block_id}' AND FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".date('d/m/Y')."'", $logs_field);
					if(!empty($tmp)){
						$clsStockLog->updateOne($tmp[$clsStockLog->pkey], array(
							'more_information' => json_encode($lst_stock_id, JSON_UNESCAPED_UNICODE)
						));
					} else {
						$clsStockLog->insert(array(
							'stock_type' => $stock_type,
							'agency_id' => $agency_id,
							'block_id' => $block_id,
							'more_information' => json_encode($lst_stock_id, JSON_UNESCAPED_UNICODE),
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
		'spreadsheetId_crawl' => $res["spreadsheetId_crawl"],
		'total_updated' => $total_updated,
		'total_stock_sold' => $total_stock_sold,
		'total_stock_new' => $total_stock_new,
		'stock_not_upd' => $arr_not_upd,
	));	die();
}
function default_crawl_agency() {
    global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID, $profile_id, $clsConfiguration, $dbconn;

    // Crawl đồng bộ qua HTTP là tác vụ dài → bỏ giới hạn thời gian PHP để chạy đến khi xong.
    // LƯU Ý: 504 là timeout của NGINX (fastcgi_read_timeout/proxy_read_timeout), không phải PHP —
    // phải tăng thêm ở cấu hình nginx mới hết 504. ignore_user_abort(true) giúp PHP vẫn ghi xong DB
    // dù nginx đã trả 504 / client ngắt kết nối.
	@set_time_limit(0);
    @ini_set('max_execution_time', '0');
    @ignore_user_abort(true);
    // @ini_set('memory_limit', '1024M'); // bật nếu gặp lỗi hết bộ nhớ

    // -------------------------------------------------------------------------
    // 1. GUARD CLAUSES: KIỂM TRA INPUT ĐẦU VÀO
    // -------------------------------------------------------------------------
    $block_id = (int) Input::post("block_id", 0);
    $agency_id = (int) Input::post("agency_id", 0);
    $stock_type = (int) Input::post("stock_type", _BLOCK_TYPE_HIGHLEVEL_SALE);

    if ($block_id <= 0 || $agency_id <= 0) {
        echo json_encode(['result' => false, 'msg' => 'Dữ liệu đầu vào không hợp lệ']);
        die();
    }
    $clsCrawlLocal = new CrawlLocal();
    $clsProperty = new Property();
    $clsStock = new Stock();
    $clsStockMeta = new StockMeta();
    $clsStockLog = new StockLog();
    $clsLogCrawl = new LogCrawl();
    $clsStockAgent = new StockAgent();
    $clsTmpStockAgent = new TmpStockAgent();

    // Lấy thông tin Agency
    $oneAgency = $clsProperty->getByCond("`property_type`='_AGENCY' AND `property_id`='{$agency_id}' 
        AND JSON_SEARCH(`more_information`,'one','1',NULL,'$.block_crawl.*.is_crawl') IS NOT NULL");

    if (empty($oneAgency)) {
        echo json_encode(['result' => false, 'msg' => 'Đại lý chưa có cấu hình dự án này']);
        die();
    }

    // -------------------------------------------------------------------------
    // 2. LOAD THƯ VIỆN & CACHE
    // -------------------------------------------------------------------------
    require_once(DIR_INCLUDES . '/json_master/autoload.php');
    require_once(DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
    
    $decoder = new Webmozart\Json\JsonDecoder();
    $cachedFile = DIR_CACHE_JSON . '/crawl/stock/stock_sold_admin.json';
    $arrCacheSold = file_exists($cachedFile) ? $decoder->decodeFile($cachedFile) : [];
    $lstStock_sold = $arrCacheSold[$stock_type][$agency_id][$block_id] ?? [];

    // Lấy cấu hình giá Min/Max
    $field_config_price = $clsISO->to_array_json($clsConfiguration->getValue('field_config_price'));
    $arr_price_min_max = [];
    if (!empty($field_config_price)) {
        foreach ($field_config_price as $val) {
            if ($val["stock_type"] == _BLOCK_TYPE_HIGHLEVEL_SALE) {
                $arr_price_min_max[$val["project_id"]][$val["block_id"]] = $val;
            }
        }
    }

    $oneBlock = $clsProperty->getOne($block_id, "for_id");
    $price_min_max = $arr_price_min_max[$oneBlock["for_id"]][$block_id] ?? ($arr_price_min_max[$oneBlock["for_id"]][0] ?? []);
    $min = !empty($price_min_max["min"]) ? (int)$price_min_max["min"] : "";
    $max = !empty($price_min_max["max"]) ? (int)$price_min_max["max"] : "";

    $more_information = $clsISO->to_array_json($oneAgency['more_information']);
    $block_crawl = $more_information["block_crawl"][$block_id] ?? [];
    $stock_status_id = $more_information['stock_status_id'] ?? _STOCK_STATUS_LOCK_ID;

    if (empty($block_crawl["is_crawl"]) || empty(trim($block_crawl['sheetID'])) || empty($block_crawl['sheet_name'])) {
        echo json_encode(['result' => false, 'msg' => 'Cấu hình Sheet không hợp lệ hoặc chưa bật crawl']);
        die();
    }

    // -------------------------------------------------------------------------
    // 3. ĐỌC DỮ LIỆU TỪ GOOGLE SHEET
    // -------------------------------------------------------------------------
    $spreadsheetId = $block_crawl['sheetID'];
    $ranges = explode("|", $block_crawl['sheet_name']);
    $range_column = '!A1:AC500';
    $arr_ranges = array_map(function($range) use ($range_column) { return $range . $range_column; }, $ranges);
	$res = $clsCrawlLocal->getDataNew($spreadsheetId,$ranges,$block_id,$agency_id,$stock_type);
	if($clsISO->_DEV()){
//		$clsISO->print_pre($res);die;
	}
    if (empty($res["result"]) || empty($res["tblData"])) {
        $clsLogCrawl->log($agency_id, $block_id, [
            "title_log"   => 'File Google Sheet đã bị thay đổi hoặc lỗi đọc dữ liệu',
            "type"        => 1,
            "result_type" => "change_field",
        ], $stock_type);
        echo json_encode(['result' => false, 'msg' => 'File Google Sheet đã bị thay đổi. Hãy cập nhật lại cấu hình']);
        die();
    }

    // -------------------------------------------------------------------------
    // 4. CHUẨN BỊ DỮ LIỆU (MAPPING & FILTERING)
    // -------------------------------------------------------------------------
    $arr_data = $res["tblData"];
    $arr_data_code = [];
    $arr_cdt_not_update = []; // Logic custom giữ nguyên của bạn

    foreach ($arr_data as $val) {
        $ms_code = $val['ms_code'] ?? '';
        if (!empty($ms_code) && preg_match(REGEX_MS_CODE, $ms_code)) {
            $ms_code_clean = $clsCrawlLocal->getCodeNotTemplate($ms_code);
            if (!$clsISO->checkItemInArray($ms_code_clean, $arr_cdt_not_update)) {
                $arr_data_code[$ms_code_clean] = $val;
            }
        }
    }

    $arr_ms_code = array_keys($arr_data_code);
    $str_code_in = implode("','", $arr_ms_code);
    
    // Truy vấn Stock hiện có trong DB
    $list_agency_stocks = $clsStock->getAll("`stock_type`='{$stock_type}' AND `block_id`='{$block_id}' 
        AND (`agency_id`<>'"._AGENCY_FH_ID."' OR (`agency_id` = '"._AGENCY_FH_ID."' AND `status_id`='"._STOCK_STATUS_SOLD_ID."')) 
        AND `mscode` IN ('".$str_code_in."')");

    $arr_stock_code_not_in = $arr_stock_id_not_in = $arr_code_old = [];
    
    if (!empty($list_agency_stocks)) {
        foreach ($list_agency_stocks as $oneStock) {
            $ms_code_raw = $oneStock["ms_code"];
            if (!$clsISO->checkItemInArray($ms_code_raw, $lstStock_sold)) {
                $arr_stock_id_not_in[] = $oneStock[$clsStock->pkey];
                $arr_stock_code_not_in[] = $ms_code_raw;
                
                $ms_code_clean = $clsCrawlLocal->getCodeNotTemplate($ms_code_raw);
                $arr_data_code[$ms_code_clean]["stock_id"] = $oneStock["stock_id"];
                $arr_data_code[$ms_code_clean]["oneStock"] = $oneStock;
                $arr_data_code[$ms_code_clean]["min"] = $min;
                $arr_data_code[$ms_code_clean]["max"] = $max;

                if ($oneStock['status_id'] == _STOCK_STATUS_LOCK_ID) {
                    $arr_code_old[] = $ms_code_raw;
                }
            }
        }
    }

    $arr_ms_code_new = array_diff($arr_ms_code, $arr_code_old);
    $lstStock = array_values($arr_data_code);
    $total_stock_sold = $total_stock_new = $total_updated = 0;
    $arr_not_upd = $arr_upd = $ms_codes = $lst_stock_id = [];

    // -------------------------------------------------------------------------
    // 5. CẬP NHẬT CÁC CĂN THÀNH ĐÃ BÁN (Xử lý hàng loạt - Pre-fetch Meta)
    // -------------------------------------------------------------------------
    // (Đã bỏ transaction bao quanh: vòng lặp chỉ ~32 căn nên không cần gom commit; transaction giữ
    // khoá dòng cả vòng → kết hợp ignore_user_abort + bấm lại nhiều lần dễ gây lock-wait ~50s → 504.)

    $g_cond = "`agency_id`='{$agency_id}' AND (`status_id`>0 AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."')
               AND `stock_type`='{$stock_type}' AND `block_id`='{$block_id}'";
    if (!empty($arr_stock_id_not_in)) {
        $g_cond .= " AND `stock_id` NOT IN (" . implode(',', $arr_stock_id_not_in) . ")";
    }
    
    $list_sold_stocks = $clsStock->getAll($g_cond, "{$clsStock->pkey}, `ms_code`, `status_id`, `more_information`");

    if (!empty($list_sold_stocks)) {
        // Pre-fetch Meta cho list sold
        $sold_stock_ids = array_column($list_sold_stocks, $clsStock->pkey);
        $sold_metas = $clsStockMeta->getAll("`stock_id` IN (" . implode(',', $sold_stock_ids) . ")", "{$clsStockMeta->pkey}, `stock_id`, `logs`");
        $sold_meta_map = [];
        if (!empty($sold_metas)) {
            foreach ($sold_metas as $meta) $sold_meta_map[$meta['stock_id']] = $meta;
        }

        foreach ($list_sold_stocks as $val) {
            $s_id = $val[$clsStock->pkey];
            $more_info = $clsISO->to_array_json($val['more_information']);
            $more_info['status_id'] = _STOCK_STATUS_SOLD_ID;
            $more_info['user_id_update_sold'] = $profile_id;

            $oneStockMeta = $sold_meta_map[$s_id] ?? null;
            $logs = !empty($oneStockMeta['logs']) ? $clsISO->to_array_json($oneStockMeta['logs']) : [];

            $logs[$clsISO->getUniqid()] = [
                'reg_date' => time(), 
                'user_id' => $profile_id,
                'from_id' => $val['status_id'],
                'to_id' => _STOCK_STATUS_SOLD_ID,
                'field' => 'status_id',
                'from' => '_front',
            ];

            if ($clsStock->updateOne($s_id, [
                'ms_date' => time(),
                'status_id' => _STOCK_STATUS_SOLD_ID,
                'more_information' => json_encode($more_info, JSON_UNESCAPED_UNICODE)
            ])) {
                ++$total_stock_sold;
                if ($oneStockMeta) {
                    $clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], [
                        'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
                        'upd_date' => time()
                    ]);
                } else {
                    $clsStockMeta->insert(['stock_id' => $s_id, 'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE), 'reg_date' => time(), 'upd_date' => time()]);
                }
            }
        }
    }

    // -------------------------------------------------------------------------
    // 6. CẬP NHẬT DỮ LIỆU TỪ SHEET (Khắc phục N+1 Query và Code thừa)
    // -------------------------------------------------------------------------
//	$clsISO->print_pre($lstStock);die;
//	$dbconn->debug=true;
    if (!empty($lstStock)) {
        // PRE-FETCH META CHO LIST STOCK TỪ SHEET
        $arr_update_ids = array_filter(array_column(array_column($lstStock, 'oneStock'), 'stock_id'));
        $meta_map = [];
        if (!empty($arr_update_ids)) {
            $all_metas = $clsStockMeta->getAll("`stock_id` IN (" . implode(',', $arr_update_ids) . ")", "{$clsStockMeta->pkey}, `stock_id`, `logs`");
            if (!empty($all_metas)) {
                foreach ($all_metas as $m) $meta_map[$m['stock_id']] = $m;
            }
        }

        foreach ($lstStock as $v_stock) {
            $oneStock = $v_stock["oneStock"] ?? null;
//			$clsISO->print_pre($oneStock);die;
            if (empty($oneStock)) continue;

            $stock_id = $oneStock['stock_id'];
            $ms_code = $oneStock["ms_code"];
            $ms_codes[] = $ms_code;
            $lst_stock_id[] = $stock_id;

            // Extract giá trị từ Sheet
            $prices = [
                'total_price' => $core->get_price_field($v_stock, "total_price", 0),
                'total_price_early' => $core->get_price_field($v_stock, "total_price_early", 0),
                'total_price_progress' => $core->get_price_field($v_stock, "total_price_progress", 0),
                'total_price_bank' => $core->get_price_field($v_stock, "total_price_bank", 0),
                'total_price_bank_half' => $core->get_price_field($v_stock, "total_price_bank_half", 0)
            ];
            $total_price_vat = $core->get_price_field($v_stock, "total_price_vat", 0);

            $more_info_stock = $clsISO->to_array_json($oneStock['more_information']);
            $more_info_stock['ms_code'] = $ms_code;
            $more_info_stock['agency_id'] = $agency_id;

            // Xử lý các trường cơ bản
            foreach (['DT_TT', 'csbh', 'date_deposit_sign', 'DT_Tim'] as $f) {
                $val = $core->get_field($v_stock, $f, "");
                if (!empty($val)) $more_info_stock[$f] = $val;
            }

            // Xử lý Logs từ Meta Map (Không query DB)
            $oneStockMeta = $meta_map[$stock_id] ?? null;
            $logs = !empty($oneStockMeta['logs']) ? $clsISO->to_array_json($oneStockMeta['logs']) : [];
			$check_log = 0;

            // Duyệt mảng cấu hình giá để Update & Log (Loại bỏ code if/else lặp lại)
            foreach ($prices as $key => $new_val) {
                if (!empty($new_val) && $new_val != ($more_info_stock[$key] ?? 0)) {
                    $old_val = $more_info_stock[$key] ?? 0;
                    $more_info_stock[$key] = $new_val;
                    $logs = $clsCrawlLocal->renderArrayLog($logs, $key, $old_val, $new_val);
					$check_log = 1;
                }
            }

            // Logic tính VAT
            if (empty($total_price_vat)) {
                if (!empty($prices['total_price'])) {
                    $total_price_vat = round($prices['total_price'] * _PERCENT_PRICE_VAT);
                } elseif (!empty($prices['total_price_early'])) {
//                    $total_price_vat = $prices['total_price_early'];
                }
            }

            $upd_field = ['upd_date' => time(), 'agency_id' => $agency_id];

            if (!empty($total_price_vat) && $total_price_vat != $more_info_stock["total_price_vat"]) {
                $upd_field['total_price_vat'] = $total_price_vat;
                $more_info_stock['total_price_vat'] = $total_price_vat;
                $logs = $clsCrawlLocal->renderArrayLog($logs, "total_price_vat", $more_info_stock["total_price_vat"], $total_price_vat);
				$check_log = 1;
            }

            // Status & Agency Logs
            if ($agency_id != $oneStock["agency_id"]) {
                $logs = $clsCrawlLocal->renderArrayLog($logs, "agency_id", $oneStock["agency_id"], $agency_id);
				$check_log = 1;
            }
            if (!empty($stock_status_id) && $stock_status_id != $oneStock["status_id"]) {
                $upd_field['status_id'] = $stock_status_id;
                $more_info_stock['status_id'] = $stock_status_id;
                $logs = $clsCrawlLocal->renderArrayLog($logs, "status_id", $oneStock["status_id"], $stock_status_id);
				$check_log = 1;
            }

            // Logic thêm Price Sheets
            $price_sheet_title = $core->get_field($v_stock, "price_sheet_title", "PTG TẠM TÍNH");
            $sheet_link = $core->get_field($v_stock, "price_sheet_link", "") ?: $core->get_field($v_stock, "link_smartchip", "");
            if (!empty($price_sheet_title) && !empty($sheet_link)) {
                $price_sheet_id = $clsISO->getUniqid();
                $more_info_stock['price_sheets'] = [
                    $price_sheet_id => [
                        'sheets' => [$clsISO->getUniqid() => ['title' => $price_sheet_title, 'image' => $sheet_link]],
                        'reg_date' => time(), 'upd_date' => time(), 'csbh' => $more_info_stock['csbh'] ?? "",
                        'user_id' => $profile_id, 'user_update_id' => $profile_id, 'from' => "_front"
                    ]
                ];
            }
            $upd_field['more_information'] = json_encode($more_info_stock, JSON_UNESCAPED_UNICODE);
			//$clsISO->print_pre($upd_field);die;
            // Execute Update
            if ($clsStock->updateOne($stock_id, $upd_field)) {
                ++$total_updated;
                $arr_upd[] = $ms_code;
                
                // Update or Insert Meta
				if(!empty($check_log)) {
					if ($oneStockMeta) {
						$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], ['logs' => json_encode($logs, JSON_UNESCAPED_UNICODE), 'upd_date' => time()]);
					} else {
						$clsStockMeta->insert(['stock_id' => $stock_id, 'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE), 'reg_date' => time(), 'upd_date' => time()]);
					}     
				}
            } else {
                $arr_not_upd[] = $ms_code;
            }
        }
        
        /*if ($total_updated > 0) {                
            (new ActivityLog())->addActivityLog("Stock", "update");
        }*/
    }
    // -------------------------------------------------------------------------
    // 7. GHI LOG TỔNG HỢP & HOÀN TẤT
    // -------------------------------------------------------------------------
    $arr_ms_code_new = array_intersect($arr_ms_code_new, $arr_upd);
    $total_stock_new = count($arr_ms_code_new);

    $clsLogCrawl->log($agency_id, $block_id, [
        "total_stock_sold" => $total_stock_sold,
        "total_stock_new"  => $total_stock_new,
        "total_stock"      => $total_updated,
        "stock_not_upd"    => $arr_not_upd,
        "data_log"         => $arr_data,
        "title_log"        => "Tổng quỹ: $total_updated, Đã bán: $total_stock_sold, Nhập mới: $total_stock_new",
        "type"             => 1,
        "result_type"      => "update",
    ], $stock_type);
                                        
    $clsTmpStockAgent->updateStockTmp($agency_id, $stock_type, $block_id, $ms_codes);            

    // Log dự báo thay đổi file
    $tmp = $clsStockLog->getByCond("`stock_type`='{$stock_type}' AND `agency_id`='{$agency_id}' 
        AND `block_id`='{$block_id}' AND FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".date('d/m/Y')."'", "{$clsStockLog->pkey}, `more_information`");
        
    if (!empty($tmp)) {
        $clsStockLog->updateOne($tmp[$clsStockLog->pkey], ['more_information' => json_encode($lst_stock_id, JSON_UNESCAPED_UNICODE)]);
    } else {
        $clsStockLog->insert([
            'stock_type' => $stock_type, 'agency_id' => $agency_id, 'block_id' => $block_id,
            'more_information' => json_encode($lst_stock_id, JSON_UNESCAPED_UNICODE),
            'reg_date' => time(), 'user_id' => $profile_id
        ]);
    }

    echo json_encode([
        'result'              => true,
        'msg'                 => "Tổng quỹ: $total_updated, Đã bán: $total_stock_sold, Nhập mới: $total_stock_new",
        'spreadsheetId_crawl' => $res["spreadsheetId_crawl"],
        'total_updated'       => $total_updated,
        'total_stock_sold'    => $total_stock_sold,
        'total_stock_new'     => $total_stock_new,
        'stock_not_upd'       => $arr_not_upd,
    ]);
    die();
}
function default_open_import_logs(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;
	$clsUser = new User();
	$clsLogCrawl = new LogCrawl();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	
	$agency_id = (int)Input::post('agency_id', 0);
	$target_id = (int)Input::post('target_id', 0);
	$stock_type = (int)Input::post('stock_type', _BLOCK_TYPE_HIGHLEVEL_SALE);
	$cond = "`agency_id`='{$agency_id}' AND `stock_type`='{$stock_type}'";
	$list_logs_json = $clsLogCrawl->getAll($cond. " AND JSON_VALID(`description`)" . " order by `date` DESC limit 0,20","JSON_UNQUOTE(JSON_EXTRACT(description, '$.logs.\"{$target_id}\"')) AS log_array");
	$list_logs = array();
	foreach ($list_logs_json as $key => $val) {
		if(!empty($val["log_array"])) {
			$logs = $clsISO->to_array_json($val["log_array"]);
			$list_logs = array_merge($list_logs,$logs);
		}		
	}
	$sort_log = @array_column($list_logs, 'time');
	@array_multisort($sort_log, SORT_DESC, $list_logs);
	$arr_cache_user_admin = $arr_cache_user = array();
	if(!empty($list_logs)){
		foreach($list_logs as $key => $val){
			$user_id = $val['user_id'];
			if($val["_from_site"] == "_admin") {
				if(!isset($arr_cache_user_admin[$user_id])) {
					$arr_cache_user_admin[$user_id] = $clsUser->getOne($user_id,"more_information");
				}
				$oneUser = $arr_cache_user_admin[$user_id];
				$more_user = $clsISO->to_array_json($oneUser["more_information"]);
				$pro_id = !empty($more_user["staff_permiss_id"]) ? $more_user["staff_permiss_id"] : 0;
			}else{
				$pro_id = !empty($user_id) ? $user_id : 0;
			}
			if(!isset($arr_cache_user[$pro_id])) {
				$arr_cache_user[$pro_id] = !empty($pro_id) ? $clsProfile->getFullName($pro_id) : "Hệ thống";
			}
			$list_logs[$key]['full_name'] = sprintf('%s', $arr_cache_user[$pro_id]);
			$type_name = "Link tổng hợp";
			if($val['type'] == 1) {
				$type_name = "BH đại lý";
			}else if($val["type"] == 2) {
				$type_name = "Hình ảnh";
			}else if($val["type"] == 3) {
				$type_name = "Copy/Paste";
			}
			$list_logs[$key]["type_name"] = $type_name;
			$description = $clsISO->to_array_json($val['description']);			
		}
	}
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	$smarty->assign('agency_id', $agency_id);
	$smarty->assign('list_logs', $list_logs);
	$smarty->assign('clsProperty', $clsProperty);
	//Return
	$smarty->assign('core', $core);
	$smarty->assign('clsISO', $clsISO);
	$html = $core->build('_ajax.open_import_logs.tpl');
	echo json_encode(array(
		'html' => $html,
		'uid'	=>	$uid
	)); die();
}
function default_start_import(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn,$smarty,$oneProfile;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsCrawl = new Crawl();
	$assign_list['clsProperty'] = $clsProperty;
	$smarty->assign("clsStock",$clsStock);
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	#
	$uid = $clsISO->getUniqid();
	$stock_type = (int) Input::post('stock_type', _BLOCK_TYPE_HIGHLEVEL_SALE);
	$target_id = (int) Input::post('target_id', 0);
	$agency_id = (int) Input::post('agency_id', 0);
	$spreadsheetId = Input::post('spreadsheetId');
	$lstBlockId = $clsStock->getAll("`stock_type`='{$stock_type}' AND (`status_id`<>'"._STOCK_STATUS_NON_ID."' AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."') GROUP BY `block_id`","`block_id`");
	$arr_block = $clsProperty->getArraySearchByKey("_BLOCK");
//	var_dump($arr_block);die;
//	$lstBlock = _PROJECT_BLOCK_CRAWL;
	$lstBlock = array();
	$more_information_user = $oneProfile['more_information'];
	$block_permiss = $core->get_field($more_information_user, 'block_permiss', []);
	foreach ($arr_block as $key => $val) {
		if($clsISO->checkItemInArray($val['property_id'],$block_permiss) && $val["parent_id"] == $stock_type) {
			$lstBlock[$val['property_id']] = $arr_block[$val['property_id']]["title"];
		}
	}
	#- Require library
	if(!empty(trim($spreadsheetId)) && !empty($target_id) && !empty($agency_id)) {
		$cachedFileColumn = DIR_CACHE_JSON.'/crawl/cache_column_highfloor.json';
		if(file_exists($cachedFileColumn)){
			$decoder = new Webmozart\Json\JsonDecoder();
			$lstColumn = $decoder->decodeFile($cachedFileColumn);
		}
		$arr_column = !empty($lstColumn[$agency_id]) ? $lstColumn[$agency_id] : array();
		$more_information = $clsProperty->getOneField("more_information",$agency_id);
		$more_information = $clsISO->to_array_json($more_information);
		$arr_column = !empty($arr_column) ? $arr_column : (!empty($more_information["columns"]) ? $more_information["columns"] : array());
		
		$oneAgency = $clsProperty->getOne($agency_id);
		$arr_data = $clsCrawl->getDataConfigColumn($spreadsheetId,["BH"],$target_id,$agency_id,$stock_type); 
		$tblData = array_values($arr_data["BH"]);
		$total_records = count($tblData);
		$cachedName = sprintf('%s.json', $uid);
		$cachedFile = DIR_CACHE_JSON.'/'.$cachedName;
		$encoder = new Webmozart\Json\JsonEncoder();
		$encoder->encodeFile($tblData, $cachedFile);
		$highestColumnIndex = 15;
		$widthColumn = 100/$highestColumnIndex;
		$smarty->assign("uid",$uid);
		$smarty->assign("column_data",$column_data);
		$smarty->assign("widthColumn",$widthColumn);
		$smarty->assign("agency_id",$agency_id);
		$smarty->assign("target_id",$target_id);
		$smarty->assign("stock_type",$stock_type);
		$smarty->assign("oneAgency",$oneAgency);
		$smarty->assign("highestColumnIndex",$highestColumnIndex);
		$smarty->assign("arr_data",$arr_data);
		$smarty->assign("tblData",$tblData);
		$smarty->assign("arr_column",$arr_column);
		$smarty->assign("lstBlock",$lstBlock);
		$html = $core->build("_ajax.start_crawl_stock.tpl");
		echo json_encode(array(
			'result'	=>	true,
			'uid' => $uid,
			'html' => $html
		)); die();
	}else{
		$res = array(
			"result"	=>	false,
			'msg' => "Vui lòng nhập đủ thông tin spreasheetID",
		);
	}
	echo json_encode($res); die();
}
function default_do_import(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$profile_id,$oneProfile
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;
	$user_id = $profile_id;
	$clsUser = new User();
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsStockLog = new StockLog();
	$clsLogCrawl = new LogCrawl();
	$clsCrawl = new Crawl();
	$clsTmpStockAgent = new TmpStockAgent();
	$clsStockAgent = new StockAgent();
	#
	$uid = Input::post('uid');
	$agency_id = (int) Input::post('agency_id', 0);
	$opt_ignore_empty = (int) Input::post('opt_ignore_empty', 1);
	$project_id = (int) Input::post('project_id', 0);
	$arr_blocks_ids = Input::post('block_id', []);
	$stock_type = (int) Input::post('stock_type', 0);
	$type = Input::post('type', "");
	$stock_type = (!empty($stock_type)) ? $stock_type : _BLOCK_TYPE_HIGHLEVEL_SALE;
	/*giá min max*/
	$field_config_price = $clsConfiguration->getValue('field_config_price');
	$field_config_price = $clsISO->to_array_json($field_config_price);
	$arr_price_min_max = [];
	if(!empty($field_config_price)) {
		foreach ($field_config_price as $key => $val) {
			if($val["stock_type"] == _BLOCK_TYPE_HIGHLEVEL_SALE) {
				$arr_price_min_max[$val["project_id"]][$val["block_id"]] = $val;
			}			
		}
	}
	/*end giá min max*/	
	$more_information_user = $oneProfile['more_information'];
	$block_permiss = $core->get_field($more_information_user, 'block_permiss', []);
	if(empty($block_permiss)) {
		echo json_encode(array(
			"result"	=>	false,
			"msg"		=>	"Tài khoản của bạn chưa được cấp quyền cập nhật bảng hàng"
		));die;
	}
//	var_dump($_POST);die;
	if(empty($arr_blocks_ids)){
		echo json_encode(array(
			"result"	=>	false,
			"msg"		=>	"Bạn chưa chọn phân khu cần cập nhật"
		));die;
	}
	#- Require library
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	$columns = Input::post('columns', array());
	$error_field = 0;
	if(!empty($columns)){
		$arr_fields = array();
		foreach($columns as $key => $p_field){
			if(!empty($p_field)){
				if(!in_array($p_field, $arr_fields)){
					$arr_fields[] = $p_field;
				} else {
					$error_field += 1;
				}
			} else {
				unset($columns[$key]);
			}
		}
		if(!in_array("ms_code", $arr_fields)){
			echo json_encode([
				'result'	=>	false,
				'msg'		=>	"Cột mã căn chưa được xác định"
			]); die();
		}
		if($error_field > 0){
			echo json_encode([
				'result'	=>	false,
				'msg'		=>	"Các cột dữ liệu không được trùng nhau"
			]); die();
		}
		#Lưu cấu hình cột
		if(empty($type)) {
			$cachedFile = DIR_CACHE_JSON.'/crawl/cache_column_highfloor.json';
			$arr_column = [];
			if(file_exists($cachedFile)){
				$decoder = new Webmozart\Json\JsonDecoder();
				$arr_column = $decoder->decodeFile($cachedFile);
			}
			$arr_column[$agency_id] = $columns;
			$encoder = new Webmozart\Json\JsonEncoder();
			$encoder->encodeFile($arr_column, $cachedFile); 	
		}
		
	}
	#- End require
	$tblData = array();
	$cachedName = sprintf('%s.json', $uid);
	$cachedFile = DIR_CACHE_JSON.'/'.$cachedName;
	if(file_exists($cachedFile)){
		$decoder = new Webmozart\Json\JsonDecoder();
		$tblData = $decoder->decodeFile($cachedFile);
		if($profile_id != 289){
			@unlink($cachedFile);		
		}		
	}
	if($profile_id == 289){
//		$clsISO->print_pre($tblData);die;
	}
	// Cập nhật lần chạy cron cuối cùng
	$more_information = $clsProperty->getOneField("more_information", $agency_id);
	$more_information = $clsISO->to_array_json($more_information);
	$stock_status_id = _STOCK_STATUS_LOCK_ID;
	if(isset($more_information['stock_status_id']) && !empty($more_information['stock_status_id'])){
		$stock_status_id = $more_information['stock_status_id'];
	}	
	if(empty($type)) {
		$more_information['columns'] = $columns;
	}
	$more_information['last_cronjob_time'] = time();
	$clsProperty->updateOne($agency_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	));
	// End
	$total_stock_sold = $total_stock_new = $total_updated = 0;	
	$sql_string = " AND `is_trash`=0"; $sql_block_permiss = "";
	if(!empty($arr_blocks_ids)) {
		$sql_block_permiss .= " and `block_id` IN (".implode(",",$arr_blocks_ids).") ";
	}
	$arr_tmp_stock = $lst_stock_id = [];
	foreach ($arr_blocks_ids as $block_id) {
		$arr_tmp_stock[$block_id] = [];
		$lst_stock_id[$block_id] = [];
	}
	if(!empty($tblData)){
		$total_record = @count($tblData);
		if($total_record > 1){
			$index = 0; 
			$list_stock_ids = $list_stock_agency_ids = array();
			$list_stock_ids = $arr_column = $arr_data_code = $lstStock = $lstStock_block = array();
			for($i=1; $i<$total_record; $i++){
				if(isset($tblData[$i][$index]) && !empty($tblData[$i][$index])){
					$arr_stock = [];
					foreach($columns as $i_col => $p_field){
						if($p_field == 'ms_code'){
							$ms_code = (string)preg_replace('/[^\p{L}\p{N}\.\-]+/u', '', addslashes(trim($tblData[$i][$i_col])));
							if (!empty($ms_code) && preg_match(REGEX_MS_CODE, $ms_code)) {
								$arr_stock["ms_code"] = $ms_code;
							}else{
								$ms_code = "";
							}
						}else if(!empty($columns[$i_col])) {
							$arr_stock[$p_field] = $tblData[$i][$i_col];
						}
					}
					if(!empty($ms_code)) {
						$arr_data_code[$clsCrawl->getCodeNotTemplate($ms_code)] = $arr_stock;											
					}			
					unset($ms_code);
				}
			}
			
			if(!empty($arr_data_code)) {
				$arr_code_old = [];
				$arr_ms_code = array_keys($arr_data_code);
				$str_code_in = @implode("','",$arr_ms_code);
				if($profile_id == 289){
//					$dbconn->debug=true;
				}
				$list_agency_stocks = $clsStock->getAll("`stock_type`='{$stock_type}' AND `block_id`='{$block_id}' AND (`agency_id`<>'"._AGENCY_FH_ID."' OR (`agency_id` = '"._AGENCY_FH_ID."' AND `status_id`='"._STOCK_STATUS_SOLD_ID."')) AND mscode IN ('".$str_code_in."')");
				
				if($profile_id == 289){
//					$clsISO->print_pre($list_agency_stocks);die;
				}
				if(!empty($list_agency_stocks)) {
					foreach ($list_agency_stocks as $k_stock => $oneStock) {
						$list_stock_ids[] = $oneStock[$clsStock->pkey];
						$arr_stock_code_not_in[] = $oneStock["ms_code"];
						$ms_code = $clsCrawl->getCodeNotTemplate($oneStock["ms_code"]);
						$arr_data_code[$ms_code]["stock_id"] = $oneStock["stock_id"];
						$arr_data_code[$ms_code]["oneStock"] = $oneStock;
						if($oneStock['status_id'] == _STOCK_STATUS_LOCK_ID){
							$arr_code_old[] = $oneStock["ms_code"];
						}
						$lstStock_block[$oneStock["block_id"]][] = $arr_data_code[$ms_code];
						unset($ms_code);
					}
					unset($list_agency_stocks);
				}
				$arr_ms_code_new = array_diff($arr_ms_code,$arr_code_old);
				$total_stock_new = !empty($arr_ms_code_new) ? count($arr_ms_code_new) : 0;
			}
			$arr_data = array_values($arr_data_code);
			if($profile_id == 289){
//				$clsISO->print_pre($lstStock_block);die;
			}
			// Cập nhật các căn thành đã bán
			$field = "{$clsStock->pkey},`ms_code`,`status_id`,`more_information`,`block_id`";
			$g_cond = "`agency_id`='{$agency_id}' and (`status_id`>0 and `status_id`<>'"._STOCK_STATUS_SOLD_ID."') 
			and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."'";
			if(!empty($list_stock_ids)){
				$g_cond.= " and `{$clsStock->pkey}` not in(".implode(',', $list_stock_ids).")";
			}
			$list_sold_stocks = $clsStock->getAll($g_cond.$sql_string.$sql_block_permiss, $field);
			// $clsISO->print_pre($list_sold_stocks); die();
			$arr_stock_sold_block = [];
			if(!empty($list_sold_stocks)){
				foreach($list_sold_stocks as $key => $val){
					$logs = array();
					$more_information = $val['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					##
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
					$more_information['status_id'] = _STOCK_STATUS_SOLD_ID;
					$more_information['user_id_update_sold'] = $profile_id;
					$logs[$clsISO->getUniqid()] = array(
						'reg_date' => time(), 
						'user_id' => $profile_id,
						'from_id' => $val['status_id'],
						'to_id' => _STOCK_STATUS_SOLD_ID,
						'field' => 'status_id'
					);
					if($clsStock->updateOne($val[$clsStock->pkey], array(
						'ms_date' => time(),
						'status_id' => _STOCK_STATUS_SOLD_ID,
						'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
					))){
						++$total_stock_sold;
						if(isset($arr_stock_sold_block[$val['block_id']]['total_stock_sold'])) {
							$arr_stock_sold_block[$val['block_id']]['total_stock_sold'] += 1;
						}else{
							$arr_stock_sold_block[$val['block_id']]['total_stock_sold'] = 1;
						}
						// Save Logs
						if(!empty($oneStockMeta)){
							$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
								'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
								'upd_date' => time()
							));	
						}
					}
				}
				unset($list_sold_stocks);
			}
			$total_updated = 0;
			$arr_block_cached = $clsProperty->getArraySearchByKey("_BLOCK");
			if($profile_id == 289){
//				ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
//				$dbconn->debug=true;
			}
			if(!empty($lstStock_block)) {
				foreach($lstStock_block as $block_id => $lstStock) {
					/*giá min max*/
					$oneBlock = !isset($arr_block_cached[$block_id]) ? $arr_block_cached[$block_id] : $clsProperty->getOne($block_id,"for_id");
					$min = $max = "";
					if(!empty($arr_price_min_max[$oneBlock["for_id"]])) {
						$price_min_max = isset($arr_price_min_max[$oneBlock["for_id"]][$block_id]) ? $arr_price_min_max[$oneBlock["for_id"]][$block_id] : $arr_price_min_max[$oneBlock["for_id"]][0];
					}
					$min = !empty($price_min_max["min"]) ? (int)$price_min_max["min"] : "";
					$max = !empty($price_min_max["max"]) ? (int)$price_min_max["max"] : "";
					/*end giá min max*/
					$total_updated_block = 0;
					if(!empty($lstStock)) {
						$arr_data_log = array();
						foreach ($lstStock as $k_stock => $v_stock){
							/*giá min max*/
							$v_stock["min"] = $min;
							$v_stock["max"] = $max;
							/*end giá min max*/
							$oneStock = $v_stock["oneStock"];
							$stock_id = $v_stock['stock_id'];
							$ms_code = $v_stock["ms_code"];
							$total_price = $core->get_price_field($v_stock, "total_price", 0);
							$total_price_vat = $core->get_price_field($v_stock, "total_price_vat", 0);
							$total_price_early = $core->get_price_field($v_stock, "total_price_early", 0);
							$total_price_progress = $core->get_price_field($v_stock, "total_price_progress", 0);
							$total_price_bank = $core->get_price_field($v_stock, "total_price_bank", 0);
							$total_price_bank_half = $core->get_price_field($v_stock, "total_price_bank_half", 0);
							#
							$csbh = $core->get_field($v_stock, "csbh", "");
							$DT_TT = $core->get_number_field($v_stock, "DT_TT", 0);
							$DT_Tim = $core->get_number_field($v_stock, "DT_Tim", 0);
							$date_deposit_sign = $core->get_field($v_stock, "date_deposit_sign", "");
							$price_sheet_link = $core->get_field($v_stock, "price_sheet_link", "");
							$price_sheet_title = $core->get_field($v_stock, "price_sheet_title", "PTG TẠM TÍNH");
							#
							$more_information_stock = $oneStock['more_information'];
							$more_information_stock = $clsISO->to_array_json($more_information_stock);
							#
							$logs = array(); $m_field = "{$clsStockMeta->pkey},`logs`";
							$check_log = 0;
							$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='{$stock_id}'", $m_field);
							if(!empty($oneStockMeta)){
								$logs = $oneStockMeta['logs'];
								$logs = $clsISO->to_array_json($logs);
							} else {
								$stock_meta_id = $clsStockMeta->getMaxId();
								$clsStockMeta->insert(array(
									$clsStockMeta->pkey => $stock_meta_id,
									'stock_id' => $stock_id,
									'reg_date' => time(),
									'upd_date' => time()
								));
								$oneStockMeta = [
									$clsStockMeta->pkey => $stock_meta_id,
									"logs"	=>	""
								];
							}							
							$upd_field['agency_id'] = $agency_id;
							$more_information_stock['ms_code'] = $ms_code;	
							$more_information_stock['agency_id'] = $agency_id;
							if(!empty($csbh)) $more_information_stock['csbh'] = $csbh;
							if(!empty($DT_TT)) $more_information_stock['DT_TT'] = $DT_TT;
							if(!empty($date_deposit_sign)) $more_information_stock['date_deposit_sign'] = $date_deposit_sign;
							if(!empty($DT_Tim)) {
								$more_information_stock['DT_Tim'] = $DT_Tim;
								$upd_field['DT_Tim'] = $DT_Tim;
							}
							if($opt_ignore_empty == 0){
								if(empty($total_price)){
									$more_information_stock["total_price"]= 0;
								}
								if(empty($total_price_vat)){
									$more_information_stock["total_price_vat"]= 0;
									$upd_field['total_price_vat'] = 0;
								}
								if(empty($total_price_early)){
									$more_information_stock["total_price_early"]= 0;
								}
								if(empty($total_price_progress)){
									$more_information_stock["total_price_progress"]= 0;
								}
								if(empty($total_price_bank)){
									$more_information_stock["total_price_bank"]= 0;
								}
								if(empty($total_price_bank_half)){
									$more_information_stock["total_price_bank_half"]= 0;
								}
							}
							if(!empty($total_price) && $total_price != $more_information_stock["total_price"]) {
								$more_information_stock['total_price'] = $total_price;
								$logs = $clsCrawl->renderArrayLog($logs,"total_price",$more_information_stock["total_price"],$total_price);
								$check_log = 1;
							}
							
							if(!empty($total_price_vat)) {
								$upd_field['total_price_vat'] = $total_price_vat;
								if($total_price_vat != $more_information_stock['total_price_vat']) {
									$logs = $clsCrawl->renderArrayLog($logs,"total_price_vat",$oneStock["total_price_vat"],$total_price_vat);	
									$check_log = 1;
								}
								$more_information_stock['total_price_vat'] = $total_price_vat;
							}else if(!empty($total_price) && empty($total_price_vat)){ // có giá chưa VAT và không có giá full VAT
								$total_price_vat = $total_price * _PERCENT_PRICE_VAT; // * 1.12
								$total_price_vat = round($total_price_vat);
								$upd_field['total_price_vat'] = $total_price_vat;
								if($total_price_vat != $more_information_stock["total_price_vat"]) {
									$logs = $clsCrawl->renderArrayLog($logs,"total_price_vat",$oneStock["total_price_vat"],$total_price_vat);
									$check_log = 1;
								}
								$more_information_stock["total_price_vat"] = $total_price_vat;
							}
							if(!empty($total_price_early) && $total_price_early != $more_information_stock["total_price_early"]) {
								$more_information_stock['total_price_early'] = $total_price_early;
								$logs = $clsCrawl->renderArrayLog($logs,"total_price_early",$more_information_stock["total_price_early"],$total_price_early);
								$check_log = 1;
							}
							if(!empty($total_price_progress) && $total_price_progress != $more_information_stock["total_price_progress"]) {
								$more_information_stock['total_price_progress'] = $total_price_progress;
								$logs = $clsCrawl->renderArrayLog($logs,"total_price_progress",$more_information_stock["total_price_progress"],$total_price_progress);
								$check_log = 1;
							}
							if(!empty($total_price_bank) && $total_price_bank != $more_information_stock["total_price_bank"]) {
								$more_information_stock['total_price_bank'] = $total_price_bank;
								$logs = $clsCrawl->renderArrayLog($logs,"total_price_bank",$more_information_stock["total_price_bank"],$total_price_bank);
								$check_log = 1;
							}
							if(!empty($total_price_bank_half) && $total_price_bank_half != $more_information_stock["total_price_bank_half"]) {
								$more_information_stock['total_price_bank_half'] = $total_price_bank_half;
								$logs = $clsCrawl->renderArrayLog($logs,"total_price_bank_half",$more_information_stock["total_price_bank_half"],$total_price_bank_half);
								$check_log = 1;
							}
							if(!empty($agency_id)) {
								$upd_field['agency_id'] = $agency_id;
								$more_information_stock['agency_id'] = $agency_id;
								if($agency_id != $oneStock["agency_id"]){
									$logs = $clsCrawl->renderArrayLog($logs,"agency_id",$oneStock["agency_id"],$agency_id);
									$check_log = 1;	
								}									
							}
							if(!empty($stock_status_id)) {
								$upd_field['status_id'] = $stock_status_id;
								$more_information_stock['status_id'] = $stock_status_id;
								if($stock_status_id != $oneStock["status_id"]) {
									$logs = $clsCrawl->renderArrayLog($logs,"status_id",$oneStock["status_id"],$stock_status_id);
									$check_log = 1;	
								}									
							}
							###
							$sheets = $more = array();
							$price_sheets = $core->get_field($more_information_stock, 'price_sheets', []);
							if(!empty($price_sheet_title) && !empty($price_sheet_link)){
								$sheets[$clsISO->getUniqid()] = array(
									'title' => $price_sheet_title,
									'image' => $price_sheet_link
								);
							}
							if(!empty($sheets)){
								$price_sheets = array();
								$price_sheet_id = $clsISO->getUniqid();
								$price_sheets[$price_sheet_id]['sheets'] = $sheets;
								$price_sheets[$price_sheet_id]['reg_date'] = time();
								$price_sheets[$price_sheet_id]['upd_date'] = time();
								$price_sheets[$price_sheet_id]['csbh'] = $csbh;
								$price_sheets[$price_sheet_id]['user_id'] = $profile_id;
								$price_sheets[$price_sheet_id]['user_update_id'] = $profile_id;
								$price_sheets[$price_sheet_id]['from'] = "_front";
							}
							$more_information_stock['price_sheets']= $price_sheets;
							$upd_field['upd_date'] = time();
							$upd_field['more_information'] = json_encode($more_information_stock, JSON_UNESCAPED_UNICODE);
							if($profile_id == 289){
//								$dbconn->debug=true;
							}
							if($clsStock->updateOne($oneStock[$clsStock->pkey], $upd_field)){
								++$total_updated;
								++$total_updated_block;
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
							$arr_data_log[] = [
								"ms_code"	=>	 $ms_code,
								"total_price_vat"=> $total_price_vat,
								"total_price_early"=> $total_price_early,
								"total_price_progress"=> $total_price_progress,
								"total_price_bank"=> $total_price_bank,
								"total_price_bank_half"=> $total_price_bank_half,
								"csbh"=> $csbh,
								"price_sheet_link"=> $price_sheet_link,
								"stock_id"=> $oneStock[$clsStock->pkey],
							];
							$arr_tmp_stock[$oneStock["block_id"]][] = $ms_code;
							$lst_stock_id[$oneStock["block_id"]][] = $oneStock[$clsStock->pkey];
							if($profile_id == 289){
//								die;
							}
						}
					}
					//log cập nhật
					$arr_ms_code = array_keys($arr_data_code);
					$arr_not_upd = array_diff($arr_ms_code,$arr_upd);
					$arr_ms_code_new = array_intersect($arr_ms_code_new,$arr_upd);
					$total_stock_new = !empty($arr_ms_code_new) ? count($arr_ms_code_new) : 0;
					$total_stock_sold = !empty($arr_stock_sold_block[$block_id]["total_stock_sold"]) 
						? $arr_stock_sold_block[$block_id]["total_stock_sold"] : 0;
					#log new
					$data = array(
						"total_stock_sold"	=>	$total_stock_sold,
						"total_stock_new"	=>	$total_stock_new,
						"total_stock"	=>	$total_updated_block,
						"stock_not_upd"	=>	$arr_not_upd,
						"data_log"	=>	$arr_data_log,
						"title_log"	=>	'Tổng quỹ: '.$total_updated.', Đã bán: '.$total_stock_sold.', Nhập mới: '.$total_stock_new,
						"type"	=>	0,	//0:tổng hợp,1:drive,2:hình ảnh,3:copy,
						"result_type"	=>	"update",	//change_field,copy_speadsheet,read_speadsheet
					);
					$clsLogCrawl->log($agency_id,$block_id, $data, $stock_type);
					#luu bang tam
					$clsTmpStockAgent->updateStockTmp($agency_id,$stock_type,$block_id,$ms_codes);	
				}
			}			
			if($total_updated > 0) {				
				#activity log		
				$clsActivityLog = new ActivityLog();
				$log = $clsActivityLog->addActivityLog("Stock","update");
			}else{
				#log new
				$data = array(
					"total_stock_sold"	=>	$total_stock_sold,
					"total_stock_new"	=>	0,
					"total_stock"	=>	0,
					"stock_not_upd"	=>	[],
					"data_log"	=>	[],
					"title_log"	=>	'Tổng quỹ: 0, Đã bán: '.$total_stock_sold.', Nhập mới: 0',
					"type"	=>	0,	//0:tổng hợp,1:drive,2:hình ảnh,3:copy,
					"result_type"	=>	"update",	//change_field,copy_speadsheet,read_speadsheet
				);
				$clsLogCrawl->log($agency_id,$block_id, $data, $stock_type);
				#luu bang tam
				$clsTmpStockAgent->updateStockTmp($agency_id,$stock_type,$block_id,[]);	
			}			
		}
	}else{
		echo 1;die;
	}
	#luu bang tam
	$logs_field = "{$clsStockLog->pkey},`more_information`";
	foreach ($arr_tmp_stock as $block_id => $ms_codes) {	
		$clsTmpStockAgent->updateStockTmp($agency_id,$stock_type,$block_id,$ms_codes);
		// Start Logs 
		$stock_ids = !empty($lst_stock_id[$block_id]) ? $lst_stock_id[$block_id] : array();
		$tmp = $clsStockLog->getByCond("`stock_type`='{$stock_type}' AND `agency_id`='{$agency_id}' AND `block_id`='{$block_id}' AND FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".date('d/m/Y')."'", $logs_field);
		if(!empty($tmp)){
			$clsStockLog->updateOne($tmp[$clsStockLog->pkey], array(
				'more_information' => json_encode($stock_ids, JSON_UNESCAPED_UNICODE)
			));
		} else {
			$clsStockLog->insert(array(
				'stock_type' => $stock_type,
				'agency_id' => $agency_id,
				'block_id' => $block_id,
				'more_information' => json_encode($stock_ids, JSON_UNESCAPED_UNICODE),
				'reg_date' => time(),
				'user_id' => $profile_id
			));
		}
		/** End */
	}		
	#tong hop quy dai ly
	/*$resStockCrawl = $clsStockAgent->updateStockCrawl($agency_id);*/
	// Return
	echo json_encode(array(
		'result'	=>	true,
		'msg' => 'Tổng quỹ: '.$total_updated.', Đã bán: '.$total_stock_sold.', Nhập mới: '.$total_stock_new,
		'total_updated' => $total_updated,
		'total_stock_sold' => $total_stock_sold,
		'total_stock_new' => $total_stock_new,
		'stock_not_upd' => $arr_not_upd,
	)); die();
}
function default_start_import_image(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO,$oneProfile,$profile_id;
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	$clsUser = new User();
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsCrawl = new Crawl();
	###
	$uid = $clsISO->getUniqid();
	$agency_id = (int) Input::post('agency_id', 0);
	$target_id = (int) Input::post('target_id', 0);
	$stock_type = (int) Input::post('stock_type', _BLOCK_TYPE_HIGHLEVEL_SALE);
	$type = Input::post('type', "_COPY");
	$data = $data_row = array();
	$lstBlockId = $clsStock->getAll("`stock_type`='{$stock_type}' AND ( `status_id`<>'"._STOCK_STATUS_NON_ID."' AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."') GROUP BY `block_id`","`block_id`");
	$arr_block = $clsProperty->getArraySearchByKey("_BLOCK");
	//	$lstBlock = _PROJECT_BLOCK_CRAWL;
	$lstBlock = array();
	$more_information_user = $oneProfile['more_information'];
	$block_permiss = $core->get_field($more_information_user, 'block_permiss', []);
	foreach ($lstBlockId as $key => $val) {
		if($clsISO->checkItemInArray($val['block_id'],$block_permiss)) {
			$lstBlock[$val['block_id']] = $arr_block[$val['block_id']]["title"];
		}
	}
	//	$arr_column = $clsCrawl->getArrayField($target_id,$agency_id);
	if($type == "_IMAGE") {
		if(empty($data_row)) {
			$arr_image = [];
			if(isset($_FILES['images']) && !empty($_FILES['images']['name'])) {
				for ($i=0; $i < count($_FILES['images']['name']); $i++) {
					$arr_image[$i]['name'] = $_FILES['images']['name'][$i];
					$arr_image[$i]['type'] = $_FILES['images']['type'][$i];
					$arr_image[$i]['tmp_name'] = $_FILES['images']['tmp_name'][$i];
				}
			}
			$curl = new Curl\Curl();			
			$curl->setHeaders(array(
				'Content-Type' => 'application/json'
			));
			if(!empty($arr_image)) {
				$tblData = [];				
				$clsUploadFile = new UploadFile();
				$clsGoogleDrive = new GoogleDrive();
				$clsCrawl = new Crawl();
				foreach ($arr_image as $image) {
					if(is_uploaded_file($image['tmp_name'])){
						$title = $image["name"];
						$mimeType = $image["type"];
						$image_upload = $clsUploadFile->uploadItem($image,"/crawl",EXTENSION_FILE_UPLOAD);
						if(!empty($image_upload) && file_exists(ROOTPATH . $image_upload)){
							$createdFile = $clsGoogleDrive->upload($title, $mimeType, ROOTPATH.$image_upload, GOOGLE_DRIVE_PTG_COPY_ID_DEV);
							$file_id = $createdFile->getId();
							$image = 'https://drive.google.com/file/d/'.$file_id.'/view';
							$tblData = $clsISO->getDataImage('https://n8n.futurehomes.vn/webhook/upload-excel-image',["image"=>$image]);
							$clsGoogleDrive->deleteFile($file_id);
							if(empty($tblData)) {
								echo json_encode(array(
									"result"	=>	false,
									"msg"		=>	"Không thể đọc được file ảnh"
								)); die();
							}
							@unlink(ROOTPATH . $image_upload);
						}
					}
					
					
				}
			}
//			$clsISO->print_pre($tblData);die;
//			die;
			$data = $data_row = array();
			if(!empty($tblData)) {
				foreach($tblData as $key => $value) {
					foreach ($value as $val) {
						$data[] = $val;
					}
				}
			}
			$highestColumnIndex = (count($data[0]) < 16) ? 16 : count($data[0]);
			if(count($data[0]) < $highestColumnIndex) {
				foreach ($data as $k => $val) {
					for($i = count($val); $i <= $highestColumnIndex; $i++) {
						$val[$i] = "";
					}
					$data[$k] = $val;
				}
			}
			$highestColumnIndex = count($data[0]) + 1;
			$dataHead = [];
			for($i=0; $i<$highestColumnIndex; $i++) {
				$data_null[] = "";
				$dataHead[] = [
					"type"	=>	"text",
					"title"	=>	'',
					"width"	=>	120
				];
			}
			for($i=0; $i<200; $i++) {
				$data_row[] = (!empty($data[$i])) ? $data[$i] : $data_null;
			}
//			$clsISO->print_pre($data_row);die;
			$cachedFile = sprintf(DIR_CACHE_JSON.'/crawl/image_%s.json',$agency_id);
			$encoder = new Webmozart\Json\JsonEncoder();
			$encoder->encodeFile($tblData, $cachedFile);
	//		var_dump($data_row);die;
		}
		$highestColumnIndex = count($tblData[0]) + 1;
		$dataHead = [];
		for($i=0; $i<$highestColumnIndex; $i++) {
			$dataHead[] = [
				"type"	=>	"text",
				"title"	=>	'',
				"width"	=>	120
			];
		}
	}else{		
		$highestColumnIndex = 16;
		$data = $data_row = array();
		$dataHead = [];
		for($i=0; $i<$highestColumnIndex; $i++) {
			$data[] = "";
			$dataHead[] = [
				"type"	=>	"text",
				"title"	=>	'',
				"width"	=>	120
			];
		}				
		$cachedName = sprintf('%s_%s.json', $project_admin_id,$agency_id);
		$cachedFile = DIR_CACHE_JSON.'/'.$cachedName;
		if(@file_exists($cachedFile)){
			$decoder = new Webmozart\Json\JsonDecoder();
			$tblData = $decoder->decodeFile($cachedFile);
		}else{
			for($i=0; $i<50; $i++) {
				$tblData[] = $data;
			}
		}
	}
	$cachedFileColumn = DIR_CACHE_JSON.'/crawl/cache_column_highfloor.json';
	if(file_exists($cachedFileColumn)){
		$decoder = new Webmozart\Json\JsonDecoder();
		$lstColumn = $decoder->decodeFile($cachedFileColumn);
	}
	$arr_column = !empty($lstColumn[$agency_id]) ? $lstColumn[$agency_id] : array();
	$more_information = $clsProperty->getOneField("more_information",$agency_id);
	$more_information = $clsISO->to_array_json($more_information);
	$arr_column = !empty($arr_column) ? $arr_column : $core->get_field($more_information, 'columns', []);
	//	$highestColumnIndex = 15;
	$widthColumn = 100/$highestColumnIndex;
	$smarty->assign("uid",$uid);
	$smarty->assign("type",$type);
	$smarty->assign("column_data",$column_data);
	$smarty->assign("widthColumn",$widthColumn);
	$smarty->assign("agency_id",$agency_id);
	$smarty->assign("target_id",$target_id);
	$smarty->assign("stock_type",$stock_type);
	$smarty->assign("oneAgency",$oneAgency);
	$smarty->assign("highestColumnIndex",$highestColumnIndex);
	$smarty->assign("arr_data",$arr_data);
	$smarty->assign("tblData",$tblData);
	$smarty->assign("arr_column",$arr_column);
	$smarty->assign("lstBlock",$lstBlock);
	$smarty->assign("clsStock",$clsStock);
	$html = $core->build("_ajax.start_crawl_stock.tpl");
	// Return
	echo json_encode(array(
		'result'	=>	true,
		'uid' => $uid,
		'html' => $html,
		'data'	=>	$tblData,
		'dataHead'	=>	$dataHead
	)); die();
}
function default_do_copy_agent(){
	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$profile_id,$oneProfile;
	$clsUser = new User();
	$clsStock = new Stock();
	$clsStockMeta = new StockMeta();
	$clsStockLog = new StockLog();
	$clsProperty = new Property();
	$clsLogCrawl = new LogCrawl();
	$clsCrawl = new Crawl();	
	$clsTmpStockAgent = new TmpStockAgent();
	$clsStockAgent = new StockAgent();
	/*giá min max*/
	$field_config_price = $clsConfiguration->getValue('field_config_price');
	$field_config_price = $clsISO->to_array_json($field_config_price);
	$arr_price_min_max = [];
	if(!empty($field_config_price)) {
		foreach ($field_config_price as $key => $val) {
			if($val["stock_type"] == _BLOCK_TYPE_HIGHLEVEL_SALE) {
				$arr_price_min_max[$val["project_id"]][$val["block_id"]] = $val;
			}			
		}
	}
	/*end giá min max*/
	###
	$uid = Input::post('uid');
	$agency_id = (int) Input::post('agency_id', 0);
	$project_id = (int) Input::post('project_id', 0);
	$arr_blocks_ids = Input::post('block_id', []);
	$opt_over = Input::post('opt_over','Update');
	$opt_fund = (int) Input::post('opt_fund', 0);
	$opt_ignore_empty = (int) Input::post('opt_ignore_empty', 0);
	$opt_update_ptg_only = (int) Input::post('opt_update_ptg_only', 0);
	$stock_type = (int) Input::post('stock_type', _BLOCK_TYPE_HIGHLEVEL_SALE);
	$type = Input::post('type', "_COPY");
	$columns = Input::post('columns', array());
	$tblData = Input::post('tblData',array());
	#- Require library
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	$error_field = 0;
	if(!empty($columns)){
		$arr_fields = array();
		foreach($columns as $key => $p_field){
			if(!empty($p_field)){
				if(!in_array($p_field, $arr_fields)){
					$arr_fields[] = $p_field;
				} else {
					$error_field += 1;
				}
			} else {
				unset($columns[$key]);
			}
		}		
		if(!in_array("ms_code", $arr_fields)){
			echo json_encode([
				'result'	=>	false,
				'msg'		=>	"Cột mã căn chưa được xác định"
			]);
			die();
		}
	}
	$more_information = $oneProfile['more_information'];
	$block_permiss = $core->get_field($more_information, 'block_permiss', []);
	if(empty($block_permiss)) {
		echo json_encode(array(
			"result"	=>	false,
			"msg"		=>	"Tài khoản của bạn chưa được cấp quyền cập nhật bảng hàng"
		));die;
	}
	if(empty($arr_blocks_ids)){
		echo json_encode(array(
			"result"	=>	false,
			"msg"		=>	"Bạn chưa chọn phân khu cần cập nhật"
		));die;
	}
	$index = 0;
	$lstStock_block = array();
	$total_record = count($tblData);
	for($i=0; $i<$total_record; $i++){
		if(isset($tblData[$i][$index]) && !empty($tblData[$i][$index])){
			$arr_stock = [];
			foreach($columns as $p_key => $p_field){
				if($p_field == 'ms_code'){
					$ms_code = (string) preg_replace('/[^\p{L}\p{N}\.\-]+/u', '', addslashes(trim($tblData[$i][$p_key])));
					if (!empty($ms_code) && preg_match(REGEX_MS_CODE, $ms_code)) {
						$arr_stock["ms_code"] = $ms_code;
					}else{
						$ms_code = "";
					}
				}else if(!empty($columns[$p_key])) {
					$arr_stock[$p_field] = $tblData[$i][$p_key];
				}
			}
			if(!empty($ms_code)) {
				$arr_data_code[$ms_code] = $arr_stock;						
			} 			
			unset($ms_code);
		}
	}
	foreach ($arr_blocks_ids as $block_id) {
		$lstStock_block[$block_id] = [];
	}
	if(!empty($arr_data_code)) {
		$arr_code_old = [];
		$arr_ms_code = array_keys($arr_data_code);
		$str_code_in = implode("','",$arr_ms_code);
		$list_agency_stocks = $clsStock->getAll("`stock_type`='".$stock_type."' AND `block_id` IN (".implode(',',$arr_blocks_ids).") AND (`agency_id`<>'"._AGENCY_FH_ID."' OR (`agency_id`='"._AGENCY_FH_ID."' AND `status_id`='"._STOCK_STATUS_SOLD_ID."')) AND `ms_code` IN ('".$str_code_in."')");
		if(!empty($list_agency_stocks)) {
			foreach($list_agency_stocks as $k_stock => $oneStock) {
				$list_stock_ids[] = $oneStock[$clsStock->pkey];
				$arr_stock_code_not_in[] = $oneStock["ms_code"];
				$arr_data_code[$oneStock["ms_code"]]["stock_id"] = $oneStock["stock_id"];
				$arr_data_code[$oneStock["ms_code"]]["oneStock"] = $oneStock;
				if($oneStock['status_id'] == _STOCK_STATUS_LOCK_ID){
					$arr_code_old[] = $oneStock["ms_code"];
				}
				$lstStock_block[$oneStock["block_id"]][] = $arr_data_code[$oneStock["ms_code"]];
			}
			unset($list_agency_stocks);
		}
		$arr_ms_code_new = array_diff($arr_ms_code,$arr_code_old);
		$total_stock_new = !empty($arr_ms_code_new) ? count($arr_ms_code_new) : 0;
	}	
	$type_log = 0;
	if($type == "_IMAGE"){
		$type_log = 2;
	}else if($type == "_COPY") {
		$type_log = 3;
	}
	$arr_data = array_values($arr_data_code);
	// Cập nhật lần chạy cron cuối cùng
	$more_information = $clsProperty->getOneField("more_information", $agency_id);
	$more_information = $clsISO->to_array_json($more_information);
	$stock_status_id = _STOCK_STATUS_LOCK_ID;
	if(isset($more_information['stock_status_id']) && !empty($more_information['stock_status_id'])){
		$stock_status_id = $more_information['stock_status_id'];
	}
	$more_information['last_cronjob_time'] = time();
	// Cập nhật cấu hình cột
	$clsProperty->updateOne($agency_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	));
	#
	$total_stock_sold = $total_stock_new = $total_updated = 0;	
	$sql_string = " AND `is_trash`=0"; $sql_block_permiss = "";
	if(!empty($arr_blocks_ids)) {
		$sql_block_permiss.= " AND `block_id` IN (".implode(",",$arr_blocks_ids).") ";
	}
	// Cập nhật các căn thành đã bán
	$field = "{$clsStock->pkey},`ms_code`,`status_id`,`more_information`,`block_id`";
	$g_cond = "`stock_type`='{$stock_type}' AND `agency_id`='{$agency_id}' and (`status_id`>0 and `status_id`<>'"._STOCK_STATUS_SOLD_ID."')";
	if(!empty($list_stock_ids)){
		$g_cond.= " AND `{$clsStock->pkey}` not in(".implode(',', $list_stock_ids).")";
	}
	$arr_stock_sold_block = [];
	$list_sold_stocks = $clsStock->getAll($g_cond.$sql_string.$sql_block_permiss, $field);
	if(!empty($list_sold_stocks)){
		foreach($list_sold_stocks as $key => $val){
			$block_id = (int) $val['block_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			#
			$logs = array();
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
			$more_information['status_id'] = _STOCK_STATUS_SOLD_ID;
			$more_information['user_id_update_sold'] = $profile_id;
			$logs[$clsISO->getUniqid()] = array(
				'reg_date' => time(), 
				'user_id' => $profile_id,
				'from_id' => $val['status_id'],
				'to_id' => _STOCK_STATUS_SOLD_ID,
				'field' => 'status_id'
			);
			if($clsStock->updateOne($val[$clsStock->pkey], array(
				'ms_date' => time(),
				'status_id' => _STOCK_STATUS_SOLD_ID,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			))){
				++$total_stock_sold;
				if(isset($arr_stock_sold_block[$block_id]['total_stock_sold'])) {
					$arr_stock_sold_block[$block_id]['total_stock_sold'] += 1;
				}else{
					$arr_stock_sold_block[$block_id]['total_stock_sold'] = 1;
				}
				if(!empty($oneStockMeta)){
					$clsStockMeta->updateOne($oneStockMeta[$clsStockMeta->pkey], array(
						'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
						'upd_date' => time()
					));	
				}
			}
		}
		unset($list_sold_stocks);
	}
	
	$arr_tmp_stock = $lst_stock_id = [];
	$total_updated = 0;
	if(!empty($lstStock_block)) {
		foreach($lstStock_block as $block_id => $lstStock) {			
			/*giá min max*/
			$oneBlock = $clsProperty->getOne($block_id,"for_id");
			$min = $max = "";
			if(!empty($arr_price_min_max[$oneBlock["for_id"]])) {
				$price_min_max = isset($arr_price_min_max[$oneBlock["for_id"]][$block_id]) ? $arr_price_min_max[$oneBlock["for_id"]][$block_id] : $arr_price_min_max[$oneBlock["for_id"]][0];
			}
			$min = !empty($price_min_max["min"]) ? (int)$price_min_max["min"] : "";
			$max = !empty($price_min_max["max"]) ? (int)$price_min_max["max"] : "";
			/*end giá min max*/
			$total_updated_block = 0;
			if(!empty($lstStock)) {
				foreach($lstStock as $k_stock => $v_stock){
					/*giá min max*/
					$v_stock["min"] = $min;
					$v_stock["max"] = $max;
					/*end giá min max*/
					$oneStock = $v_stock["oneStock"];
					$stock_id = $v_stock['stock_id'];
					$ms_code = $v_stock["ms_code"];
					$total_price = $core->get_price_field($v_stock, "total_price", 0);
					$total_price_vat = $core->get_price_field($v_stock, "total_price_vat", 0);
					$total_price_progress = $core->get_price_field($v_stock, "total_price_progress", 0);
					$total_price_early = $core->get_price_field($v_stock, "total_price_early", 0);
					$total_price_bank = $core->get_price_field($v_stock, "total_price_bank", 0);
					$total_price_bank_half = $core->get_price_field($v_stock, "total_price_bank_half", 0);
					#
					$csbh = $core->get_field($v_stock, "csbh", "");
					$price_sheet_link = $core->get_field($v_stock, "price_sheet_link", "");
					$price_sheet_title = $core->get_field($v_stock, "price_sheet_title", "PTG TẠM TÍNH");
					$DT_TT = $core->get_number_field($v_stock, "DT_TT", 0);
					$DT_Tim = $core->get_number_field($v_stock, "DT_Tim", 0);
					$date_deposit_sign = $core->get_field($v_stock, "date_deposit_sign", "");
					#
					$more_information_stock = $oneStock['more_information'];
					$more_information_stock = $clsISO->to_array_json($more_information_stock);
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
						$oneStockMeta = $clsStockMeta->getByCond("`stock_id`='".$stock_id."'", $m_field);
					}
					$upd_field['agency_id'] = $agency_id;
					$more_information_stock['ms_code'] = $ms_code;
					$more_information_stock['agency_id'] = $agency_id;
					if(!empty($csbh)) $more_information_stock['csbh'] = $csbh;
					if(!empty($DT_TT)) $more_information_stock['DT_TT'] = $DT_TT;
					if(!empty($date_deposit_sign)) $more_information_stock['date_deposit_sign'] = $date_deposit_sign;
					if(!empty($DT_Tim)) {
						$upd_field['DT_Tim'] = $DT_Tim;
						$more_information_stock['DT_Tim'] = $DT_Tim;
					}
					if($opt_ignore_empty == 0){
						if(empty($total_price)){
							$more_information_stock["total_price"]= 0;
						}
						if(empty($total_price_vat)){
							$more_information_stock["total_price_vat"]= 0;
							$upd_field['total_price_vat'] = 0;
						}
						if(empty($total_price_early)){
							$more_information_stock["total_price_early"]= 0;
						}
						if(empty($total_price_progress)){
							$more_information_stock["total_price_progress"]= 0;
						}
						if(empty($total_price_bank)){
							$more_information_stock["total_price_bank"]= 0;
						}
						if(empty($total_price_bank_half)){
							$more_information_stock["total_price_bank_half"]= 0;
						}
					}
					if(!empty($total_price) && $total_price != $more_information_stock["total_price"]) {
						$more_information_stock['total_price'] = $total_price;
						$logs = $clsCrawl->renderArrayLog($logs,"total_price",$more_information_stock["total_price"],$total_price);
					}
					if(!empty($total_price_vat)) {
						$upd_field['total_price_vat'] = $total_price_vat;
						$more_information_stock['total_price_vat'] = $total_price_vat;
						if($total_price_vat != $oneStock["total_price_vat"]) {
							$logs = $clsCrawl->renderArrayLog($logs,"total_price_vat",$oneStock["total_price_vat"],$total_price_vat);	
						}
					}else if(!empty($total_price) && empty($total_price_vat)){ // có giá chưa VAT và không có giá full VAT
						$total_price_vat = $total_price * _PERCENT_PRICE_VAT; // * 1.12
						$total_price_vat = round($total_price_vat);
						$upd_field['total_price_vat'] = $total_price_vat;
						$more_information_stock["total_price_vat"] = $total_price_vat;
						if($total_price_vat != $oneStock["total_price_vat"]) {
							$logs = $clsCrawl->renderArrayLog($logs,"total_price_vat",$oneStock["total_price_vat"],$total_price_vat);
						}
					}else if(!empty($total_price_early) && empty($total_price_vat)){
						$upd_field['total_price_vat'] = $total_price_early;
						if($total_price_early != $oneStock["total_price_vat"]) {
							$logs = $clsCrawl->renderArrayLog($logs,"total_price_vat",$oneStock["total_price_vat"],$total_price_early);
						}
					}
					if(!empty($total_price_early) && $total_price_early != $more_information_stock["total_price_early"]) {
						$more_information_stock['total_price_early'] = $total_price_early;
						$logs = $clsCrawl->renderArrayLog($logs,"total_price_early",$more_information_stock["total_price_early"],$total_price_early);
					}
					if(!empty($total_price_progress) && $total_price_progress != $more_information_stock["total_price_progress"]) {
						$more_information_stock['total_price_progress'] = $total_price_progress;
						$logs = $clsCrawl->renderArrayLog($logs,"total_price_progress",$more_information_stock["total_price_progress"],$total_price_progress);
					}
					if(!empty($total_price_bank) && $total_price_bank != $more_information_stock["total_price_bank"]) {
						$more_information_stock['total_price_bank'] = $total_price_bank;
						$logs = $clsCrawl->renderArrayLog($logs,"total_price_bank",$more_information_stock["total_price_bank"],$total_price_bank);
					}
					if(!empty($total_price_bank_half) && $total_price_bank_half != $more_information_stock["total_price_bank_half"]) {
						$more_information_stock['total_price_bank_half'] = $total_price_bank_half;
						$logs = $clsCrawl->renderArrayLog($logs,"total_price_bank_half",$more_information_stock["total_price_bank_half"],$total_price_bank_half);
					}
					if(!empty($agency_id)) {
						$upd_field['agency_id'] = $agency_id;
						$more_information_stock['agency_id'] = $agency_id;
						if($agency_id != $oneStock["agency_id"]){
							$logs = $clsCrawl->renderArrayLog($logs,"agency_id",$oneStock["agency_id"],$agency_id);	
						}									
					}
					if(!empty($stock_status_id)) {
						$upd_field['status_id'] = $stock_status_id;
						$more_information_stock['status_id'] = $stock_status_id;
						if($stock_status_id != $oneStock["status_id"]) {
							$logs = $clsCrawl->renderArrayLog($logs,"status_id",$oneStock["status_id"],$stock_status_id);	
						}									
					}
					###
					$sheets = $more = array();
					$price_sheets = $core->get_field($more_information_stock, 'price_sheets', []);
					if(!empty($price_sheet_title) && !empty($price_sheet_link)){
						$sheets[$clsISO->getUniqid()] = array(
							'title' => $price_sheet_title,
							'image' => $price_sheet_link
						);
					}
					if(!empty($sheets)){
						$price_sheets = array();
						$price_sheet_id = $clsISO->getUniqid();
						$price_sheets[$price_sheet_id]['sheets'] = $sheets;
						$price_sheets[$price_sheet_id]['reg_date'] = time();
						$price_sheets[$price_sheet_id]['upd_date'] = time();
						$price_sheets[$price_sheet_id]['csbh'] = $csbh;
						$price_sheets[$price_sheet_id]['user_id'] = $profile_id;
						$price_sheets[$price_sheet_id]['user_update_id'] = $profile_id;
						$price_sheets[$price_sheet_id]['from'] = "_front";
					}
					$more_information_stock['price_sheets']= $price_sheets;
					$upd_field['upd_date'] = time();
					$upd_field['more_information'] = json_encode($more_information_stock, JSON_UNESCAPED_UNICODE);
					// var_dump($upd_field,$more_information_stock,$logs);die;
					if($clsStock->updateOne($oneStock[$clsStock->pkey], $upd_field)){
						++$total_updated;
						++$total_updated_block;
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
					$arr_tmp_stock[$oneStock["block_id"]][] = $ms_code;
					$lst_stock_id[$oneStock["block_id"]][] = $oneStock[$clsStock->pkey];
				}
			}
			//log cập nhật
			$arr_ms_code = array_keys($arr_data_code);
			$arr_not_upd = array_diff($arr_ms_code,$arr_upd);
			$arr_ms_code_new = array_intersect($arr_ms_code_new,$arr_upd);
			$total_stock_new = !empty($arr_ms_code_new) ? count($arr_ms_code_new) : 0;
			$total_stock_sold = !empty($arr_stock_sold_block["block_id"]["total_stock_sold"]) ? $arr_stock_sold_block["block_id"]["total_stock_sold"] : 0;
			#log new
			$data = array(
				"total_stock_sold"	=>	$total_stock_sold,
				"total_stock_new"	=>	$total_stock_new,
				"total_stock"	=>	$total_updated_block,
				"stock_not_upd"	=>	$arr_not_upd,
				"data_log"	=>	$arr_data,
				"title_log"	=>	'Tổng quỹ: '.$total_updated_block.', Đã bán: '.$total_stock_sold.', Nhập mới: '.$total_stock_new,
				"type"	=>	$type_log,	//0:tổng hợp,1:drive,2:hình ảnh,3:copy,
				"result_type"	=>	"update",	//change_field,copy_speadsheet,read_speadsheet
			);
			$clsLogCrawl->log($agency_id,$block_id, $data, $stock_type);
		}
	}
		
	$logs_field = "{$clsStockLog->pkey},`more_information`";
	foreach ($arr_tmp_stock as $block_id => $ms_codes) {	
		#luu bang tam
		$clsTmpStockAgent->updateStockTmp($agency_id,$stock_type,$block_id,$ms_codes);
		// Start Logs 
		$stock_ids = !empty($lst_stock_id[$block_id]) ? $lst_stock_id[$block_id] : array();
		$tmp = $clsStockLog->getByCond("`stock_type`='{$stock_type}' AND `agency_id`='{$agency_id}' AND `block_id`='{$block_id}' AND FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".date('d/m/Y')."'", $logs_field);
		if(!empty($tmp)){
			$clsStockLog->updateOne($tmp[$clsStockLog->pkey], array(
				'more_information' => json_encode($stock_ids, JSON_UNESCAPED_UNICODE)
			));
		} else {
			$clsStockLog->insert(array(
				'stock_type' => $stock_type,
				'agency_id' => $agency_id,
				'block_id' => $block_id,
				'more_information' => json_encode($stock_ids, JSON_UNESCAPED_UNICODE),
				'reg_date' => time(),
				'user_id' => $profile_id
			));
		}
		/** End */
	}	
	#tong hop quy dai ly
	/*$resStockCrawl = $clsStockAgent->updateStockCrawl($agency_id);*/
	
	if($total_updated > 0) {				
		#activity log	
		$clsActivityLog = new ActivityLog();
		$log = $clsActivityLog->addActivityLog("Stock","update");
	}
	// Return
	echo json_encode(array(
		'result' =>	true,
		'msg' => 'Tổng quỹ: '.$total_updated.', Đã bán: '.$total_stock_sold.', Nhập mới: '.$total_stock_new,
		'total_updated' => $total_updated,
		'total_stock_sold' => $total_stock_sold,
		'total_stock_new' => $total_stock_new,
		'stock_not_upd' => $arr_not_upd,
	)); die();
}
function default_open_config_update_stock(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO,$oneProfile;
	###
	$clsProperty = new Property();
	$clsProject = new Project();
	$uid = $clsISO->getUniqid();
	$stock_type = (int) Input::post('stock_type', _BLOCK_TYPE_HIGHLEVEL_SALE);
	$list_agency = $clsProperty->getAll("`is_trash`=0 AND `is_locked`=0 AND `property_id`<>'"._AGENCY_FH_ID."' AND `property_type`='_AGENCY' order by `order_no` ASC", "{$clsProperty->pkey},title,more_information");
	foreach ($list_agency as $key => $val) {
		$more_information = $clsISO->to_array_json($val["more_information"]);
//		var_dump($more_information);die;
		if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE) {
			$arr_project_not_upd = !empty($more_information["project_not_upd"]) ? $more_information["project_not_upd"] : array();
			$list_agency[$key]["arr_project_not_upd"] = $arr_project_not_upd;
		}else{
			$arr_block_not_upd = !empty($more_information["block_not_upd"]) ? $more_information["block_not_upd"] : array();
			$list_agency[$key]["arr_block_not_upd"] = $arr_block_not_upd;
		}		
	}
	if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) {
		$cond = "`parent_id`='".$stock_type."' AND `property_type`='_BLOCK' AND JSON_SEARCH(`more_information`,'one','1',NULL,'$.on_sale') IS NOT NULL";
//		$clsProperty->setDeBug(1);
		$listBlocks = $clsProperty->getAll($cond . " ORDER BY `for_id` ASC",$clsProperty->pkey.',property_code');
//		var_dump($listBlocks);die;
		$smarty->assign("listBlocks",$listBlocks);
	}else if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE){
		$field = "{$clsProject->pkey},`code`";
		$cond_project = "`is_menu`='1' AND `list_block_type` LIKE '%|".$stock_type."|%'";		
		$lstProjects = $clsProject->getAll($cond_project." order by `reg_date` ASC", $field);
		$smarty->assign("lstProjects",$lstProjects);
	}
	$smarty->assign("list_agency",$list_agency);
	$smarty->assign("stock_type",$stock_type);
	$smarty->assign("uid",$uid);
	$html = $core->build("_ajax.open_config_update_stock.tpl");
	// Return
	echo json_encode(array(
		'result'	=>	true,
		'uid' => $uid,
		'html' => $html,
	)); die();
}
function default_save_config_update_stock(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsISO,$oneProfile;
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$stock_type = (int) Input::post('stock_type', _BLOCK_TYPE_HIGHLEVEL_SALE);
	
	$res = ["result"	=>	false,"msg"	=>	"_success"];
	$list_agency = $clsProperty->getAll("`is_trash`=0 AND `is_locked`=0 AND `property_id`<>'"._AGENCY_FH_ID."' AND `property_type`='_AGENCY' order by `order_no` ASC", "{$clsProperty->pkey},title,more_information");
	foreach ($list_agency as $key => $val) {
		$more_information = $clsISO->to_array_json($val["more_information"]);
		if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE) {			
			$project_not_upd = Input::post("project_not_upd_".$val[$clsProperty->pkey],array());
			$more_information["project_not_upd"] = $project_not_upd;
		}else{			
			$block_not_upd = Input::post("block_not_upd_".$val[$clsProperty->pkey],array());
			$more_information["block_not_upd"] = $block_not_upd;
		}
		$clsProperty->updateOne($val[$clsProperty->pkey], array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		));		
	}
	// Return
	echo json_encode(array(
		'result'	=>	true,
	)); die();
}
function default_report_crawl(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO;
	
	$list_preloaders = array();
	for($i=0; $i<100; $i++){
		$list_preloaders[] = $i;
	}
	$smarty->assign('list_preloaders', $list_preloaders);
	
	$current_year = date('Y');
	$current_month = date('m');
	$prev_year = ($current_year - 1);
	$assign_list['prev_year'] = $prev_year;
	$assign_list['current_year'] = $current_year;
	$assign_list['current_month'] = $current_month;
	###
	$start_year = 2023;
	$end_year = date('Y');
	$list_months = $list_years = array();
	for($i=1; $i<= date('m'); $i++){
		$list_months[] = $i;
	}
	for($i=$start_year; $i <= $end_year; $i++){
		$list_years[] = $i;
	}
	###
	$start_date = date("d/m/Y 00:00:00");
	$end_date =date("d/m/Y 23:59:59");
	$assign_list['start_date'] = $start_date;
	$assign_list['end_date'] = $end_date;
	$assign_list['list_months'] = $list_months;
	$assign_list['list_years'] = $list_years;
	
	#lst project	
	$clsProject = new Project();
	$lstProjects = $clsProject->getAll("`is_menu`='1' AND `list_block_type` LIKE '%|"._BLOCK_TYPE_LOWFLOOR_SALE."|%' order by `reg_date` ASC", $clsProject->pkey.",title");
	$assign_list['lstProjects'] = $lstProjects;
	#lst block
	$clsProperty = new Property();
	$listBlocks = $clsProperty->getAll("`parent_id`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' AND `property_type`='_BLOCK' AND (JSON_SEARCH(`more_information`,'one','1',NULL,'$.is_out_stock') IS NULL OR JSON_EXTRACT(`more_information`,'$.is_out_stock') = '0') AND JSON_SEARCH(`more_information`,'one','1',NULL,'$.on_sale') IS NOT NULL ORDER BY `for_id` ASC",$clsProperty->pkey.",title,for_id,property_code");
	$list_group_block = $arr_cache_project = [];
	foreach ($listBlocks as $key => $val) {
		if(!isset($arr_cache_project[$val["for_id"]])) {
			$oneProject = $clsProject->getOne($val['for_id'],"title,code");
			$arr_cache_project[$val["for_id"]] = "[".$oneProject["code"]."] ".$oneProject["title"];
			$list_group_block[$val["for_id"]]["title"] = $arr_cache_project[$val["for_id"]];
		}
		$list_group_block[$val["for_id"]]["listBlocks"][] = $val;
	}
	$assign_list['list_group_block'] = $list_group_block;
//	$assign_list['listBlocks'] = $listBlocks;
	/*=============Title & Description Page==================*/
	$title_page = 'Thống kê cập nhật bảng hàng - ' . PAGE_NAME;
	$assign_list["title_page"] = $title_page;
	$description_page = $title_page;
	$assign_list["description_page"] = $description_page;
	$keyword_page = $description_page;
	$assign_list["keyword_page"] = $keyword_page;
}
function default_ajax_get_total_report(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsStock = new Stock();
	$clsStockLog = new StockLog();
	$clsLogCrawl = new LogCrawl();
	$clsProperty = new Property();
	$helper = new Helper();
	$smarty->assign('clsOpsCost', $clsOpsCost);
	$smarty->assign('clsProperty', $clsProperty);
	##
	define('START_YEAR', 2025);
	$start_date = Input::post('start_date', "");
	$end_date = Input::post('end_date', "");
	$start_date = str_replace("/","-",$start_date);
	$end_date = str_replace("/","-",$end_date);
	$start_time = !empty($start_date) ? strtotime($start_date." 00:00:00") : strtotime(date("d-m-Y 00:00:00"));
	$end_time = !empty($end_date) ? strtotime($end_date." 23:59:59") : strtotime(date("d-m-Y 23:59:59"));
	##
	$cond = "`date` BETWEEN '{$start_time}' AND '{$end_time}'";
	$total_sold_highfloor = $total_new_highfloor = $total_sold_lowfloor = $total_new_lowfloor = $total_highfloor = $total_lowfloor = $total_block = $total_project = $total_agency_highfloor = $total_agency_lowfloor = 0;
//	$clsLogCrawl->setDeBug(1);
	$lstTotal_stock = $clsStock->getAll("(`status_id`='"._STOCK_STATUS_LOCK_ID."' || `status_id`='"._STOCK_STATUS_DQ_ID."')  AND `is_trash`=0 GROUP BY `stock_type` ","COUNT(`stock_id`) as total_stock, `stock_type`");
	foreach ($lstTotal_stock as $key => $val) {
		if($val["stock_type"] == _BLOCK_TYPE_LOWFLOOR_SALE) {
			$total_lowfloor = $val["total_stock"];
		}elseif($val["stock_type"] == _BLOCK_TYPE_HIGHLEVEL_SALE) {
			$total_highfloor = $val["total_stock"];
		}
	}
	$lstLogCrawl = $clsLogCrawl->getAll(" FROM_UNIXTIME(`date`,'%d/%m/%Y')='".date('d/m/Y')."'","description,stock_type,agency_id,date");
	$arr_project = $arr_block = $array_agency_highfloor = $array_agency_lowfloor = [];
	if(!empty($lstLogCrawl)) {
		foreach ($lstLogCrawl as $key => $val) {
			$description = $clsISO->to_array_json($val["description"]);
			$logs = !empty($description["logs"]) ? $description["logs"] : array();
			if(!empty($logs)) {
				if($val["stock_type"] == _BLOCK_TYPE_LOWFLOOR_SALE) {		
					if(!$clsISO->checkItemInArray($val['agency_id'],$array_agency_lowfloor)) {
						$array_agency_lowfloor[] = $val['agency_id'];
					}			
					foreach ($logs as $target_id => $log){				
						if(!$clsISO->checkItemInArray($target_id,$arr_project)) {
							$arr_project[] = $target_id;
						}
						$log = array_reverse($log);
						foreach ($log as $k_log => $v_log) {
							$total_stock_sold = !empty($v_log["total_stock_sold"]) ? (int)$v_log["total_stock_sold"] : 0;
							$total_stock_new = !empty($v_log["total_stock_new"]) ? (int)$v_log["total_stock_new"] : 0;
							$total_stock = !empty($v_log["total_stock"]) ? (int)$v_log["total_stock"] : 0;
							if($v_log["result_type"] == "update") {
								$total_sold_lowfloor += $total_stock_sold;
								$total_new_lowfloor += $total_stock_new;
								break;
							}
							unset($total_stock_sold,$total_stock_new,$total_stock);
						}
						unset($log);
					}					
				}elseif($val["stock_type"] == _BLOCK_TYPE_HIGHLEVEL_SALE) {
					if(!$clsISO->checkItemInArray($val['agency_id'],$array_agency_highfloor)) {
						$array_agency_highfloor[] = $val['agency_id'];
					}
					foreach ($logs as $target_id => $log){	
						if(!$clsISO->checkItemInArray($target_id,$arr_block)) {
							$arr_block[] = $target_id;
						}
						$log = array_reverse($log);
						foreach ($log as $k_log => $v_log) {
							$total_stock_sold = !empty($v_log["total_stock_sold"]) ? (int)$v_log["total_stock_sold"] : 0;
							$total_stock_new = !empty($v_log["total_stock_new"]) ? (int)$v_log["total_stock_new"] : 0;
							$total_stock = !empty($v_log["total_stock"]) ? (int)$v_log["total_stock"] : 0;
							if($v_log["result_type"] == "update") {
								$total_sold_highfloor += $total_stock_sold;
								$total_new_highfloor += $total_stock_new;
								break;
							}
							unset($total_stock_sold,$total_stock_new,$total_stock);
						}
						unset($log);	
					}
				}
			}
			unset($logs);
		}
	}
	$smarty->assign("total_highfloor",$total_highfloor);
	$smarty->assign("total_sold_highfloor",$total_sold_highfloor);
	$smarty->assign("total_new_highfloor",$total_new_highfloor);
	$smarty->assign("total_lowfloor",$total_lowfloor);
	$smarty->assign("total_sold_lowfloor",$total_sold_lowfloor);
	$smarty->assign("total_new_lowfloor",$total_new_lowfloor);
	$smarty->assign("total_block",count($arr_block));
	$smarty->assign("total_project",count($arr_project));
	$smarty->assign("total_agency_highfloor",count($array_agency_highfloor));
	$smarty->assign("total_agency_lowfloor",count($array_agency_lowfloor));
	$html = $core->build('_ajax.load_total_stock.tpl');
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_ajax_load_status_log_stock(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsProperty = new Property();
	$clsLogCrawl = new LogCrawl();
	$clsCrawl = new Crawl();
	$clsStock = new Stock();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsUser = new User();
	$assign_list['clsProperty'] = $clsProperty;
	#
	$_type = Input::post('_type', '_agency');
	$stock_type = (int) Input::get("stock_type",_BLOCK_TYPE_HIGHLEVEL_SALE);
	$time_type = Input::post('time_type', 'TODAY');
	/*$tmp = $clsISO->getRangeTime($time_type);
	$start_date = $tmp['start_date'];
	$due_date = $tmp['due_date'];*/
	$date = Input::post("date",date("Y-m-d"));
	//	$clsISO->print_pre($oneProfile);die;
	$more_information_user = $oneProfile['more_information'];
	$block_permiss = $core->get_field($more_information_user, 'block_permiss', []);
	$assign_list['block_permiss'] = $block_permiss;
	
	$list_agency = $clsProperty->getAll("`is_trash`=0 AND `is_locked`=0 AND `property_id`<>'"._AGENCY_FH_ID."' 
	AND `property_type`='_AGENCY' order by `order_no` ASC", "{$clsProperty->pkey},title,more_information");
	#tổng quỹ căn đại lý theo phân khu
	$group_by = "GROUP BY `block_id`,`agency_id`";
	$field = "COUNT(`stock_id`) as `total_stock`,`block_id` as `target_id`,`agency_id`";
	if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) {
		$group_by = "GROUP BY `project_id`,`agency_id`";
		$field = "COUNT(`stock_id`) as `total_stock`,`project_id` as `target_id`,`agency_id`";
	}
	
	$arr_total_block = $arr_total_project = [];
	$totalStock = 0;
	if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE) {
		# tổng quỹ căn đại lý theo dự án
		$lstTotal_stock_project_agency = $clsStock->getAll("`stock_type`='".$stock_type."' AND `status_id`>0 AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."' GROUP BY `project_id`,`agency_id` ","COUNT(`stock_id`) as `total_stock`, `project_id`, `agency_id`");
		$arr_project_stock = [];
		if(!empty($lstTotal_stock_project_agency)) {
			foreach ($lstTotal_stock_project_agency as $key => $val) {
				$arr_project_stock[$val["agency_id"]][$val['project_id']] = $val['total_stock'];
			}
		}
		
		$field = "{$clsProject->pkey},`code`,`title`,`more_information`";
		$cond_project = "`is_menu`='1' AND `list_block_type` LIKE '%|".$stock_type."|%'";		
		$lstProjects = $clsProject->getAll($cond_project." order by `reg_date` ASC", $field);
		$lst_project = $arr_project_id = array();
		foreach ($lstProjects as $key => $val) {
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
			
		$l_field = "agency_id,description";
		$list_logs_json = $clsLogCrawl->getAll("`stock_type`='{$stock_type}' AND FROM_UNIXTIME(`date`,'%Y-%m-%d')='".$date."'", $l_field);
		$arr_logs_json = array();
		if(!empty($list_logs_json)) {
			foreach ($list_logs_json as $key => $val) {
				$arr_logs_json[$val["agency_id"]][] = $val;
			}
		}
		$lstBlockTotalUpd = [];
		foreach ($list_agency as $key => $val) {
			$agency_id = $val["property_id"];
			$more_information = $clsISO->to_array_json($val['more_information']);
			$crawl_lowfloor = !empty($more_information['crawl_lowfloor']) ? $more_information['crawl_lowfloor'] : array();
			$project_not_upd = !empty($more_information["project_not_upd"]) ? $more_information["project_not_upd"] : array();
			if($clsCrawl->isSubset_diff($arr_project_id,$project_not_upd)) {
				unset($list_agency[$key]);
				continue;
			}
			$list_agency[$key]['more_information'] = $more_information;
			$list_agency[$key]['spreadsheetId'] = $more_information["spreadsheetId"];
			$total_dq = $is_crawl = $total_upd = $total_not_upd = $total_dont_upd = 0;
			foreach ($lst_project as $project_id=>$oProject) {
				$is_crawl = 1;
				$is_agency_not_upd = $is_agency_upd = 0;
				$total_upd_pro = $total_fail_pro = $total_success_pro = 0;
				if($clsISO->checkItemInArray($project_id,$project_not_upd) || empty($crawl_lowfloor[$project_id]["is_crawl"])) {
					$is_crawl = 0;
					++$total_dont_upd;
				}
				
				$total_stock = !empty($arr_project_stock[$agency_id][$project_id]) ? $arr_project_stock[$agency_id][$project_id] : 0;
				$crawl_lowfloor[$project_id]["total_stock"] = $total_stock;
				if(isset($arr_total_project[$project_id])) {
					$arr_total_project[$project_id] += $total_stock;
				}else{
					$arr_total_project[$project_id] = $total_stock;
				}
				$total_dq += $total_stock;
				$totalStock += $total_stock;
				
				$check_update = $check_not_upd = 0;
				if(!empty($is_crawl)) {	
					$array_total = [];
					if(!empty($arr_logs_json[$agency_id])) {
						$check = 0;
						foreach ($arr_logs_json[$agency_id] as $k_log => $val) {
							$description = !empty($val["description"]) ? $clsISO->to_array_json($val["description"]) : array();
							$lstlogs = !empty($description["logs"]) ? $description["logs"] : array();
							$logs = !empty($lstlogs[$project_id]) ? $lstlogs[$project_id] : array();	
							
							foreach ($logs as $k => $v) {
								++$total_upd_pro;
								if($v["result_type"]=="update") {
									++$total_success_pro;
								}else{
									++$total_fail_pro;
								}
							}
							if(!empty($logs) && $k_log == 0) {
								$logs = array_reverse($logs);
								$log = reset($logs);
								$crawl_lowfloor[$project_id]["title_log"] = ($log["result_type"]=="update")?"Thành công" : $log["title_log"];
								$crawl_lowfloor[$project_id]["is_success"] = ($log["result_type"]=="update")? 1 : 0;	
								$crawl_lowfloor[$project_id]["time"] = !empty($log) ? date("d/m/Y H:i",$log["time"]) : "";
								if($log["result_type"] == "update") {
									$check_update = 1;
									$is_agency_upd = 1;
									$check = 1;
								}else{
									$is_agency_not_upd = 1;
								}
								
							}elseif(empty($logs)){
								$crawl_lowfloor[$project_id]["title_log"] = "Chưa cập nhật";
								$is_agency_not_upd = 1;
							}							
						}
						if(empty($check)) {
							++$check_not_upd;
						}
					}else{
						$crawl_lowfloor[$project_id]["title_log"] = "Chưa cập nhật";
						$is_agency_not_upd = 1;
						$check_not_upd = 1;
					}
					unset($log,$isOverTime);	
				}
				#tổng cập nhật
				if(!empty($check_update)) {
					++$total_upd;					
				}
				#tổng chưa cập nhật
				if(!empty($check_not_upd)) {
					++$total_not_upd;					
				}
				#dự án đại lý cập nhật
				$total_agency_upd = !empty($lstBlockTotalUpd[$project_id]["agency_upd"]) ? $lstBlockTotalUpd[$project_id]["agency_upd"] : 0;
				if(!empty($is_agency_upd)) {
					++$total_agency_upd;
				}
				$lstBlockTotalUpd[$project_id]["agency_upd"] = $total_agency_upd;
				#dự án đại lý không cập nhật
				$total_agency_not_upd = !empty($lstBlockTotalUpd[$project_id]["agency_not_upd"]) ? $lstBlockTotalUpd[$project_id]["agency_not_upd"] : 0;
				if(!empty($is_agency_not_upd)) {
					++$total_agency_not_upd;
				}
				$lstBlockTotalUpd[$project_id]["agency_not_upd"] = $total_agency_not_upd;				
				unset($total_agency_not_upd);
				
				$crawl_lowfloor[$project_id]["total_upd"] = $total_upd_pro;
				$crawl_lowfloor[$project_id]["total_success"] = $total_success_pro;
				$crawl_lowfloor[$project_id]["total_fail"] = $total_fail_pro;
				$crawl_lowfloor[$project_id]["is_crawl"] = $is_crawl;	
				//				var_dump($is_crawl,$project_id,$agency_id);
				//				echo "======\n";
			}
			$list_agency[$key]['total_upd'] = $total_upd;
			$list_agency[$key]['total_not_upd'] = $total_not_upd;
			$list_agency[$key]['total_dont_upd'] = $total_dont_upd;			
			$list_agency[$key]['list_block'] = $crawl_lowfloor;	
			$list_agency[$key]['total_stock'] = $total_dq;	
			unset($more_information,$list_agency[$key]["more_information"],$crawl_lowfloor);
		}
		$smarty->assign("arr_total_project",$arr_total_project);
		$smarty->assign("totalStock",$totalStock);
		$smarty->assign("lst_project",$lst_project);
	}else{		
		$arr_block_stock = [];
		$lstTotal_stock_block_agency = $clsStock->getAll("`is_trash`=0 AND `status_id`>0 AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."' AND `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' GROUP BY `block_id`,`agency_id`","COUNT(`stock_id`) as `total_stock`, `block_id`, `agency_id`");
		if(!empty($lstTotal_stock_block_agency)) {
			foreach ($lstTotal_stock_block_agency as $key => $val) {
				$arr_block_stock[$val["agency_id"]][$val['block_id']] = $val['total_stock'];
			}
		}
//	$listBlocks = $clsProperty->getAll("`parent_id`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' AND `property_type`='_BLOCK' AND (JSON_SEARCH(`more_information`,'one','1',NULL,'$.is_out_stock') IS NULL OR JSON_EXTRACT(`more_information`,'$.is_out_stock') = '0') AND JSON_SEARCH(`more_information`,'one','1',NULL,'$.on_sale') IS NOT NULL ORDER BY `for_id` ASC",$clsProperty->pkey.",title,for_id");
		$cond = "`parent_id`='".$stock_type."' AND `property_type`='_BLOCK' AND (JSON_SEARCH(`more_information`,'one','1',NULL,'$.is_out_stock') IS NULL OR JSON_EXTRACT(`more_information`,'$.is_out_stock') = '0') AND JSON_SEARCH(`more_information`,'one','1',NULL,'$.on_sale') IS NOT NULL";
		$lst_block = $arr_block_id = [];
		$listBlocks = $clsProperty->getAll($cond . " ORDER BY `for_id` ASC",$clsProperty->pkey.",property_code");
		foreach ($listBlocks as $key => $val) {
			$list_blocks[$val[$clsProperty->pkey]] = $val['property_code'];
			$arr_block_id[] = $val[$clsProperty->pkey];
		}
		$arr_cache_user = $arr_cache_user_admin = array();
		$total = $totalStock = 0;
//		$clsLogCrawl->setDeBug(1);
		$arr_agency_block = array();
		$list_logs_json = $clsLogCrawl->getAll("`stock_type`='".$stock_type."' AND FROM_UNIXTIME(`date`,'%Y-%m-%d')='".$date."'","agency_id,description");
		$arr_logs_json = array();
		if(!empty($list_logs_json)) {
			foreach ($list_logs_json as $key => $val) {
				$arr_logs_json[$val["agency_id"]][] = $val;
			}
		}
		$lstBlockTotalUpd = array();
		foreach ($list_agency as $key => $val) {
			$agency_id = $val["property_id"];
			$more_information = $clsISO->to_array_json($val['more_information']);
			if(!empty($more_information["spreadsheetId"])) {			
				$block_crawl = !empty($more_information['block_crawl']) ? $more_information['block_crawl'] : array();
				$block_not_upd = !empty($more_information["block_not_upd"]) ? $more_information["block_not_upd"] : array();
				if($clsCrawl->isSubset_diff($arr_block_id,$block_not_upd)) {
					unset($list_agency[$key]);
					continue;
				}
				$list_agency[$key]['more_information'] = $more_information;
				$list_agency[$key]['spreadsheetId'] = $more_information["spreadsheetId"];
				$total_dq = $is_crawl = $total_upd = $total_not_upd = $total_dont_upd = 0;		
				foreach ($list_blocks as $block_id=>$block_code) {
					$is_crawl = 1;
					$is_agency_not_upd = $is_agency_upd = 0;
					$total_upd_pro = $total_fail_pro = $total_success_pro = 0;
					if($clsISO->checkItemInArray($block_id,$block_not_upd) || empty($block_crawl[$block_id]["is_crawl"])) {
						$is_crawl = 0;
						++$total_dont_upd;
					}
					
					$total_stock = !empty($arr_block_stock[$agency_id][$block_id]) ? $arr_block_stock[$agency_id][$block_id] : 0;
					$block_crawl[$block_id]["time"] = !empty($log) ? $clsLogCrawl->getTimeAgo($log["time"]) : "--";
					$block_crawl[$block_id]["total_stock"] = $total_stock;
					if(isset($arr_total_block[$block_id])) {
						$arr_total_block[$block_id] += $total_stock;
					}else{
						$arr_total_block[$block_id] = $total_stock;
					}
					$total_dq += $total_stock;
					$totalStock += $total_stock;
					
					$check_update = $check_not_upd = 0;
					if(!empty($is_crawl)) {
						$array_total = [];
						if(!empty($arr_logs_json[$agency_id])) {
							$check = 0;
							foreach ($arr_logs_json[$agency_id] as $k_log => $val) {
								$description = !empty($val["description"]) ? $clsISO->to_array_json($val["description"]) : array();
								$lstlogs = !empty($description["logs"]) ? $description["logs"] : array();
								$logs = !empty($lstlogs[$block_id]) ? $lstlogs[$block_id] : array();							
								foreach ($logs as $k => $v) {
									++$total_upd_pro;
									if($v["result_type"]=="update") {
										++$total_success_pro;
									}else{
										++$total_fail_pro;
									}
								}
								if(!empty($logs) && $k_log == 0) {
									$logs = array_reverse($logs);
									$log = reset($logs);
									$block_crawl[$block_id]["title_log"] = ($log["result_type"]=="update")?"Thành công" : $log["title_log"];
									$block_crawl[$block_id]["is_success"] = ($log["result_type"]=="update")? 1 : 0;	
									$block_crawl[$block_id]["time"] = !empty($log) ? date("d/m/Y H:i",$log["time"]) : "";
									if($log["result_type"] == "update") {
	//									++$total_upd;
										$is_agency_upd = 1;
										$check = 1;		
										$check_update = 1;
									}else{
										$is_agency_not_upd = 1;
									}								
								}elseif(empty($logs)){
									$block_crawl[$block_id]["title_log"] = "Chưa cập nhật";
									$is_agency_not_upd = 1;
								}
								if(empty($check)) {
									$check_not_upd = 1;
								}
							}
						}else{
							$check_not_upd = 1;
							$is_agency_not_upd = 1;			
							$block_crawl[$block_id]["title_log"] = "Chưa cập nhật";
						}						
						#tổng cập nhật
						if(!empty($check_update)) {
							++$total_upd;					
						}
						#tổng chưa cập nhật
						if(!empty($check_not_upd)) {
							++$total_not_upd;					
						}
					}
					#dự án đại lý cập nhật
					$total_agency_upd = !empty($lstBlockTotalUpd[$block_id]["agency_upd"]) ? $lstBlockTotalUpd[$block_id]["agency_upd"] : 0;
					if(!empty($is_agency_upd)) {
						++$total_agency_upd;
					}
					$lstBlockTotalUpd[$block_id]["agency_upd"] = $total_agency_upd;
					#dự án đại lý không cập nhật
					$total_agency_not_upd = !empty($lstBlockTotalUpd[$block_id]["agency_not_upd"]) ? $lstBlockTotalUpd[$block_id]["agency_not_upd"] : 0;
					if(!empty($is_agency_not_upd)) {
						++$total_agency_not_upd;
					}
					$lstBlockTotalUpd[$block_id]["agency_not_upd"] = $total_agency_not_upd;
					unset($total_agency_not_upd);
					$block_crawl[$block_id]["total_upd"] = $total_upd_pro;
					$block_crawl[$block_id]["total_success"] = $total_success_pro;
					$block_crawl[$block_id]["total_fail"] = $total_fail_pro;
					$block_crawl[$block_id]["is_crawl"] = $is_crawl;
				}
				$list_agency[$key]['total_upd'] = $total_upd;
				$list_agency[$key]['total_not_upd'] = $total_not_upd;
				$list_agency[$key]['total_dont_upd'] = $total_dont_upd;
				$list_agency[$key]['list_block'] = $block_crawl;
				$list_agency[$key]['total_stock'] = $total_dq;					
			}else{
				unset($list_agency[$key]);
			}
		}
		$smarty->assign("totalStock",$totalStock);
		$smarty->assign("list_blocks",$list_blocks);
		
//		$clsISO->print_pre($list_agency);die;
	}
	$smarty->assign("lstBlockTotalUpd",$lstBlockTotalUpd);
	$smarty->assign("list_agency",$list_agency);
//	$clsISO->print_pre($lstBlockTotalUpd);die;
	$assign_list["arr_total_block"] = $arr_total_block;
	$smarty->assign("stock_type",$stock_type);
	$smarty->assign("_type",$_type);
	$html = $core->build('_ajax.load_status_log_stock.tpl');
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_ajax_load_agency_link(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$profile_id;
	$clsProperty = new Property();
	$clsLogCrawl = new LogCrawl();
	$clsCrawl = new Crawl();
	$clsStock = new Stock();
	$clsProfile = new Profile();
	$clsProject = new Project();
	$clsUser = new User();
	$assign_list['clsProperty'] = $clsProperty;
	#
	$_type = Input::post('_type', '_agency');
	$stock_type = (int) Input::get("stock_type",_BLOCK_TYPE_HIGHLEVEL_SALE);
	
	$list_agency = $clsProperty->getAll("`is_trash`=0 AND `is_locked`=0 AND `property_id`<>'"._AGENCY_FH_ID."' 
	AND `property_type`='_AGENCY' order by `order_no` ASC", "{$clsProperty->pkey},title,more_information");
	$arr_total_block = $arr_total_project = [];
	$totalStock = 0;
	if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE) {
		$project_id = (int)Input::post("project_id",0);
		$arr_cache_project = [];
		$field = "{$clsProject->pkey},`code`,`title`,`more_information`";
		$cond_project = "`is_menu`='1' AND `list_block_type` LIKE '%|".$stock_type."|%'";	
		if(!empty($project_id)) {
			$cond_project .= "	AND `project_id`='{$project_id}'";
		}
		$lstProjects = $clsProject->getAll($cond_project." order by `reg_date` ASC", $field);
		$list_project = [];
		foreach ($lstProjects as $key => $val) {
			$list_project[$val[$clsProject->pkey]] = $val["title"];
		}
		
		foreach ($list_agency as $key => $val) {
			$lstProjectHasLink = $lstProjectNotHasLink = [];
			$more_information = $clsISO->to_array_json($val['more_information']);
			$crawl_lowfloor = !empty($more_information['crawl_lowfloor']) ? $more_information['crawl_lowfloor'] : array();
			foreach ($list_project as $project_id => $title) {
				if(!empty($crawl_lowfloor[$project_id]) && !empty($crawl_lowfloor[$project_id]["sheetID"])) {
					$lstProjectHasLink[$project_id] = [
						"title"	=>	$title,
						"link"	=>	$clsISO->genGoogleURL($crawl_lowfloor[$project_id]["sheetID"],'spreadsheets')
					];					
				}else{
					$lstProjectNotHasLink[$project_id] = [
						"title"	=>	$title
					];
				}
			}
			$list_agency[$key]["lstHasLink"] = $lstProjectHasLink;
			$list_agency[$key]["lstNotHasLink"] = $lstProjectNotHasLink;
			
		}
		$smarty->assign("lst_project",$lst_project);
	}else{
		$block_id = (int)Input::post("block_id",0);
		$arr_cache_block = [];
		$cond = "`parent_id`='".$stock_type."' AND `property_type`='_BLOCK' AND (JSON_SEARCH(`more_information`,'one','1',NULL,'$.is_out_stock') IS NULL OR JSON_EXTRACT(`more_information`,'$.is_out_stock') = '0') AND JSON_SEARCH(`more_information`,'one','1',NULL,'$.on_sale') IS NOT NULL";
		if(!empty($block_id)) {
			$cond .= "	AND `property_id`='{$block_id}'";
		}
		$listBlocks = $clsProperty->getAll($cond . " ORDER BY `for_id` ASC",$clsProperty->pkey.",property_code,title,more_information,for_id");
		$list_blocks = [];
		foreach ($listBlocks as $key => $val) {
			if(!isset($arr_cache_block[$val['for_id']])) {
				$arr_cache_block[$val['for_id']] = $clsProject->getCode($val['for_id']);
			}
			$title = !empty($arr_cache_block[$val['for_id']]) ? ($val['property_code'] . "(".$arr_cache_block[$val['for_id']].")") : $val['property_code'];
			$list_blocks[$val[$clsProperty->pkey]] = $title;
		}
//		$clsISO->print_pre($list_blocks);die;
		foreach ($list_agency as $key => $val) {
			$lstBlockHasLink = $lstBlockNotHasLink = [];
			$more_information = $clsISO->to_array_json($val['more_information']);
			$block_crawl = !empty($more_information['block_crawl']) ? $more_information['block_crawl'] : array();
			foreach ($list_blocks as $block_id => $title) {
				if(!empty($block_crawl[$block_id]) && !empty($block_crawl[$block_id]["sheetID"])) {
					$lstBlockHasLink[$block_id] = [
						"title"	=>	$title,
						"link"	=>	$clsISO->genGoogleURL($block_crawl[$block_id]["sheetID"],'spreadsheets')
					];					
				}else{
					$lstBlockNotHasLink[$block_id] = [
						"title"	=>	$title
					];
				}
			}
			$list_agency[$key]["lstHasLink"] = $lstBlockHasLink;
			$list_agency[$key]["lstNotHasLink"] = $lstBlockNotHasLink;
			
		}
	}
	$smarty->assign("list_agency",$list_agency);
//	$clsISO->print_pre($lstBlockTotalUpd);die;
	$smarty->assign("stock_type",$stock_type);
	$smarty->assign("_type",$_type);
	$html = $core->build('_ajax.load_agency_link.tpl');
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_open_help(){
	global $core, $dbconn, $smarty, $dbconn, $profile_id, $clsISO;
	$clsConfiguration = new Configuration();
	$SiteMsg_CRAWL_Help = $clsConfiguration->getValue('SiteMsg_CRAWL_Help');
	$html = '<div class="modal-dialog modal-ipad modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header border-bottom">
				<h5 class="modal-title">Hướng dẫn sử dụng</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<div class="tinyContent">
					'.html_entity_decode($SiteMsg_CRAWL_Help).'
				</div>
			</div>
		</div>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html,
		'uid' => $clsISO->getUniqid()
	)); die();
}
function default_crawl_stock_point(){
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $clsISO;
	$clsCrawl = new Crawl();
	
	$agency_id = Input::post("agency_id");
	$agency_id = Input::post("block_id");
	
	$spreadsheetId = "1PgNjmRpwG0BxLsoJXuoGm8xbQayFwCewhW1E2E5rfC4";
	$gid = 1206289231;
	$sheet_name = "BẢNG THEO DÕI C4-C6";
	$ranges = ["BẢNG THEO DÕI C4-C6"];
	$block_id = 10361;
	$agency_id = 10533;
	$stock_type = 178;
	$lstData = $clsCrawl->getDataPoint($spreadsheetId, $ranges, $block_id, $agency_id,$stock_type);
	$clsISO->print_pre($lstData);die;
}
?>