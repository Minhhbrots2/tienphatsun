<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/**
 * ApartmentMedia — thư viện ảnh căn hộ theo LOẠI CĂN (_BEDROOM) × TYPE (vd "Type 5"),
 * cho mỗi Tòa (building) hoặc Phân khu (block). 1 dòng = 1 Type-card.
 * Bảng default_apartment_media (DB chính):
 *   parent_id, parent_type('building'|'block'), bedroom_id(_BEDROOM), type_label(free text),
 *   media_boc_mai/folder_boc_mai (Layout bóc mái), media_noi_that/folder_noi_that (Nội thất),
 *   media = JSON [{source,type,ref,url,thumb,name}] (đồng dạng media Tiến độ).
 * Đọc/hiển thị: ƯU TIÊN Tòa → fallback Phân khu (getEffective).
 */
class ApartmentMedia extends dbBasic{
	function __construct(){
		$this->pkey = "id";
		$this->tbl  = DB_PREFIX."apartment_media";
	}
	// Tất cả Type-card của 1 parent (tòa hoặc phân khu).
	function getByParent($parent_id, $parent_type){
		$parent_id = (int)$parent_id; $parent_type = addslashes($parent_type);
		if($parent_id <= 0) return array();
		return $this->getAll("parent_id={$parent_id} and parent_type='{$parent_type}' order by bedroom_id ASC, order_no ASC, id ASC");
	}
	// Ưu tiên Tòa → fallback Phân khu. Trả ['rows'=>[], 'source'=>'building'|'block'|''].
	function getEffective($building_id, $block_id){
		$rows = $this->getByParent($building_id, 'building');
		if(!empty($rows)) return array('rows'=>$rows, 'source'=>'building');
		$rows = $this->getByParent($block_id, 'block');
		if(!empty($rows)) return array('rows'=>$rows, 'source'=>'block');
		return array('rows'=>array(), 'source'=>'');
	}
	// Chuẩn hóa 1 chuỗi JSON media (rỗng/không hợp lệ -> '').
	function normMedia($json){
		$arr = !empty($json) ? json_decode($json, true) : array();
		if(!is_array($arr)) $arr = array();
		return !empty($arr) ? json_encode($arr, JSON_UNESCAPED_UNICODE) : '';
	}
	// Upsert 1 Type-card. row_id>0 = update, ngược lại insert. Trả id đã ghi (0 nếu lỗi).
	function saveType($parent_id, $parent_type, $row_id, $data, $user_id=0){
		$row_id = (int)$row_id;
		$now = time();
		$payload = array(
			'bedroom_id'      => (int)($data['bedroom_id'] ?? 0),
			'type_label'      => trim($data['type_label'] ?? ''),
			'media_boc_mai'   => $this->normMedia($data['media_boc_mai'] ?? ''),
			'folder_boc_mai'  => trim($data['folder_boc_mai'] ?? ''),
			'media_noi_that'  => $this->normMedia($data['media_noi_that'] ?? ''),
			'folder_noi_that' => trim($data['folder_noi_that'] ?? ''),
			'order_no'        => (int)($data['order_no'] ?? 0),
			'upd_date'        => $now,
			'user_id'         => (int)$user_id,
		);
		if($payload['bedroom_id'] <= 0) return 0;
		if($row_id > 0){
			$this->updateOne($row_id, $payload);
			return $row_id;
		}
		$id = $this->getMaxId();
		$payload['id']          = $id;
		$payload['parent_id']   = (int)$parent_id;
		$payload['parent_type'] = $parent_type;
		$payload['reg_date']    = $now;
		$this->insert($payload);
		return $id;
	}
}
?>
