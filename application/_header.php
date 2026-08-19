<?php
	global $assign_list,$smarty,$core,$dbconn,$mod,$sub,$act,$title_page,$description_page
	,$keyword_page,$global_title_page,$global_description_page,$global_keyword_page,$_LANG_ID,
	$takeleave_configs,$_frontIsLoggedin,$clsCategory,$clsCookie,$clsISO,$deviceType,$clsSetting,$extLang,$header_configs;
	#
	$extLang = ''; 
	$assign_list['extLang'] = $extLang;
	$isiPad = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
	$assign_list["isiPad"] = $isiPad;
	$smarty->assign('mod', $mod);
	$smarty->assign('act', $act);
	#
	require_once(DIR_INCLUDES.'/Mobile_Detect.php');
	$detect = new Mobile_Detect();
	$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
	$assign_list["deviceType"] = $deviceType;
	#
	if(preg_match("/msie 6.0/i",$_SERVER['HTTP_USER_AGENT']))
		$use_browser = "InternetExplorer6";
	elseif(preg_match("/msie 7.0/i",$_SERVER['HTTP_USER_AGENT']))
		$use_browser = "InternetExplorer7";
	elseif(preg_match("/Firefox/i",$_SERVER['HTTP_USER_AGENT']))
		$use_browser = "Firefox";
	elseif(preg_match("/Chrome/i",$_SERVER['HTTP_USER_AGENT']))
		$use_browser = "Chrome";
	elseif(preg_match("/(iPod|iPhone|iPad|webOS)/i",$_SERVER['HTTP_USER_AGENT']))
		$use_browser = "iPhone";
	else
		$use_browser = "Other";
	$assign_list["use_browser"] = $use_browser;
	#
	$assign_list["PCMS_DIR"] = PCMS_DIR;
	$assign_list["PCMS_URL"] = PCMS_URL;
	$assign_list["PAGE_NAME"] = PAGE_NAME;
	$assign_list["SITE_URL"] = DOMAIN_URL;
	$assign_list["DOMAIN_URL"] = DOMAIN_URL;
	$assign_list["MYOCEAN_URL"] = MYOCEAN_URL;
	$assign_list["GOOGLEAPI_URL"] = GOOGLEAPI_URL;
	$assign_list["_DOMAIN_SESSION"] = DOMAIN_SESSION;
	$assign_list["_ROLE_STAFF_ADMIN"] = _ROLE_STAFF_ADMIN;
	$assign_list["_NEWS_GRATITUDE_CAT_ID"] = _NEWS_GRATITUDE_CAT_ID;
	#
	$assign_list["DIR_IMAGES"] = DIR_IMAGES;
	$assign_list["URL_THEMES"] = URL_THEMES;
	$assign_list["URL_IMAGES"] = URL_IMAGES;
	$assign_list["URL_JS"] 	   = URL_JS;
	$assign_list["URL_CSS"]    = URL_CSS;
	$assign_list["ISOCMS_DIR"] = ISOCMS_DIR;
	$assign_list["URL_THEMES"] = URL_THEMES;
	#
	$assign_list["core"] = $core;
	$assign_list['sid']= session_id();
	$assign_list["_LANG_ID"] = $_LANG_ID;	
	$assign_list["curl"] = $_SERVER['REQUEST_URI'];
	$assign_list["REQUEST_URI"] = $_SERVER['REQUEST_URI'];
	$assign_list["QUERY_STRING"] = $_SERVER['QUERY_STRING'];
	//$assign_list["upd_version"] = sprintf('%s', 'v.1.1.84');
	$assign_list["upd_version"] = time();
	# Sidebar layout v2 (giao diện menu mới) là mặc định; ?menu=v1 chỉ để xem tạm layout cũ, không dính phiên.
	$assign_list["menu_v2"] = (isset($_GET['menu']) && $_GET['menu'] === 'v1') ? 0 : 1;
	#
	$clsCache = new Cache();
	$clsISO = new ISO(); $assign_list["clsISO"] = $clsISO;
	$clsNews = new News(); $assign_list["clsNews"] = $clsNews;
	$clsMember = new Member(); $assign_list["clsMember"] = $clsMember;
	$clsReport = new Report(); $assign_list["clsReport"] = $clsReport;
	$clsBilling = new Billing(); $assign_list["clsBilling"] = $clsBilling;
	$clsProject = new Project(); $assign_list["clsProject"] = $clsProject;
	$clsProfile = new Profile(); $assign_list["clsProfile"] = $clsProfile;
	$clsProperty = new Property(); $assign_list["clsProperty"] = $clsProperty;
	$clsCustomer = new Customer(); $assign_list["clsCustomer"] = $clsCustomer;
	$clsConfiguration = new Configuration(); $assign_list["clsConfiguration"] = $clsConfiguration;
	/** Check User Login */
	$assign_list["_login_google"] = 1;
	$assign_list["_login_facebook"] = 0;
	$assign_list["return_url"] = Input::request('return_url', '/');
	if($clsCache->has('_header_configs_cached')){
		$header_configs = $clsCache->get('_header_configs_cached');
		// $clsCache->delete('_header_configs_cached');
	} else {
		$header_configs = $clsConfiguration->getValues(array(
			'googlebot',
			'copyright',
			'ContactFooter',
			"Favicon",
			'HeaderLogo',
			'LogoWhite',
			'CompanyName',
			'CompanyNameBrief',
			'BrandColor',
			'StockColorDQ',
			'StockColorSold',
			'StockColor'
		));
		$clsCache->put('_header_configs_cached', $header_configs);
	}
	$ContactFooter = $header_configs['ContactFooter'];
	$takeleave_configs = $header_configs['takeleave_configs'];
	$ContactFooter = $clsISO->to_array_json($ContactFooter);
	$takeleave_configs = $clsISO->to_array_json($takeleave_configs);
	$assign_list["ContactFooter"] = $ContactFooter;
	$assign_list["takeleave_configs"] = $takeleave_configs;
	$assign_list["header_configs"] = $header_configs;
	#If already login
	$profile_id = $total_wishlists = 0; 
	$required_modal = $is_transacted = 0;
	$oneProfile = $transactions_configs = array();
	$loggedIn = $clsProfile->isLoggedIn();
	$stock_compare = vnSessionExist('stock_compare') ? vnSessionGetVar('stock_compare') : [];
	$total_compare = count($stock_compare);
	$assign_list["total_compare"] = $total_compare;
	if($loggedIn){
		$profile_id = $clsProfile->profile_id;
		$oneProfile = $clsProfile->oneProfile;
		$wishlist = $oneProfile['wishlist'];
		$role_id = $oneProfile['role_id'];
		$dep_id = $oneProfile['department_id'];
		// $root_dep_id = $clsProperty->getRootId($dep_id);
		// $root_role_id = $clsProperty->getRootId($role_id);
		$permiss_mod = $oneProfile['permiss_mod'];
		$permiss_mod = $clsISO->to_array_json($permiss_mod);
		$wishlist_arrs = $clsISO->to_array_json($wishlist);
		$total_wishlists = !empty($wishlist_arrs) ? count($wishlist_arrs) : 0;
		$oneRole = $clsProperty->getOne($role_id, "`title` AS `role_name`,`more_information`");
		$role_name = $oneRole['role_name'];
		$more_information = $oneRole['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$permiss_role = $core->get_field($more_information, 'permiss_mod', []);
		if(empty($permiss_mod) && !empty($permiss_role)){
			$permiss_mod = $permiss_role;
		} else if(!empty($permiss_role) && !empty($permiss_mod)){
			foreach($permiss_role as $key => $val){
				if(!isset($permiss_mod[$key])){
					$permiss_mod[$key] = $val;
				}
			}
		}
		$oneProfile['permiss_mod'] = $permiss_mod;
		// $oneProfile['root_dep_id'] = $root_dep_id;
		$more_information = $oneProfile['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$role_name = $core->get_field($more_information, "role_name", "");
		$root_role_id = $core->get_field($more_information, "root_role_id", 0);
		$department_name = $core->get_field($more_information, "department_name", "");
		$is_active_new_version = $core->get_field($more_information, "is_active_new_version", 0);
		$oneProfile['role_name'] = $role_name;
		$oneProfile['root_role_id'] = $root_role_id;
		$oneProfile['department_name'] = $department_name;
		$oneProfile['more_information'] = $more_information;
		$oneProfile['is_active_new_version'] = $is_active_new_version;
		#--Multi-role: vai trò chính + phụ (tối đa 1 phụ). Không có phụ ⇒ y hệt trước.
		$role_pairs = array(array(
			'role_id' => (int) $oneProfile['role_id'],
			'department_id' => (int) $oneProfile['department_id'],
			'list_department_id' => isset($oneProfile['list_department_id']) ? $oneProfile['list_department_id'] : '',
		));
		$secondary = $core->get_field($more_information, 'secondary', []);
		$has_secondary = 0;
		if(!empty($secondary) && (int) $core->get_field($secondary, 'role_id', 0) > 0
			&& (int) $core->get_field($secondary, 'department_id', 0) > 0){
			$has_secondary = 1;
			$role_pairs[] = array(
				'role_id' => (int) $secondary['role_id'],
				'department_id' => (int) $secondary['department_id'],
				'list_department_id' => $core->get_field($secondary, 'list_department_id', ''),
			);
		}
		$oneProfile['role_pairs'] = $role_pairs;
		$oneProfile['has_secondary'] = $has_secondary;
		#--Vai trò đang ACTIVE (session) quyết định scope dữ liệu. Mặc định = chính (index 0).
		$active_idx = 0;
		if($has_secondary && vnSessionExist('active_role') && (int) vnSessionGetVar('active_role') === 1){
			$active_idx = 1;
		}
		$oneProfile['active_role'] = $active_idx;
		if($active_idx === 1){
			$oneProfile['role_id'] = $role_pairs[1]['role_id'];
			$oneProfile['department_id'] = $role_pairs[1]['department_id'];
			$oneProfile['list_department_id'] = $role_pairs[1]['list_department_id'];
			#--Đồng bộ biến cục bộ + nhãn hiển thị theo vai trò ĐANG active (menu, tên vai trò, logdedUser)
			$role_id = $oneProfile['role_id'];
			$dep_id = $oneProfile['department_id'];
			$role_name = $clsProperty->getTitle($oneProfile['role_id']);
			$department_name = $clsProperty->getTitle($oneProfile['department_id']);
			$oneProfile['role_name'] = $role_name;
			$oneProfile['department_name'] = $department_name;
		}
		#--Nhãn nút chuyển đổi vai trò (navbar) — chỉ khi có vai trò phụ
		$role_switcher = array();
		if($has_secondary){
			foreach($role_pairs as $idx => $rp){
				$role_switcher[] = array(
					'idx' => $idx,
					'role_name' => $clsProperty->getTitle($rp['role_id']),
					'department_name' => $clsProperty->getTitle($rp['department_id']),
					'active' => ($idx === $active_idx) ? 1 : 0,
				);
			}
		}
		$assign_list['role_switcher'] = $role_switcher;
		$assign_list["oneProfile"] = $oneProfile;
		$assign_list["logdedUser"] = array(
			'full_name' => $oneProfile['full_name'],
			'avatar' => $clsProfile->getAvatar($profile_id, $oneProfile),
			'role_name' => $role_name,
			'department_name' => $department_name
		);
		$list_projects = $list_quick_menus = array();
		if(!$core->isAjax()){
			if($clsCache->has('_ca_project_cached') && 1==2){
				$list_projects = $clsCache->get('_ca_project_cached');
				// $clsCache->delete('_ca_project_cached');
			} else {
				$field = "{$clsProject->pkey},`code`,`title`,`link`,`is_menu`,`image`,`more_information`,`utilities`,`list_block_type`,`area_id`";
				$list_projects = $clsProject->getAll("`is_trash`=0 AND `is_menu`='1' ORDER BY `reg_date` ASC", $field);
				$order_arrs = [_PROJECT_DEF_ID => 1, _PROJECT_VHOP2_ID => 2, _PROJECT_VHOP3_ID => 3, _PROJECT_SLC_ID => 4];
				if(!empty($list_projects)){
					foreach($list_projects as $key => $val){
						$haveBuildingSale = 0;
						$project_id = $val[$clsProject->pkey];
						$list_block_type = $val['list_block_type'];
						$more_information = $val['more_information'];
						$more_information = $clsISO->to_array_json($more_information);
						$logo = $core->get_field($more_information, "logo", "");
						$address = $core->get_field($more_information, "address", "");
						$arcreage = $core->get_field($more_information, "arcreage", "");
						$apartment = $core->get_field($more_information, "apartment", "");
						$list_projects[$key]['logo'] = $logo;
						$list_projects[$key]['address'] = $address;
						$list_projects[$key]['arcreage'] = $arcreage;
						$list_projects[$key]['apartment'] = $apartment;
						$list_projects[$key]['more_information'] = $more_information;
						$list_projects[$key]['list_block_type'] = $clsISO->getArrayByTextSlash($list_block_type, ",", []);
						$list_projects[$key]['order_no'] = isset($order_arrs[$project_id]) ? $order_arrs[$project_id] : 100;
						$list_blocks = $clsProperty->getOItems('_BLOCK', $project_id, "`parent_id`,`more_information`,`reg_date`");
						$list_menu_blocks = array();
						if(!empty($list_blocks)){
							foreach($list_blocks as $okey => $oval){
								$block_id = $oval[$clsProperty->pkey];
								$order_no = ($block_id == _PROJECT_BLOCK_MGA_ID) ? 2 : 1;
								$more_information = $oval['more_information'];
								$more_information = $clsISO->to_array_json($more_information);
								$list_blocks[$okey]['more_information'] = $more_information;
								if($oval['parent_id']==_BLOCK_TYPE_HIGHLEVEL_SALE){ // Cao tầng
									$list_builings = $clsProperty->getOItems('_BUILDING',$block_id,"`for_id`,`property_code`,`more_information`");
									$list_menu_buildings = array();
									if(!empty($list_builings)){
										foreach($list_builings as $mkey => $mval){
											$building_id = $mval[$clsProperty->pkey];
											$more_information = $mval['more_information'];
											$more_information = $clsISO->to_array_json($more_information);
											$list_builings[$mkey]['more_information'] = $more_information;
											$is_out_stock = $core->get_field($more_information, "is_out_stock", 0);
											if(((int) $core->get_field($more_information, "is_menu", 0) == 1) && $is_out_stock == 0){
												$list_menu_buildings[] = array(
													'order_no' => $order_no,
													'building_id' => $building_id,
													'title' => sprintf('%s(%s)', $mval['title'], $mval['property_code']),
													'link' => sprintf('/project/p%s/b%s.html', $project_id, $building_id)
												);
												$list_menu_blocks[] = array(
													'order_no' => $order_no,
													'title' => sprintf('Tòa %s', $mval['title']),
													'link' => sprintf('/project/p%s/b%s.html', $project_id, $building_id)
												);
											}
										}
									}
									if(!empty($list_menu_buildings)) $haveBuildingSale = 1;
									$list_blocks[$okey]['list_builings'] = $list_builings;
									$list_blocks[$okey]['list_menu_buildings'] = $list_menu_buildings;
								}
							}
						}
						$list_projects[$key]['list_blocks'] = $list_blocks;
						$list_projects[$key]['list_menu_blocks'] = $list_menu_blocks;
						$list_projects[$key]['haveBuildingSale'] = $haveBuildingSale;
					}
					$arrs_order_no = @array_column($list_projects, "order_no");
					@array_multisort($arrs_order_no, SORT_ASC, $list_projects);
				}
				$clsCache->put('_ca_project_cached', $list_projects, 60*60);
			}
			// Check là Sale 
			$trans_configs = array();
			$oneColor = array('color' => '#c81313', 'bgcolor' => '#c81313');
			if($clsISO->checkSale()){
				$current_now = time();
				$b_field = "{$clsBilling->pkey},`reg_date`";
				$tmp = $clsBilling->getByCond("`is_trash`=0 AND `is_cancel`=0 AND `staff_id`='{$profile_id}' 
					ORDER BY `reg_date` DESC LIMIT 0,1", $b_field);
				$days_since_sold = 0; $last_deposit_date = "Chưa có giao dịch";
				if(!empty($tmp)){
					$reg_date = $tmp['reg_date'];
					$last_deposit_date = $clsISO->convertTimeToText($reg_date, true);
					$days_since_sold = $clsISO->getNumDayBetweenDate($reg_date, $current_now);
				} else {
					$start_date = $oneProfile['start_date'];
					$days_since_sold = $clsISO->getNumDayBetweenDate($start_date, $current_now);
				}
				if($days_since_sold > _BILLING_MAX_DAYS){
					$_POPUP_COOKIE = isset($_COOKIE['_POPUP_COOKIE']) ? $_COOKIE['_POPUP_COOKIE'] : "";
					if(empty($_POPUP_COOKIE) || (!empty($_POPUP_COOKIE) && $_POPUP_COOKIE != date('dmY'))){
						$is_transacted = 1;
					}
					@setcookie('_POPUP_COOKIE', date('dmY'), time()+(60*60), '/');
				}
				$trans_configs= array(
					'days_since_sold' => $days_since_sold,
					'last_deposit_date' => $last_deposit_date
				);
				$msg_group_arrs = array(
					1 => array(
						'Đà đang lên… giữ tốc và chốt thêm một căn nữa nào!',
						'Phong độ cao nhất là lúc này — đừng để mạch thắng bị ngắt!',
						'Sale mạnh là sale không cho mình nghỉ quá 7 ngày!',
						'Đi tiếp đi, một cú follow nữa lại ra căn đấy!',
						'Lửa đang cháy đẹp… đừng để tắt!'
					), 2 => array(
						'Hơi chậm nhịp một chút rồi… làm nóng lại nào!',
						'Chỉ cần 1 cuộc gọi chất lượng — là trở lại đường đua ngay!',
						'15 ngày không deal chưa nói lên điều gì… nhưng 16 ngày sẽ khác!',
						'Quay lại routine 3-3-3 đi, cơ hội đang nằm ngay sát!',
						'Phá chững – lấy lại nhịp – bật lại mode chiến!'
					), 3 => array(
						'Một deal lúc này không chỉ là doanh số — mà là lấy lại phong độ!',
						'30 ngày không giao dịch không phải lý do — chỉ là tín hiệu cần hành động!',
						'Nhớ cảm giác chốt căn gần nhất không? Lấy lại nó đi',
						'Đã đến lúc trở lại là chính mình!',
						'“Đã 3 tuần rồi… giờ là lúc bật chế độ nghiêm túc!'
					), 4 => array(
						'Không ai thay đổi tương lai mình ngoài chính mình — quay lại đường đua thôi!',
						'Deal tiếp theo sẽ quyết định luôn phong độ năm nay của bạn!',
						'Nếu không làm mới mình bây giờ… thì khi nào?',
						'Cả đội đang tiến lên — đừng để bị tụt phía sau!',
						'Cho mình một cơ hội mới — và làm lại từ hôm nay!'
					), 5 => array(
						'Đã quá lâu rồi… nhưng chỉ cần một bước nhỏ để quay lại.',
						'Không ai bỏ ai ở FH — nhưng bạn phải bước một bước trước.',
						'Nếu bạn còn ước mơ với nghề… đây là lúc đứng dậy.',
						'Cả công ty vẫn chờ thấy bạn trong bảng giao dịch tháng này',
						'Cho mình một cơ hội mới — và làm lại từ hôm nay!'
					)
				);	
				$ranges = [
					1 => ['from' => 0, 'to' => 7, 'color' => '#FFF8D6', 'bgcolor' => '#E6A400', 'message' => ''],
					2 => ['from' => 8, 'to' => 15, 'color' => '#FEE7A0', 'bgcolor' => '#D97900', 'message' => ''],
					3 => ['from' => 16, 'to' => 30, 'color' => '#FFD28C', 'bgcolor' => '#CC5C00', 'message' => ''],
					4 => ['from' => 31, 'to' => 60, 'color' => '#FFB463', 'bgcolor' => '#B93A00', 'message' => ''],
					5 => ['from' => 61, 'to' => 9999,'color' => '#FF8A80', 'bgcolor' => '#C62828', 'message' => '']
				];
				foreach($ranges as $key => $val){
					if($trans_configs['days_since_sold'] >= $val['from'] 
						&& $trans_configs['days_since_sold'] <= $val['to']){
						$oneColor = $ranges[$key];
						$msg_arrs = $msg_group_arrs[$key];
						$random_key = array_rand($msg_arrs);
						$oneColor['message'] = $msg_arrs[$random_key];
						break;
					}
				}
				#hoat dong tiep khach
				$msg_group_share_arrs = array(
					0 => array(
						'Tuần này bạn CHƯA có hoạt động tiếp khách. KPI tuần đang bị vi phạm – hành động ngay!'
					), 1 => array(
						'Tuần này bạn mới tiếp khách 1/2 lần. Cần thêm 1 lần nữa để hoàn thành KPI tuần.'
					), 2 => array(
						'Chúc mừng! Bạn đã hoàn thành KPI tiếp khách tuần này. Duy trì phong độ nhé!'
					), 3 => array(
						'Bạn đang vượt KPI tiếp khách tuần này. Phong độ này rất đáng ghi nhận!'
					)
				);	
				$ranges_share = [
					0 => ['color' => '#811b00', 'bgcolor' => '#00c0c7', 'message' => ''],
					1 => ['color' => '#fff7ee', 'bgcolor' => '#d48700', 'message' => ''],
					2 => ['color' => '#972000', 'bgcolor' => '#34cc00', 'message' => ''],
					3 => ['color' => '#FEE7A0', 'bgcolor' => '#7000d9', 'message' => ''],
				];
				$clsShare = new Share();
				$startOfWeek = date('Y-m-d 00:00:00', strtotime('monday this week'));
				$endOfWeek = date('Y-m-d 23:59:59', strtotime('sunday this week'));
				$cond_share = "`is_trash`=0 AND `share_type`='share' AND `user_id`='{$profile_id}' 
					AND (`reg_date` BETWEEN '".strtotime($startOfWeek)."' AND '".strtotime($endOfWeek)."')";
				$total_share = $clsShare->countItem($cond_share . " ORDER BY `reg_date` DESC");
				if(!empty($ranges_share[$total_share])){
					$k_range = $total_share;
				}else{
					$k_range = 3;
				}
				$oneColorShare = $ranges_share[$k_range];
				$msg_arrs_share = $msg_group_share_arrs[$k_range];
				$random_key_share = array_rand($msg_arrs_share);
				$oneColorShare['message'] = $msg_arrs_share[$random_key_share];
				$oneColorShare['total_share'] = $total_share;
				$assign_list["oneColorShare"] = $oneColorShare;
			}
			$assign_list["oneColor"] = $oneColor;
			$assign_list["transactions_configs"] = $trans_configs;
			#total request PTG
			$total_request_pendding = 0;
			if($clsISO->checkPermissionGroup('DIRECTOR') 
				|| $clsISO->checkPermissionGroup('ADMIN_PROJECT') 
				|| $clsISO->checkPermissionGroup('PROJECT_DIRECTOR')){
				$clsRequestPTG = new RequestPTG();
				$total_request_pendding = $clsRequestPTG->countItem("`status_id`='0'");
			}
			$assign_list["total_request_pendding"] = $total_request_pendding;
			###
			if(!in_array($profile_id, _PROFILE_NOT_ACCESS_LOGS_ID)){
				$clsProfileLog = new ProfileLog();
				$clsProfileLog->init();
			}
		}
		$assign_list["list_projects"] = $list_projects;
		$list_projects_highfloor = [_PROJECT_DEF_ID,_PROJECT_VHOP2_ID,_PROJECT_VHGG_ID];
		$list_projects_lowfloor = [_PROJECT_VHOP3_ID,_PROJECT_VHOP2_ID,_PROJECT_VHGG_ID,_PROJECT_VWC_ID];
		$assign_list["list_projects_highfloor"] = $list_projects_highfloor;
		$assign_list["list_projects_lowfloor"] = $list_projects_lowfloor;
	} else {
		# mod=info: trang ho so tu van vien cong khai, khong yeu cau dang nhap
		if($mod !='auth' && $mod !='info'){
			header("Location: ".PCMS_URL.'/dang-nhap/ret='.$_SERVER['REQUEST_URI']);
			exit();
		}
	}
	$assign_list["loggedIn"] = $loggedIn;
	$assign_list["profile_id"] = $profile_id;
	$assign_list["is_transacted"] = $is_transacted;
	$assign_list["total_wishlists"] = $total_wishlists;
	// Full Permis
	$is_full_permis = $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkDEV() ? 1 : 0;
	$assign_list["is_full_permis"] = $is_full_permis;
	/** End Login */
	global $_FRONTLANG;
	$scriptlang = '<script type="text/javascript">
		var __ = [];';
		foreach($_FRONTLANG as $k => $v){
			$scriptlang .= '__[\''. str_replace("'",'',$k).'\']="'.$v.'";';
		}
	$scriptlang .='</script>';
	$assign_list["scriptlang"] = $scriptlang;
	/** Gửi báo cáo hàng ngày */
	$is_send_report_today = 0;
	$is_sales = $clsISO->checkSale();
	$assign_list["is_sales"] = $is_sales;
	$assign_list["is_send_report_today"] = $is_send_report_today;
	#-- Seting @DEV
	$dev = vnSessionExist('dev') ? (int) vnSessionGetVar('dev') : 0;
	if(isset($_GET['dev'])){
		$dev = (int) $_GET['dev'];
		vnSessionSetVar('dev', $dev);
	}
	if($mod == "homepage" || $mod == "auth"){
		// Continue
	} else {
		if($dev == 0) die("Hệ thống bảo trì. Vui lòng quay lại sau!");
	}
	$assign_list["dev"] = $dev;
?>