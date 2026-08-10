<?php
	/*
	 * NHẮC SÁNG lịch Gặp trực tiếp trong ngày (Phase 3b — CRM Task Setting V1).
	 * Chạy 1 lần/ngày lúc 8h sáng (cron: 0 8 * * *). Mỗi sale nhận 1 tin digest gom hết
	 * lịch gặp hôm nay → Zalo thường + chuông in-app + web push (PushAlert).
	 * Lịch gặp = follow-up có target_id=_CRM_TASK_MEET_ID, date_id trong hôm nay, chưa xong.
	 * Chạy 1 lần/ngày nên KHÔNG cần dedup. KHÔNG ALTER bảng. Không đụng notify_task.php.
	 */
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
	define("SMARTY_DEBUG", 	false);
	define("COMPILE_CHECK", true);
	define("ADODB_DEBUG", 	false);
	define("STOP_APP_IF_ERROR", 1);
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
	/** Instances */
	$core = new Core();
	$clsISO = new ISO();
	$clsNotify = new Notify();
	$clsProfile = new Profile();
	$clsFollowUp = new FollowUp();
	$clsZalo = new Zalo();
	$clsCustomer = new Customer();
	$clsFcmToken = new FcmToken();

	if (!defined('_CRM_TASK_MEET_ID') || (int)_CRM_TASK_MEET_ID <= 0) {
		die('Cronjob morning meeting: thiếu _CRM_TASK_MEET_ID');
	}
	$_meet_id = (int) _CRM_TASK_MEET_ID;
	$_today_start = strtotime(date('Y-m-d 00:00:00'));
	$_today_end = $_today_start + 86400;

	// Lịch Gặp trực tiếp hẹn HÔM NAY, chưa xong → follow-up target_id = Gặp trực tiếp.
	$condMeet = "`target_id`='{$_meet_id}' AND `date_id` >= {$_today_start} AND `date_id` < {$_today_end} "
		. "AND `status_id` <> '" . _FOLLOWUP_STATUS_DONE_ID . "' AND `is_trash`=0";
	$lstMeet = $clsFollowUp->getAll($condMeet . " ORDER BY `date_id` ASC", "{$clsFollowUp->pkey},admin_id,user_id,customer_id,date_id,intro");
	if (empty($lstMeet)) { die('Cronjob morning meeting: không có lịch gặp hôm nay.'); }

	// Gom theo sale (chủ follow-up: admin_id ưu tiên, fallback user_id).
	$_by_sale = array();
	foreach ($lstMeet as $_m) {
		$_sid = (int)(!empty($_m['admin_id']) ? $_m['admin_id'] : $_m['user_id']);
		if ($_sid <= 0) { continue; }
		if (!isset($_by_sale[$_sid])) { $_by_sale[$_sid] = array(); }
		$_by_sale[$_sid][] = $_m;
	}

	$arr_cache_profile = array();
	$_sent = 0;
	foreach ($_by_sale as $_sid => $_meetings) {
		if (!isset($arr_cache_profile[$_sid])) { $arr_cache_profile[$_sid] = $clsProfile->getOne($_sid); }
		$oneP = $arr_cache_profile[$_sid];
		if (empty($oneP)) { continue; }
		$saleName = $clsProfile->getFullName($_sid, $oneP);
		$cnt = count($_meetings);
		$lines_text = array(); // Zalo (plain)
		$lines_html = array(); // in-app (html)
		foreach ($_meetings as $_m) {
			$cusName = $clsCustomer->getName($_m['customer_id']);
			if ($cusName === null || trim($cusName) === '') { $cusName = 'khách hàng'; }
			$gio = $clsISO->formatDate($_m['date_id'], 4);
			$lines_text[] = sprintf("- %s lúc %s", $cusName, $gio);
			$lines_html[] = sprintf("<li>%s — <i>%s</i></li>", htmlspecialchars((string)$cusName, ENT_QUOTES), $gio);
		}
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
				$msg = sprintf("Chào buổi sáng **%s**! \nHôm nay bạn có {color:#C00000}%d lịch gặp trực tiếp{/color}: \n%s", $saleName, $cnt, implode("\n", $lines_text));
				$clsZalo->sendMsgSchedule($id_zalo, $phone, $msg);
			}
		}
		// 2) Chuông in-app
		$contentNotify = sprintf('Hôm nay bạn có <strong>%d lịch gặp trực tiếp</strong>:<ul class="mb-0">%s</ul>', $cnt, implode('', $lines_html));
		$clsNotify->insertNotify('FollowUp', $clsFollowUp->pkey, (int)$_meetings[0][$clsFollowUp->pkey], $contentNotify, time(), '|' . $_sid . '|');
		// 3) Web push (PushAlert)
		$tmp = $clsFcmToken->getAll("`push_type`='_pushalert' AND `user_id`='{$_sid}' AND `token`<>''", "token");
		$subscribers = array();
		if (!empty($tmp)) {
			foreach ($tmp as $val) { if (!in_array($val['token'], $subscribers)) { $subscribers[] = $val['token']; } }
			if (!empty($subscribers)) {
				$clsNotify->send_subscriber_notification(array(
					'title' => 'Lịch gặp hôm nay',
					'message' => sprintf('Bạn có %d lịch gặp trực tiếp hôm nay', $cnt),
					'url' => PCMS_URL . '/crm/'
				), $subscribers);
			}
		}
		$_sent++;
	}
	die('Cronjob morning meeting success! Đã nhắc ' . $_sent . ' sale.');
?>
