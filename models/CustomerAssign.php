<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| #################################################################### ||
\*======================================================================*/
// Phiếu giao khách (default_customer_assign): mỗi dòng = 1 lần giao 1 khách cho 1 người nhận + trạng thái xác nhận.
// confirmed_at = 0 ⇒ người nhận CHƯA xác nhận ⇒ bị chặn thao tác trên khách đó (chỉ được xem).
// Dùng raw $dbconn (mọi tham số đã ép (int)) để tránh ActivityLog spam của dbBasic::insert và cho phép upsert.
class CustomerAssign extends dbBasic{
	function __construct(){
		$this->pkey = "assign_id";
		$this->tbl = DB_PREFIX."customer_assign";
	}
	// Ngưỡng "quá hạn xác nhận": người nhận chưa xác nhận sau 48 giờ ⇒ tính quá hạn.
	const OVERDUE_SECONDS = 172800;

	// Tạo/mở lại phiếu CHỜ khi giao khách cho NGƯỜI KHÁC. Bỏ qua nếu tự giao cho mình hoặc id không hợp lệ.
	// channel: 1 = giao hẳn (đổi chủ), 2 = chia sẻ (dành cho sau). UNIQUE(customer_id,recipient_id) ⇒ giao lại = mở lại phiếu.
	function createPending($customer_id, $recipient_id, $assigned_by, $channel = 1){
		global $dbconn;
		$customer_id = (int) $customer_id;
		$recipient_id = (int) $recipient_id;
		$assigned_by = (int) $assigned_by;
		$channel = (int) $channel;
		if($customer_id <= 0 || $recipient_id <= 0){
			return 0;
		}
		if($recipient_id == $assigned_by){
			return 0;
		}
		$now = time();
		$sql = "INSERT INTO `{$this->tbl}` (`customer_id`,`recipient_id`,`assigned_by`,`channel`,`assigned_at`,`confirmed_at`,`is_trash`)"
			. " VALUES ({$customer_id},{$recipient_id},{$assigned_by},{$channel},{$now},0,0)"
			. " ON DUPLICATE KEY UPDATE `assigned_by`={$assigned_by},`channel`={$channel},`assigned_at`={$now},`confirmed_at`=0,`is_trash`=0";
		$dbconn->Execute($sql);
		return 1;
	}
	// Người nhận CHƯA xác nhận khách này? (còn phiếu pending hiệu lực)
	function isPending($customer_id, $recipient_id){
		global $dbconn;
		$customer_id = (int) $customer_id;
		$recipient_id = (int) $recipient_id;
		if($customer_id <= 0 || $recipient_id <= 0){
			return 0;
		}
		$cnt = (int) $dbconn->GetOne("SELECT COUNT(*) FROM `{$this->tbl}` WHERE `customer_id`={$customer_id} AND `recipient_id`={$recipient_id} AND `confirmed_at`=0 AND `is_trash`=0");
		return $cnt > 0 ? 1 : 0;
	}
	// Trong 1 danh sách khách, trả về map[customer_id]=1 cho những khách người nhận này CHƯA xác nhận (1 query — dùng render list).
	function pendingIdsIn($recipient_id, $customer_ids){
		global $dbconn;
		$recipient_id = (int) $recipient_id;
		$ids = array();
		if(is_array($customer_ids)){
			foreach($customer_ids as $cid){
				$cid = (int) $cid;
				if($cid > 0){
					$ids[] = $cid;
				}
			}
		}
		if($recipient_id <= 0 || empty($ids)){
			return array();
		}
		$in = implode(',', array_unique($ids));
		$rows = $dbconn->GetAll("SELECT `customer_id` FROM `{$this->tbl}` WHERE `recipient_id`={$recipient_id} AND `customer_id` IN ({$in}) AND `confirmed_at`=0 AND `is_trash`=0");
		$out = array();
		if(!empty($rows)){
			foreach($rows as $r){
				$out[(int) $r['customer_id']] = 1;
			}
		}
		return $out;
	}
	// Người nhận xác nhận đã nhận 1 khách (chỉ phiếu pending của chính họ). Trả 1 nếu có phiếu được xác nhận, 0 nếu không có gì để nhận.
	function confirm($customer_id, $recipient_id){
		global $dbconn;
		$customer_id = (int) $customer_id;
		$recipient_id = (int) $recipient_id;
		if($customer_id <= 0 || $recipient_id <= 0){
			return 0;
		}
		$cnt = (int) $dbconn->GetOne("SELECT COUNT(*) FROM `{$this->tbl}` WHERE `customer_id`={$customer_id} AND `recipient_id`={$recipient_id} AND `confirmed_at`=0 AND `is_trash`=0");
		if($cnt <= 0){
			return 0;
		}
		$now = time();
		$dbconn->Execute("UPDATE `{$this->tbl}` SET `confirmed_at`={$now} WHERE `customer_id`={$customer_id} AND `recipient_id`={$recipient_id} AND `confirmed_at`=0 AND `is_trash`=0");
		return 1;
	}
	// Trạng thái giao của 1 khách (cho người GIAO theo dõi): danh sách phiếu + đã/chưa nhận, mới nhất trước.
	function getByCustomer($customer_id){
		global $dbconn;
		$customer_id = (int) $customer_id;
		if($customer_id <= 0){
			return array();
		}
		return $dbconn->GetAll("SELECT `recipient_id`,`assigned_by`,`assigned_at`,`confirmed_at`,`channel` FROM `{$this->tbl}` WHERE `customer_id`={$customer_id} AND `is_trash`=0 ORDER BY `assigned_at` DESC");
	}
	// Trạng thái giao của 1 khách KÈM TÊN người nhận + người giao — cho panel "Trạng thái giao" trong chi tiết khách.
	function getByCustomerNamed($customer_id){
		global $dbconn;
		$customer_id = (int) $customer_id;
		if($customer_id <= 0){
			return array();
		}
		$clsProfile = new Profile();
		$ptbl = $clsProfile->tbl;
		$sql = "SELECT a.`recipient_id`, a.`assigned_by`, a.`assigned_at`, a.`confirmed_at`, a.`channel`, pr.`full_name` AS recipient_name, pg.`full_name` AS assigner_name"
			. " FROM `{$this->tbl}` a"
			. " LEFT JOIN `{$ptbl}` pr ON pr.`profile_id`=a.`recipient_id`"
			. " LEFT JOIN `{$ptbl}` pg ON pg.`profile_id`=a.`assigned_by`"
			. " WHERE a.`customer_id`={$customer_id} AND a.`is_trash`=0 ORDER BY a.`assigned_at` DESC";
		return $dbconn->GetAll($sql);
	}
	// Danh sách phiếu DO NGƯỜI NÀY giao (assigned_by) — kèm tên khách + người nhận + nhắc. $status: all|pending|overdue|confirmed.
	// pending = chưa nhận & trong hạn; overdue = chưa nhận & quá ngưỡng OVERDUE_SECONDS; confirmed = đã nhận.
	function byAssigner($assigned_by, $status = 'all'){
		global $dbconn;
		$assigned_by = (int) $assigned_by;
		if($assigned_by <= 0){
			return array();
		}
		$ctbl = DB_PREFIX . 'customer';
		$clsProfile = new Profile();
		$ptbl = $clsProfile->tbl;
		$cutoff = time() - self::OVERDUE_SECONDS;
		$cond = "a.`assigned_by`={$assigned_by} AND a.`is_trash`=0";
		if($status === 'pending'){
			$cond .= " AND a.`confirmed_at`=0 AND a.`assigned_at`>={$cutoff}";
		} else if($status === 'overdue'){
			$cond .= " AND a.`confirmed_at`=0 AND a.`assigned_at`<{$cutoff}";
		} else if($status === 'confirmed'){
			$cond .= " AND a.`confirmed_at`>0";
		}
		$sql = "SELECT a.`customer_id`, a.`recipient_id`, a.`assigned_at`, a.`confirmed_at`, a.`reminded_at`, a.`remind_count`, c.`name` AS customer_name, c.`phone`, p.`full_name` AS recipient_name"
			. " FROM `{$this->tbl}` a"
			. " JOIN `{$ctbl}` c ON c.`customer_id`=a.`customer_id`"
			. " LEFT JOIN `{$ptbl}` p ON p.`profile_id`=a.`recipient_id`"
			. " WHERE {$cond} ORDER BY a.`assigned_at` DESC LIMIT 300";
		return $dbconn->GetAll($sql);
	}
	// Đếm phiếu do người này giao: tổng / chờ (trong hạn) / đã nhận / quá hạn.
	function countByAssigner($assigned_by){
		global $dbconn;
		$assigned_by = (int) $assigned_by;
		$out = array('total' => 0, 'pending' => 0, 'confirmed' => 0, 'overdue' => 0);
		if($assigned_by <= 0){
			return $out;
		}
		$cutoff = time() - self::OVERDUE_SECONDS;
		$r = $dbconn->GetRow("SELECT COUNT(*) AS total,"
			. " SUM(CASE WHEN `confirmed_at`=0 AND `assigned_at`>={$cutoff} THEN 1 ELSE 0 END) AS pending,"
			. " SUM(CASE WHEN `confirmed_at`>0 THEN 1 ELSE 0 END) AS confirmed,"
			. " SUM(CASE WHEN `confirmed_at`=0 AND `assigned_at`<{$cutoff} THEN 1 ELSE 0 END) AS overdue"
			. " FROM `{$this->tbl}` WHERE `assigned_by`={$assigned_by} AND `is_trash`=0");
		if(!empty($r)){
			$out['total'] = (int) $r['total'];
			$out['pending'] = (int) $r['pending'];
			$out['confirmed'] = (int) $r['confirmed'];
			$out['overdue'] = (int) $r['overdue'];
		}
		return $out;
	}
	// Phiếu CHỜ do CHÍNH người này giao trên 1 khách (IDOR gate cho phân lại/thu hồi/nhắc). Trả row hoặc array() rỗng.
	function findPendingByGiver($customer_id, $giver_id){
		global $dbconn;
		$customer_id = (int) $customer_id;
		$giver_id = (int) $giver_id;
		if($customer_id <= 0 || $giver_id <= 0){
			return array();
		}
		$r = $dbconn->GetRow("SELECT `assign_id`,`recipient_id`,`assigned_at`,`remind_count` FROM `{$this->tbl}` WHERE `customer_id`={$customer_id} AND `assigned_by`={$giver_id} AND `confirmed_at`=0 AND `is_trash`=0 LIMIT 1");
		return !empty($r) ? $r : array();
	}
	// Thu (trash) phiếu CHỜ do người này giao trên 1 khách — dùng khi phân lại / thu hồi.
	function trashPendingByGiver($customer_id, $giver_id){
		global $dbconn;
		$customer_id = (int) $customer_id;
		$giver_id = (int) $giver_id;
		if($customer_id <= 0 || $giver_id <= 0){
			return 0;
		}
		$dbconn->Execute("UPDATE `{$this->tbl}` SET `is_trash`=1 WHERE `customer_id`={$customer_id} AND `assigned_by`={$giver_id} AND `confirmed_at`=0 AND `is_trash`=0");
		return 1;
	}
	// Ghi nhận "đã nhắc" người nhận (bump remind_count + reminded_at) trên phiếu CHỜ do người này giao. Trả số lần nhắc mới, 0 nếu không có phiếu.
	function markReminded($customer_id, $giver_id){
		global $dbconn;
		$customer_id = (int) $customer_id;
		$giver_id = (int) $giver_id;
		$pend = $this->findPendingByGiver($customer_id, $giver_id);
		if(empty($pend)){
			return 0;
		}
		$now = time();
		$dbconn->Execute("UPDATE `{$this->tbl}` SET `reminded_at`={$now}, `remind_count`=`remind_count`+1 
			WHERE `customer_id`=" . (int) $customer_id . " AND `assigned_by`=" . (int) $giver_id . " AND `confirmed_at`=0 AND `is_trash`=0");
		return (int) $pend['remind_count'] + 1;
	}
}
