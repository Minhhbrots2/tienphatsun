<?php 
	ini_set('display_errors', '1');
	error_reporting(true);
	ini_set('memory_limit', '5048M');
	
	date_default_timezone_set('Asia/Ho_Chi_Minh');
	define('DS', DIRECTORY_SEPARATOR);
	define("_SITE_ROOT", 'CRONJOB');
	define('ABSPATH', $_SERVER['DOCUMENT_ROOT']);
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
		$offset="+7:00";
		$dbconn->Execute("SET time_zone='".$offset."';");
	} else {
		$dbconn =& ADONewConnection(DB_TYPE);
		$dbconn->debug = ADODB_DEBUG;
		$dbconn->Connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$dbconn->setFetchMode(ADODB_FETCH_ASSOC);
		$offset="+7:00";
		$dbconn->Execute("SET time_zone='".$offset."';");
	}
	/** Core Requirement */
	require_once DIR_COMMON."/DbBasic.php";
	require_once DIR_COMMON."/App.php";
	require_once DIR_COMMON."/Core.php";
	require_once DIR_COMMON."/Module.php";
	require_once DIR_COMMON."/Download.php";
	require_once DIR_COMMON."/Upload.php";
	require_once DIR_COMMON."/Config.php";
	require_once DIR_COMMON."/Common.php";
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
	/** End ClassRequirement */
	$core = new Core();
	$clsISO = new ISO();
	$clsNotify = new Notify();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsClassTable = new FollowUp();
	#
