<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| #################################################################### ||
\*======================================================================*/
class ZaloMessage extends dbBasic{
	function __construct(){
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."zalo_message";
	} 
	function calcNextRunAt($next_run_at, $time_start,$time_end, $repeat_interval, $repeat_unit, $timezone = 'Asia/Ho_Chi_Minh') {
		date_default_timezone_set($timezone);
		// interval seconds
		$intervalSeconds = ($repeat_unit === '_hour') ? $repeat_interval * 3600 : $repeat_interval * 60;
		// tách time
		list($sh, $sm, $ss) = array_pad(explode(':', $time_start), 3, 0);
		list($eh, $em, $es) = array_pad(explode(':', $time_end), 3, 0);
		// mốc start & end của NGÀY HIỆN TẠI
		$dayStart = mktime($sh, $sm, $ss, date('m', $next_run_at), date('d', $next_run_at), date('Y', $next_run_at));
		$dayEnd   = mktime($eh, $em, $es, date('m', $next_run_at), date('d', $next_run_at), date('Y', $next_run_at));
		// nếu next_run_at đã vượt dayEnd → ngày đó hết slot
		if ($next_run_at >= $dayEnd) {
			return mktime(
				$sh, $sm, $ss,
				date('m', $next_run_at),
				date('d', $next_run_at) + 1,
				date('Y', $next_run_at)
			);
		}
		// thử slot tiếp theo
		$next = $next_run_at + $intervalSeconds;
		// nếu vượt cửa sổ → reset sang ngày mai
		if ($next > $dayEnd) {
			return mktime(
				$sh, $sm, $ss,
				date('m', $next_run_at),
				date('d', $next_run_at) + 1,
				date('Y', $next_run_at)
			);
		}
		return $next;
	}
	function initNextRunAt($time_start, $time_end, $timezone = "Asia/Ho_Chi_Minh"){
		$tz = new DateTimeZone($timezone);
		$now = new DateTime('now', $tz);
		[$fh, $fm, $fs] = array_pad(explode(':', $time_start), 3, 0);
		[$th, $tm, $ts] = array_pad(explode(':', $time_end), 3, 0);
		// mốc hôm nay
		$todayFrom = new DateTime($now->format('Y-m-d'), $tz);
		$todayFrom->setTime((int)$fh, (int)$fm, (int)$fs);

		$todayTo = new DateTime($now->format('Y-m-d'), $tz);
		$todayTo->setTime((int)$th, (int)$tm, (int)$ts);
		// 1. Chưa tới giờ bắt đầu → lấy time_from hôm nay
		if ($now <= $todayFrom) {
			return $todayFrom->getTimestamp();
		}
		// 2. Đang trong khung → cũng lấy time_from (KHÔNG lấy now)
		if ($now <= $todayTo) {
			return $todayFrom->getTimestamp();
		}
		// 3. Quá giờ → ngày mai time_from
		$todayFrom->modify('+1 day');
		return $todayFrom->getTimestamp();
	}
}