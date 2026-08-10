<?php 
	error_reporting(false);
	ini_set('display_errors',0);
	//ini_set('post_max_size','200M');
	define('DS', DIRECTORY_SEPARATOR);
	define('ABSPATH',$_SERVER['DOCUMENT_ROOT']);
	define('ROOTPATH', $_SERVER['DOCUMENT_ROOT']);
	define("DIR_INCLUDES", ROOTPATH."/core");
	define("DIR_MODELS", ROOTPATH."/models");
	define("DIR_ADODB", DIR_INCLUDES."/adodb5");
	/** Required Config */
	require(ABSPATH.DS.'init.php');
	require(ABSPATH.DS.'setting.php');
	define("DIR_LANG", PCMS_DIR."/lang");
	if(!defined('LANG_DEFAULT')) 
		define("LANG_DEFAULT",'vn');
	
	/** Debugging */
	define("SMARTY_DEBUG", 	false);//debug or not
	define("COMPILE_CHECK", true);//compile check
	define("ADODB_DEBUG", 	false);//debug or not
	define("STOP_APP_IF_ERROR", 1);//stop if error happen 0: no, 1: yes
	/** DriverDatabase */
	require_once(DIR_ADODB."/adodb.inc.php");
	if(defined('ADODB_CACHED') && ADODB_CACHED==1){
		$ADODB_CACHE_DIR = DIR_CACHE_ADODB;
		$GLOBALS['ADODB_CACHE_DIR'] = DIR_CACHE_ADODB;
		$dbconn =& ADONewConnection(DB_TYPE);
		$dbconn->debug = ADODB_DEBUG;
		$dbconn->Connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$dbconn->cacheSecs = ADODB_CACHED_TIME;
		$dbconn->setFetchMode(ADODB_FETCH_ASSOC);
	} else {
		$dbconn =& ADONewConnection(DB_TYPE);
		$dbconn->debug = ADODB_DEBUG;
		$dbconn->Connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$dbconn->setFetchMode(ADODB_FETCH_ASSOC);
	}
	/** Core Requirement */
	require_once DIR_COMMON."/DbBasic.php";
	require_once DIR_COMMON."/App.php";
	//require_once DIR_COMMON."/Core.php";
	require_once DIR_COMMON."/Module.php";
	require_once DIR_COMMON."/Download.php";
	require_once DIR_COMMON."/Upload.php";
	require_once DIR_COMMON."/Config.php";
	require_once DIR_INCLUDES."/curl/vendor/autoload.php";
	require_once DIR_INCLUDES."/carbon/vendor/autoload.php";
	
	/** ClassRequirement */
	if(function_exists('spl_autoload_register')){
		function autoload($model){
			if(file_exists(DIR_MODELS.DS.$model.'.php')){
				require_once(DIR_MODELS.DS.$model.'.php');
			} else if(file_exists(DIR_MODELS.'/class.'.$model.'.php')){
				require_once(DIR_MODELS.'/class.'.$model.'.php');
			}
		}
		spl_autoload_register('autoload');
	}
	/** End ClassRequirement */
	
	/** Library Requirement */
	if (is_dir(DIR_LIB)){
		$customLibArray = array();
		if ($dh = opendir(DIR_LIB)) {
			while (($file = readdir($dh)) !== false) {
				if (substr($file, -3)=='php')
				array_push($customLibArray, $file);
			}
			closedir($dh);
		}
		if(!empty($customLibArray)){
			foreach ($customLibArray as $customLib){
				require_once(DIR_LIB."/".$customLib);
			}
		}
	}
	#
	$clsISO = new ISO();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsFollowUp = new FollowUp();
	$clsCustomer = new Customer();
	$clsCustomerSales = new CustomerSales();
	$field = "`t1`.*,`t2`.`name`,`t2`.`list_share_id`,`t2`.`more_information`";
	$items = $dbconn->getAll("SELECT {$field} FROM {$clsCustomerSales->tbl} AS `t1` 
		INNER JOIN {$clsCustomer->tbl} AS `t2` ON `t1`.`customer_id`=`t2`.`customer_id` WHERE t1.`is_stop`=0"); 
	// $clsISO->print_pre($items); die();
	if(!empty($items) && 1==2){
		foreach($items as $key => $val){
			$admin_id = (int) $val['admin_id'];
			$customer_id = (int) $val['customer_id'];
			$assign_date = (int) $val['assign_date'];
			$list_share_id = $val['list_share_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$list_share_arrs = !empty($list_share_id) 
				? $clsISO->getArrayByTextSlash($list_share_id) : array();
			$list_logs = isset($more_information['logs']) ? $more_information['logs'] : array();
			// $dbconn->debug = true;
			$list_followups = $clsFollowUp->getAll("`admin_id`='{$admin_id}' AND `customer_id`='{$customer_id}' ORDER BY `reg_date` DESC");
			// AND `reg_date`>'".strtotime("+15 minutes", $assign_date)."'
			if(!empty($list_followups)){
				$oneFollowup = $list_followups[0];
				/** Sau 1 ngày */
				if(strtotime("+2 day", $assign_date) < time() 
					&& strtotime("+2 day", $oneFollowup['reg_date']) < time() && $val['is_send_notify'] == 0){
					// Cập nhật trạng thái đã gửi notify
					$clsCustomerSales->updateOne($val[$clsCustomerSales->pkey], array(
						'is_send_notify' => 1
					));
					/** Xoá bỏ quền CS */
					$clsZalo = new Zalo();
					$_oProfile = $clsProfile->getOne($admin_id, "`full_name`,`first_name`,`last_name`,`phone`,`more_information`");
					$zaloId = $clsProfile->getZaloId($admin_id, $_oProfile);
					if(!empty($zaloId)){
						$clsZalo = new Zalo();
						$message = sprintf("Xin chào %s", $clsProfile->getFullName($admin_id, $_oProfile));
						$message.= "\r";
						$message.= sprintf("⚠️ Hệ thống cảnh báo khách hàng [%s] bạn đang phụ trách!", $val['name']);
						$message.= "\r";
						$message.= "Lý do: Quá 24h không có tương tác (call / note / update)";
						$message.= "\r";
						$message.= "\r";
						$message.= "Truy cập CRM/Quản lý khách hàng(https://ca.futurehomes.vn/crm/) để chăm sóc ngay những khách hàng được giao!";
						$message.= "\r";
						$message.= "\r";
						$message.= "⚙️ 1. Quy tắc chăm khách";
						$message.= "\r";
						$message.= "1️⃣ Phản hồi khách trong vòng 24h kể từ khi được cấp data.";
						$message.= "\r";
						$message.= "👉 Nếu quá 24h không có tương tác (call / note / update) → hệ thống auto chuyển khách sang sales khác.";
						$message.= "\r";
						$message.= "2️⃣ Tương tác ít nhất 3 lần trong 3 ngày đầu (gọi điện, nhắn tin, Zalo, trao đổi trực tiếp...).";
						$message.= "\r";
						$message.= "👉 Mục tiêu: xác nhận nhu cầu, xây dựng kết nối và tạo phản hồi ban đầu.";
						$message.= "\r";
						$message.= "3️⃣ Cập nhật và tương tác đều đặn trong 24–48h.";
						$message.= "\r";
						$message.= "👉 Nếu quá 48h không có note mới, hệ thống sẽ cảnh báo và hiển thị danh sách khách mới nhận trong 24h chưa có tương tác để sale chủ động xử lý.";
						$message.= "\r";
						$message.= "👉 Nếu quá 72h vẫn không có phản hồi hoặc chăm sóc, Quản trị viên sẽ review và quyết định có chuyển khách sang sales khác hay không, dựa vào mức độ chăm thật.";
						$message.= "\r";
						$message.= "4️⃣ Ghi chú chi tiết sau mỗi cuộc gọi hoặc tương tác: nêu rõ phản hồi, nhu cầu và hướng xử lý tiếp theo.";
						$message.= "\r";
						$clsZalo->sendMsgSchedule($zaloId, $_oProfile['phone'], $message);
					}
				}
				/** Nếu trong 3 ngày có CS */
				if(strtotime("+3 days", $assign_date) < time() && strtotime("+3 days", $oneFollowup['reg_date']) > time()){
					/** Xoá bỏ quền CS */
					$clsCustomerSales->updateOne($val[$clsCustomerSales->pkey], array(
						'is_stop' => 1
					));
				}
			} else {
				if(strtotime("+1 day", $assign_date) < time()){
					if(!in_array($admin_id, $list_share_arrs)){
						$list_share_arrs[] = $admin_id;
						// $clsISO->print_pre($list_share_arrs); die();
					}
					$list_logs[$clsISO->getUniqid()] = array(
						'_type' => 'assign',
						'from_id' => $admin_id,
						'to_id' => _PROFILE_ROOT_ID,
						'status_id' => 0,
						'reg_date' => time()
					);
					$more_information['logs'] = $list_logs;
					if($clsCustomer->updateOne($customer_id, array(
						'admin_id' => _PROFILE_ROOT_ID,
						'list_share_id' => $list_share_id,
						'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
					))){
						/** Xoá bỏ quền CS */
						$clsCustomerSales->updateOne($val[$clsCustomerSales->pkey], array(
							'is_stop' => 1
						));
						/** Gửi tin nhắn Zalo */
						$clsZalo = new Zalo();
						$_oProfile = $clsProfile->getOne($admin_id, "`full_name`,`first_name`,`last_name`,`phone`,`more_information`");
						$zaloId = $clsProfile->getZaloId($admin_id, $_oProfile);
						if(!empty($zaloId)){
							$clsZalo = new Zalo();
							$message = sprintf("Xin chào %s", $clsProfile->getFullName($admin_id, $_oProfile));
							$message.= "\r";
							$message.= sprintf("📢 Hệ thống đã chuyển khách hàng [%s] đã giao cho bạn phụ trách cho Sales khác", $val['name']);
							$message.= "\r";
							$message.= "Lý do: Quá 24h không có tương tác (call / note / update)";
							$message.= "\r";
							$message.= "\r";
							$message.= "Truy cập CRM/Quản lý khách hàng(https://ca.futurehomes.vn/crm/) để chăm sóc những khách hàng được giao!";
							$message.= "\r";
							$message.= "\r";
							$message.= "⚙️ 1. Quy tắc chăm khách";
							$message.= "\r";
							$message.= "1️⃣ Phản hồi khách trong vòng 15 phút kể từ khi được cấp data.";
							$message.= "\r";
							$message.= "👉 Nếu quá 24h không có tương tác (call / note / update) → hệ thống auto chuyển khách sang sales khác.";
							$message.= "\r";
							$message.= "2️⃣ Tương tác ít nhất 3 lần trong 3 ngày đầu (gọi điện, nhắn tin, Zalo, trao đổi trực tiếp...).";
							$message.= "\r";
							$message.= "👉 Mục tiêu: xác nhận nhu cầu, xây dựng kết nối và tạo phản hồi ban đầu.";
							$message.= "\r";
							$message.= "3️⃣ Cập nhật và tương tác đều đặn trong 24–48h.";
							$message.= "\r";
							$message.= "👉 Nếu quá 48h không có note mới, hệ thống sẽ cảnh báo và hiển thị danh sách khách mới nhận trong 24h chưa có tương tác để sale chủ động xử lý.";
							$message.= "\r";
							$message.= "👉 Nếu quá 72h vẫn không có phản hồi hoặc chăm sóc, Quản trị viên sẽ review và quyết định có chuyển khách sang sales khác hay không, dựa vào mức độ chăm thật.";
							$message.= "\r";
							$message.= "4️⃣ Ghi chú chi tiết sau mỗi cuộc gọi hoặc tương tác: nêu rõ phản hồi, nhu cầu và hướng xử lý tiếp theo.";
							$message.= "\r";
							$clsZalo->sendMsgSchedule($zaloId, $_oProfile['phone'], $message);
						}
					}
				}
			}
		}
		unset($items);
	}
	/** F2 — Lead scoring: chấm điểm tiềm năng lead, ghi more_information.lead_score qua khóa A3 (saveMoreInfo, chỉ ghi khi điểm đổi). Quét xoay vòng theo con trỏ để giới hạn tải mỗi lần chạy cron. */
	if(!function_exists('crm_compute_lead_score')){
		function crm_compute_lead_score($cus){
			// điểm 0..100 (rule-based, pure, KHÔNG truy vấn DB): trạng thái 0-45 + độ mới (upd_date) 0-40 + tương tác 0-15.
			$score = 0;
			$status_id = (int) (isset($cus['status_id']) ? $cus['status_id'] : 0);
			$status_w = array(1228=>45, 294=>36, 1284=>28, 366=>22, 293=>14, 291=>7); // Nóng/Tiềm năng/Nét/Đã gặp/Đã tư vấn/Chưa tư vấn; Chốt/Rác/0 = 0
			if(isset($status_w[$status_id])){ $score += $status_w[$status_id]; }
			$logs = (isset($cus['action_logs']) && is_array($cus['action_logs'])) ? $cus['action_logs'] : array();
			// độ mới = hoạt động gần nhất: action_logs reg_date (cập nhật mỗi follow-up/note) hoặc upd_date — KHÔNG dùng upd_date đơn lẻ (không đổi khi log follow-up)
			$recency_ts = (int) (isset($cus['upd_date']) ? $cus['upd_date'] : 0);
			foreach($logs as $lg){
				$lt = (is_array($lg) && isset($lg['reg_date'])) ? (int) $lg['reg_date'] : 0;
				if($lt > $recency_ts){ $recency_ts = $lt; }
			}
			if($recency_ts > 0){
				$age_d = (time() - $recency_ts) / 86400;
				if($age_d <= 1){ $score += 40; }
				else if($age_d <= 3){ $score += 30; }
				else if($age_d <= 7){ $score += 22; }
				else if($age_d <= 14){ $score += 14; }
				else if($age_d <= 30){ $score += 7; }
			}
			$score += min(count($logs) * 3, 15);
			if($score > 100){ $score = 100; }
			if($score < 0){ $score = 0; }
			return (int) $score;
		}
	}
	$clsConfiguration = new Configuration();
	$score_cap = 3000;
	$score_cursor = (int) $clsConfiguration->getValue('crm_lead_score_cursor', 0);
	$score_excl = implode(',', array((int)_CRM_STATUS_TRASH_ID, 0));
	$score_rows = $dbconn->getAll("SELECT `customer_id`,`status_id`,`upd_date`,`more_information` FROM `".$clsCustomer->tbl."` 
		WHERE `is_trash`=0 AND `status_id` NOT IN ({$score_excl}) AND `customer_id` > {$score_cursor} ORDER BY `customer_id` ASC LIMIT {$score_cap}");
	$score_last = 0;
	if(!empty($score_rows)){
		foreach($score_rows as $sr){
			$scid = (int) $sr['customer_id'];
			$smi = $clsISO->to_array_json($sr['more_information']);
			if(!is_array($smi)){ $smi = array(); }
			$scus = array(
				'status_id' => (int) $sr['status_id'],
				'upd_date' => (int) $sr['upd_date'],
				'action_logs' => (isset($smi['action_logs']) && is_array($smi['action_logs'])) ? $smi['action_logs'] : array()
			);
			$snew = crm_compute_lead_score($scus);
			$sold = isset($smi['lead_score']) ? (int) $smi['lead_score'] : -1;
			if($snew !== $sold){
				$clsCustomer->saveMoreInfo($scid, function($m) use ($snew){
					$m['lead_score'] = $snew;
					$m['lead_score_at'] = time();
					return $m;
				});
			}
			$score_last = $scid;
		}
	}
	$score_next_cursor = (count($score_rows) < $score_cap) ? 0 : $score_last; // hết tập → quay đầu (chu kỳ sau re-score, xử lý suy giảm độ mới theo thời gian)
	$clsConfiguration->updateValue('crm_lead_score_cursor', $score_next_cursor);
	/** Send Email*/
	$fromemail = 'info@futurehomes.vn';
	$fromname = 'Hệ thống quản lý Cronjob';
	$toemail = 'vanthiembui.it@gmail.com';
	$toname = 'Bùi Văn Thiêm';
	$subject = '[Thông báo] Cronjob đã chạy hoàn tất';
	$message = 'Xin chào,<br /><br />
	Hệ thống vừa thực thi cronjob CRM vào lúc '.date('d/m/Y H:i:s').'<br />
	Nếu có bất kỳ lỗi nào, vui lòng kiểm tra hệ thống để xử lý kịp thời.<br /><br />
	Trân trọng,<br />
	Hệ thống quản lý Cronjob';
	// $clsISO->sendEmailSystem($fromemail,$fromname,$toemail,$toname,$subject,$message);
	// End
	die("Cron success");
?>