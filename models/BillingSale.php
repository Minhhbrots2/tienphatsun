<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/**
 * BillingSale — danh sách sale tham gia 1 giao dịch (co-sale / chia hoa hồng).
 * 1 billing = 1 căn; mỗi sale tham gia là 1 dòng ở đây (staff + tỷ lệ chia + phần giá trị).
 * Cho phép mỗi sale (kể cả sale phụ) thấy được GD của mình.
 */
class BillingSale extends dbBasic {
	function __construct(){
		$this->pkey = "billing_sale_id";
		$this->tbl = DB_PREFIX . "billing_sale";
	}
	function getMaxId(){
		$res = $this->getAll("1=1 order by `{$this->pkey}` desc limit 0,1");
		return intval($res[0][$this->pkey]) + 1;
	}
	/** Danh sách sale của 1 GD (sale chính + tỷ lệ cao lên trước). */
	function getByBilling($billing_id){
		$billing_id = (int) $billing_id;
		return $this->getAll("`billing_id`='{$billing_id}' order by `is_primary` desc, `share_ratio` desc");
	}
	/** Xoá hết sale của 1 GD (dùng khi lưu lại từ form). */
	function deleteByBilling($billing_id){
		$billing_id = (int) $billing_id;
		return $this->deleteByCond("`billing_id`='{$billing_id}'");
	}
	/**
	 * Ghi lại toàn bộ danh sách sale cho 1 GD: xoá cũ + thêm mới.
	 * $shares: mảng các dòng [staff_id, seller_name, department_id, regional_id, share_ratio, share_value, is_primary].
	 */
	function saveShares($billing_id, $shares){
		$billing_id = (int) $billing_id;
		$this->deleteByBilling($billing_id);
		if(empty($shares)){
			return 0;
		}
		$n = 0;
		foreach($shares as $s){
			$s['billing_id'] = $billing_id;
			$s[$this->pkey] = $this->getMaxId();
			if(!isset($s['reg_date'])){
				$s['reg_date'] = time();
			}
			if($this->insert($s)){
				$n++;
			}
		}
		return $n;
	}
}