//	 $dbconn->debug = true;
	$nowTs = time();
	// Cron 5': dùng cửa sổ thời gian (số nguyên Unix) thay so-khớp-phút (kẻo lọt note có phút lẻ).
	// Cửa sổ 15' bao trọn khoảng chạy + jitter; claim is_send_reminder bên dưới chống gửi lặp; quá 15' coi như cũ → bỏ qua (tránh spam sau downtime).
	$cond = "`followup_type`='_note' AND `is_send_reminder`='0' AND `is_reminder`='1' AND `reminder_time` > 0 AND `reminder_time` <= {$nowTs} AND `reminder_time` >= " . ($nowTs - 900);
	$lstItem = $clsClassTable->getAll($cond,"{$clsClassTable->pkey},type_id,is_send_zalo,user_id,intro,date_id");
	$arr_cache_property = $arr_cache_profile = [];
	if(!empty($lstItem)){
		foreach ($lstItem as $key => $_oItem) {
			// Claim atomic chống gửi lặp (cửa sổ qua nhiều tick cron): chỉ xử lý nếu giành được cờ
			$_fid = (int) $_oItem[$clsClassTable->pkey];
			$dbconn->Execute("UPDATE `{$clsClassTable->tbl}` SET `is_send_reminder`=1 WHERE `{$clsClassTable->pkey}`={$_fid} AND `is_send_reminder`=0");
			if ((int) $dbconn->Affected_Rows() < 1) { continue; }
			$type_id = $_oItem["type_id"];
			if(!empty($type_id)) {
				if(!isset($arr_cache_property[$type_id])) {
					$arr_cache_property[$type_id] = $clsProperty->getTitle($type_id);
				}
				$type_name = $arr_cache_property[$type_id];
			}else{
				$type_name = "Nhắc nhở";
			}
			$_oItem["type_name"] = $type_name;
			$time_reminder = $clsISO->formatDate($_oItem["date_id"],4);
			$_oItem["time"] = $time_reminder;
			if(!empty($_oItem["is_send_zalo"])) {
				$clsZalo = new Zalo();
				if(!isset($arr_cache_profile[$_oItem["user_id"]])) {
					$arr_cache_profile[$_oItem["user_id"]] = $clsProfile->getOne($_oItem["user_id"]);
				}
				$oneProfile = $arr_cache_profile[$_oItem["user_id"]];
				$phone = !empty($oneProfile["phone"]) ? $oneProfile["phone"] : "";
				if(!empty($phone)) {
					$more_information = $oneProfile['more_information'];
					$more_information = $clsISO->to_array_json($more_information);
					$id_zalo = $core->get_field($more_information, "zaloId", "");
					$message = sprintf("Xin chào **%s**. \nBạn có 1 thông báo {color:#C00000}%s{/color} vào lúc {color:#C00000}%s{/color} \n**Nội dung:** %s", $clsProfile->getFullName($oneProfile[$clsProfile->pkey],$oneProfile),$type_name,$time_reminder, $_oItem["intro"]);
					if(!empty($id_zalo)){
						$clsZalo->sendMsgSchedule($id_zalo, $phone, $message);
					} else {
						$id_zalo = $clsZalo->getZaloId("", $phone);
						if(!empty($id_zalo)){
							$more_information['zaloId'] = $id_zalo;
							$clsProfile->updateOne($oneProfile[$clsProfile->pkey], array(
								'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
							));
							$clsZalo->sendMsgSchedule($id_zalo, $phone, $message);
						}
					}
				}
			}else{
				$curl = new \Curl\Curl();
				$post_data = array(
					'tenant'     => _TENANT_ID,
					'secret'     => _CHAT_SOCKET_SECRET,
					'profile_id' => $_oItem["user_id"],
					'data' => $_oItem
				);
				$curl->setHeader('Content-Type', 'application/json');
				$curl->post(_CHAT_SOCKET_URL.'/notify-task', $post_data);
			}
		}
	}
	// === Nhắc follow-up CRM: giờ hẹn tương lai, bắn 15' trước (reminder_time). Gửi Zalo thường + Web push. ===
	$clsZalo = new Zalo();
	$clsCustomer = new Customer();
	$clsFcmToken = new FcmToken();
	$nowTs = time();
	$condCRM = "`customer_id` > 0 AND IFNULL(`followup_type`,'') <> '_note' "
		. "AND `is_reminder`='1' AND `is_send_reminder`='0' "
		. "AND `status_id` <> '" . _FOLLOWUP_STATUS_DONE_ID . "' "
		. "AND `reminder_time` > 0 AND `reminder_time` <= {$nowTs} AND `date_id` >= {$nowTs}"; // tới mốc nhắc & cuộc hẹn còn phía trước — đúng ở mọi khoảng chạy cron (1'/5'), chống jitter & downtime
	$lstCRM = $clsClassTable->getAll($condCRM, "{$clsClassTable->pkey},type_id,user_id,admin_id,customer_id,intro,date_id");
	if (!empty($lstCRM)) {
		foreach ($lstCRM as $_oC) {
			$fid = (int) $_oC[$clsClassTable->pkey];
			// Claim atomic chống gửi lặp (cron chồng lần chạy / cửa sổ 5'): chỉ xử lý nếu giành được cờ
			$dbconn->Execute("UPDATE `{$clsClassTable->tbl}` SET `is_send_reminder`=1 WHERE `{$clsClassTable->pkey}`={$fid} AND `is_send_reminder`=0");
			if ((int) $dbconn->Affected_Rows() < 1) { continue; }
			$uid = (int) (!empty($_oC['admin_id']) ? $_oC['admin_id'] : $_oC['user_id']);
			if ($uid <= 0) { continue; }
			$type_id = (int) $_oC['type_id'];
			$type_name = 'Lịch hẹn';
			if ($type_id > 0) {
				if (!isset($arr_cache_property[$type_id])) { $arr_cache_property[$type_id] = $clsProperty->getTitle($type_id); }
				if (!empty($arr_cache_property[$type_id])) { $type_name = $arr_cache_property[$type_id]; }
			}
			$cusName = $clsCustomer->getName($_oC['customer_id']);
			$time_hen = $clsISO->formatDate($_oC['date_id'], 4);
			if (!isset($arr_cache_profile[$uid])) { $arr_cache_profile[$uid] = $clsProfile->getOne($uid); }
			$oneP = $arr_cache_profile[$uid];
			// 1) Zalo thường (không ZNS)
			$phone = !empty($oneP['phone']) ? $oneP['phone'] : '';
			if (!empty($phone)) {
				$mi = $clsISO->to_array_json($oneP['more_information']);
				$id_zalo = $core->get_field($mi, 'zaloId', '');
				if (empty($id_zalo)) {
					$id_zalo = $clsZalo->getZaloId('', $phone);
					if (!empty($id_zalo)) {
						$mi['zaloId'] = $id_zalo;
						$clsProfile->updateOne($oneP[$clsProfile->pkey], array('more_information' => json_encode($mi, JSON_UNESCAPED_UNICODE)));
					}
				}
				if (!empty($id_zalo)) {
					$msg = sprintf("Xin chào **%s**. \nSau 15 phút bạn có {color:#C00000}%s{/color} với khách **%s** lúc {color:#C00000}%s{/color}. \n**Nội dung:** %s", $clsProfile->getFullName($oneP[$clsProfile->pkey], $oneP), $type_name, $cusName, $time_hen, $_oC['intro']);
					$clsZalo->sendMsgSchedule($id_zalo, $phone, $msg);
				}
			}
			// 2) Chuông in-app (insertNotify) + Web push (PushAlert) tới đúng chủ follow-up
			$contentNotify = sprintf('Nhắc lịch: <strong>%s</strong> với khách <strong>%s</strong> vào lúc <i>%s</i>', $type_name, $cusName, $time_hen);
			$clsNotify->insertNotify('FollowUp', $clsClassTable->pkey, $fid, $contentNotify, time(), '|' . $uid . '|');
			$tmp = $clsFcmToken->getAll("`push_type`='_pushalert' AND `user_id`='{$uid}' AND `token`<>''", "token");
			$subscribers = array();
			if (!empty($tmp)) {
				foreach ($tmp as $val) {
					if (!in_array($val['token'], $subscribers)) { $subscribers[] = $val['token']; }
				}
				if (!empty($subscribers)) {
					$clsNotify->send_subscriber_notification(array(
						'title' => 'Nhắc lịch follow-up',
						'message' => strip_tags($contentNotify),
						'url' => PCMS_URL . sprintf('/crm/#/customer/activity/%s/', $_oC['customer_id'])
					), $subscribers);
				}
			}
		}
	}
	// Return
	die('Cronjob success !');
?>