<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/**
 * MemberPackage — gói/đăng ký của member (hệ MF / myfuture.vn).
 * Bảng default_member_package ở CSDL fhgroupt_mf (extends DbBasicMF → $dbconnMF).
 *
 * ⚠ ĐỒNG BỘ CONTRACT với dev.myfuture.vn (models/MemberPackage.php). KHÔNG đổi cột/quy ước
 *   nếu chưa sửa song song bên myfuture, vì 2 site đọc/ghi CHUNG bảng này.
 *   - Tier hiện hành = dòng status='active' (mới nhất). Nâng/hạ/gia hạn/hết hạn = bản ghi MỚI.
 *   - grant() là điểm cấp/đổi gói DUY NHẤT: ghi 1 dòng mới (đóng dòng active cũ) +
 *     đồng bộ ngược default_member.package_id + more_information.VIP.start_date/due_date.
 *   - Catalog gói (_MF_PACKAGE) nằm ở default_property DB CHÍNH (qua model Property);
 *     level/features đọc từ property.more_information.
 *   - Cột bảng: id, member_id, package_id, level, start_date, end_date, price_paid(BIGINT VND),
 *     payment_ref, status(active|expired|cancel), change_type(new|upgrade|downgrade|renew|trial|admin),
 *     source(gateway|trial|admin|cron|migrate), note, user_id, reg_date, upd_date.
 *     (KHÔNG có package_name/duration_label — tên gói tra cứu runtime qua Property.)
 */
class MemberPackage extends DbBasic{
	function __construct(){
		$this->pkey = "id";
		$this->tbl  = "default_member_package";
	}

	/* ===================== TẦNG DỮ LIỆU ===================== */

	// Dòng gói đang active mới nhất (mảng 1 dòng, hoặc array() nếu chưa có).
	function getActive($member_id){
		$member_id = (int)$member_id;
		if($member_id <= 0) return array();
		$rows = $this->getAll("member_id=$member_id and status='active' order by id desc limit 1");
		return !empty($rows) ? $rows[0] : array();
	}

	// Toàn bộ lịch sử gói của 1 member (mới nhất trước).
	function getHistory($member_id){
		$member_id = (int)$member_id;
		if($member_id <= 0) return array();
		return $this->getAll("member_id=$member_id order by id desc");
	}

	// Mở 1 bản ghi gói MỚI + đóng dòng active cũ (đảm bảo chỉ 1 active/member).
	function open($data){
		global $dbconnMF;
		$member_id = isset($data['member_id']) ? (int)$data['member_id'] : 0;
		if($member_id <= 0) return false;
		$now = time();
		$dbconnMF->Execute("UPDATE {$this->tbl} SET status='expired', upd_date=$now WHERE member_id=$member_id AND status='active'");
		if(!isset($data['status']))   $data['status']   = 'active';
		if(!isset($data['reg_date'])) $data['reg_date'] = $now;
		$data['upd_date'] = $now;
		if(!isset($data['id']))       $data['id']       = $this->getMaxId();
		return $this->insert($data);
	}

	// Đổi trạng thái 1 dòng (active|expired|cancel).
	function setStatus($id, $status){
		$id = (int)$id;
		if($id <= 0) return false;
		return $this->updateOne($id, array('status'=>$status, 'upd_date'=>time()));
	}

	/* ===================== TẦNG LOGIC ===================== */

	// Level của 1 gói (property_id). Ưu tiên hằng số myfuture nếu định nghĩa, fallback more_information.level.
	// 0 Free / 1 Pro / 2 VVIP.
	function levelOfPackage($package_id){
		$package_id = (int)$package_id;
		if($package_id <= 0) return 0;
		if(defined('_MEMBER_PARKAGE_VIP_ID')  && $package_id == (int)_MEMBER_PARKAGE_VIP_ID)  return 2;
		if(defined('_MEMBER_PARKAGE_PRO_ID')  && $package_id == (int)_MEMBER_PARKAGE_PRO_ID)  return 1;
		if(defined('_MEMBER_PARKAGE_FREE_ID') && $package_id == (int)_MEMBER_PARKAGE_FREE_ID) return 0;
		$mi = $this->_packageInfo($package_id);
		return isset($mi['level']) ? (int)$mi['level'] : 0;
	}

	// more_information (đã decode) của 1 property gói (catalog ở DB chính).
	function _packageInfo($package_id){
		$package_id = (int)$package_id;
		if($package_id <= 0) return array();
		$clsProperty = new Property();
		$one = $clsProperty->getOne($package_id, "more_information");
		if(empty($one) || empty($one['more_information'])) return array();
		$mi = json_decode($one['more_information'], true);
		return is_array($mi) ? $mi : array();
	}

	/**
	 * Cấp / đổi gói — ĐIỂM DUY NHẤT (đồng bộ với myfuture grant()).
	 * $start, $end: unix (0 = vĩnh viễn). $opts: price_paid, payment_ref, change_type, source, note, user_id.
	 */
	function grant($member_id, $package_id, $start, $end, $opts = array()){
		$member_id  = (int)$member_id;
		$package_id = (int)$package_id;
		if($member_id <= 0 || $package_id <= 0) return false;
		$start = (int)$start;
		$end   = (int)$end;
		$level = $this->levelOfPackage($package_id);
		// 1) Ghi bản ghi gói mới (đóng dòng active cũ)
		$ok = $this->open(array(
			'member_id'   => $member_id,
			'package_id'  => $package_id,
			'level'       => $level,
			'start_date'  => $start,
			'end_date'    => $end,
			'price_paid'  => isset($opts['price_paid'])  ? (int)$opts['price_paid']  : 0,
			'payment_ref' => isset($opts['payment_ref']) ? $opts['payment_ref']      : '',
			'change_type' => isset($opts['change_type']) ? $opts['change_type']      : 'new',
			'source'      => isset($opts['source'])      ? $opts['source']           : 'admin',
			'note'        => isset($opts['note'])        ? $opts['note']             : '',
			'user_id'     => isset($opts['user_id'])     ? (int)$opts['user_id']     : 0,
		));
		// 2) Đồng bộ ngược (back-compat): default_member.package_id + more_information.VIP
		$clsMember = new MF_Member();
		$mi   = $clsMember->getOneField('more_information', $member_id);
		$more = (!empty($mi)) ? json_decode($mi, true) : array();
		if(!is_array($more)) $more = array();
		if(!isset($more['VIP']) || !is_array($more['VIP'])) $more['VIP'] = array();
		$more['VIP']['start_date'] = $start;
		$more['VIP']['due_date']   = $end;
		$clsMember->updateOne($member_id, array(
			'package_id'       => $package_id,
			'more_information' => json_encode($more, JSON_UNESCAPED_UNICODE),
			'upd_date'         => time(),
		));
		return $ok;
	}
}
