<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
class MemberSource extends DbBasic{
	function __construct(){
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."member_source";
	}
	// source 'MOC' -> member.role_id (_PACKAGE) ; 'MF' -> member.package_id (_MF_PACKAGE)
	function _packageColumn($source){
		return ($source === 'MF') ? 'package_id' : 'role_id';
	}
	function _normSource($source){
		return ($source === 'MF') ? 'MF' : 'MOC';
	}
	function _expectedType($source){
		return ($source === 'MF') ? '_MF_PACKAGE' : '_PACKAGE';
	}
	// Quyền mặc định của GÓI mà member đang giữ (map code=>1). Đọc từ property.more_information['permiss_mod'].
	function getPackageDefault($member_id, $source){
		global $clsISO;
		$source = $this->_normSource($source);
		$clsMember = new Member();
		$clsProperty = new Property();
		$package_id = (int) $clsMember->getOneField($this->_packageColumn($source), (int) $member_id);
		if($package_id <= 0) return array();
		$oPkg = $clsProperty->getOne($package_id, "property_type,more_information");
		// Guard: chỉ tính default khi package đúng loại (_MF_PACKAGE/_PACKAGE) — tránh leak quyền MOC↔MF khi data chưa chuẩn
		if(empty($oPkg) || $oPkg['property_type'] !== $this->_expectedType($source)) return array();
		$mi = $clsISO->to_array_json($oPkg['more_information']);
		$pm = (isset($mi['permiss_mod']) && is_array($mi['permiss_mod'])) ? $mi['permiss_mod'] : array();
		$out = array();
		foreach($pm as $code => $v){ if((int) $v === 1) $out[$code] = 1; }
		return $out;
	}
	// Override RIÊNG của cá nhân (diff so với gói): code=>1 (thêm) / code=>0 (gỡ). [] nếu chưa tùy chỉnh.
	function getOverride($member_id, $source){
		global $clsISO;
		$source = $this->_normSource($source);
		$member_id = (int) $member_id;
		$row = $this->getByCond("`member_id`='{$member_id}' AND `source`='{$source}' LIMIT 0,1", "{$this->pkey},permiss_mod");
		if(empty($row)) return array();
		$map = $clsISO->to_array_json($row['permiss_mod']);
		return is_array($map) ? $map : array();
	}
	// Quyền HIỆU LỰC = mặc định gói ⊕ override cá nhân. Trả map code=>1 (chỉ quyền được cấp).
	function getEffective($member_id, $source){
		$eff = $this->getPackageDefault($member_id, $source);
		foreach($this->getOverride($member_id, $source) as $code => $v){
			if((int) $v === 1) $eff[$code] = 1;
			else unset($eff[$code]);
		}
		return $eff;
	}
	// Tiện ích kiểm tra 1 quyền.
	function can($member_id, $source, $code){
		$eff = $this->getEffective($member_id, $source);
		return isset($eff[$code]) ? 1 : 0;
	}
	// Lưu phân quyền cá nhân: nhận tập code ĐƯỢC TICK + toàn bộ code render được, tính DIFF vs gói rồi upsert.
	function savePermiss($member_id, $source, $checked_codes, $all_codes){
		$source = $this->_normSource($source);
		$member_id = (int) $member_id;
		if($member_id <= 0) return false;
		$default = $this->getPackageDefault($member_id, $source);
		$checked = array();
		foreach((array) $checked_codes as $c){ if($c !== '') $checked[$c] = 1; }
		$override = array();
		foreach((array) $all_codes as $code){
			if($code === '') continue;
			$isChecked = isset($checked[$code]) ? 1 : 0;
			$isDefault = isset($default[$code]) ? 1 : 0;
			if($isChecked !== $isDefault) $override[$code] = $isChecked; // 1=thêm ngoài gói, 0=gỡ khỏi gói
		}
		$json = json_encode($override, JSON_UNESCAPED_UNICODE);
		$row = $this->getByCond("`member_id`='{$member_id}' AND `source`='{$source}' LIMIT 0,1", $this->pkey);
		if(!empty($row)){
			return $this->updateOne($row[$this->pkey], array('permiss_mod' => $json, 'upd_date' => time()));
		}
		return $this->insert(array(
			'member_id'   => $member_id,
			'source'      => $source,
			'permiss_mod' => $json,
			'reg_date'    => time(),
			'upd_date'    => time()
		));
	}
	function sync(){
		global $core, $dbconn, $clsISO;
		$clsMember = new Member();
		$tmp = $clsMember->getAll("1=1 order by {$clsMember->pkey} ASC");
		// $clsISO->print_pre($tmp); die();
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$tmp2 = $this->getByCond("`source`='MF' AND `member_id`='".$val[$clsMember->pkey]."'");
				if(!empty($tmp2)){} else {
					$permiss_mod = array();
					$this->insert(array(
						'member_id' => $val[$clsMember->pkey],
						'source' => 'MF',
						'permiss_mod' => json_encode($permiss_mod, JSON_UNESCAPED_UNICODE ),
						'reg_date' => time(),
						'upd_date' => time()
					));
				} 
			}
		}
	}
}