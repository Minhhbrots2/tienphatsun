<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxCMS IS NOT FREE SOFTWARE ----------------    # ||
|| #################################################################### ||
\*======================================================================*/
class Setting extends dbBasic{
	function __construct(){
		$this->pkey = "setting_id";
		$this->tbl = DB_PREFIX."setting";
	}
	function getCode($setting_id){
		global $core, $clsISO;
		$oneSetting = $this->getOne($setting_id,"more_information");
		$more_information = $oneSetting['more_information'];
		$more_information = $clsISO->to_array_json($more_information);
		return $core->get_field($more_information, "setting_code", "");
	}
	function getTitle($setting_id, $oDataTable = array()) {
        global $_LANG_ID;
		if(!isset($oDataTable['title'])){
			$oDataTable = $this->getOne($setting_id, "title");
		}
        return $oDataTable['title'];
    }
	function getTitleArray($ids, $data = array()){
		$titles = "";
		if(!empty($ids)){
			$tmp = array();
			$list = $this->getAll("{$this->pkey} in (".implode(",", $ids).")", "title");
			if(!empty($list)){
				foreach($list as $item){
					$tmp[] = $item['title'];
				}
				unset($list);
			}
			$titles = implode(', ', $tmp);
		}
		return $titles;
	}
	function getLabel($setting_id, $oDataTable = array(), $class="", $shorted = false){
		global $core,$dbconn,$clsISO;
		if((int) $setting_id > 0){
			if(empty($oDataTable)){
				$oDataTable = $this->getOne($setting_id,"`title`,`more_information`");
			}
			$more_information = $oDataTable['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$title = ($shorted == true) ? $core->get_field($more_information, "setting_code", "") : $oDataTable['title'];
			$bgcolor = $core->get_field($more_information, "bgcolor", "");
			$textcolor = $core->get_field($more_information, "textcolor", "");
			return '<label class="badge_status badge'.$class.'" style="background:'.$bgcolor.'">'.$title.'</label>';
		} else {
			return '<label class="badge_status badge" style="background:#cbcbcb">Chưa rõ</label>';
		}
	}
    function getSelectBySettingOrigin($type, $for_id=0, $selected = 0, $title="") {
		global $core, $clsISO;
		if(!empty($title)){
			$html = '<option value="0">'.$title.'</option>';
		} else {
			$html = '<option value="0">'.$core->get_Lang('select').'</option>';
		}
		$field = "{$this->pkey},title";
		$tmp = $this->getAll("_type='{$type}' and for_id='{$for_id}' order by order_no ASC", $field);
		if (!empty($tmp)) {
			foreach ($tmp as $item) {
				$html .= '<option value="'. $item[$this->pkey]. '"' .($selected==$item[$this->pkey]?' selected':'').'>'.$item['title'].'</option>';
			}
			unset($tmp);
		}
		return $html;
	}
	function getOptionOrigin($type, $for_id=0, $selected = 0, $title="") {
		global $core, $clsISO;
		if(!empty($title)){
			$html = '<option value="0">'.$title.'</option>';
		} else {
			$html = '<option value="0">'.$core->get_Lang('select').'</option>';
		}
		$field = "{$this->pkey},title";
		$tmp = $this->getAll("_type='{$type}' and for_id='{$for_id}' order by order_no ASC", $field);
		if (!empty($tmp)) {
			foreach ($tmp as $item) {
				$html .= '<option value="'. $item[$this->pkey]. '"' .($selected==$item[$this->pkey]?' selected':'').'>'.$item['title'].'</option>';
			}
			unset($tmp);
		}
		return $html;
	}
    function getSelectSingleSetting($type, $parent_id = 0, $selected = 0, $cached = false) {
        global $core, $clsISO;
        #
		$field = "{$this->pkey},title";
        $tmp = $this->getAll("is_trash=0 and _type='{$type}' and parent_id='{$parent_id}' order by order_no ASC", $field);
        $html = '<option value="">Lựa chọn</option>';
        if (!empty($tmp)) {
            foreach ($tmp as $val) {
                $html.='<option value="'.$val[$this->pkey].'"'.($selected==$val[$this->pkey]?' selected':'').'>'.$val['title'].'</option>';
            }
        }
        return $html;
    }
	function getOItems($_type, $for_id = 0, $field=""){
		$cond = "`is_trash`='0' and `_type`='{$_type}'";
		if(intval($for_id) > 0) $cond .= " and `for_id`='{$for_id}'";
		$field = "{$this->pkey},`title`,`for_id`".(!empty($field)?",{$field}" : "");
		return $this->getAllCache("{$cond} order by `order_no` ASC", $field);
	}
	function getCacheItems($_type){
		$cachedName = sprintf('setting_%s_cached', $_type);
		$clsCache = new Cache();
		$data = array();
		// Cache-aside: nếu key bị expire/evict thì tự rebuild lại Redis.
		try{
			$data = $clsCache->get($cachedName);
		} catch(Exception $e){
			$data = array();
		}
		if(!empty($data) && is_array($data)){
			return $data;
		}
		$data = $this->getOItems($_type, 0, "`_type`,`parent_id`,`more_information`");
		if(!empty($data) && is_array($data)){
			try{
				$clsCache->put($cachedName, $data, CACHE_LIFETIME);
			} catch(Exception $e){}
		}
		return !empty($data) ? $data : array();
	}
	function getArraySearchByKey($_type,$id=0){
		global $core, $dbconn, $clsISO;
		$data = array();
		$tmp = $this->getCacheItems($_type);
		if(!empty($tmp)){
			foreach($tmp as $key => $val) {
				$setting_id = $val[$this->pkey];
				$more_information = $val['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$data[$setting_id] = array(
					$this->pkey		=>	$setting_id,
					"_type"			=>	$val['_type'],
					"title"			=>	$val['title'],
					"slug"			=>	$val['slug'],
					"setting_code"	=>	$more_information['setting_code'],
					"bgcolor"		=>	$more_information['bgcolor'],
					"textcolor"		=>	$more_information['textcolor'],
					"for_id"		=>	$val['for_id'],
					"parent_id"		=>	$val['parent_id'],
					"more_information"	=>	$more_information,
				);
			}		
		}	
		if(!empty($id)) {
			return $data[$id];
		}
		return $data;
	}
    function getSelectBySetting($_type, $setting_id = 0, $title="", $cached=false, $is_event=false) {
        global $core, $clsISO;
		$html = '';
		if(IS_ADMIN_PAGE==1){
			$html = '<option value="0">'.(!empty($title) ? $title : 'Lựa chọn').'</option>';
		}
		if($cached == true){
			$tmp = $this->getCacheItems($_type);
		} else {
			$field = "{$this->pkey},`title`";
			$sort_type = ($_type=='_PERIOD') ? "DESC" : "ASC";
			$cond = "`is_trash`=0 AND `_type`='{$_type}'";
			$tmp = $this->getAll("{$cond} AND `parent_id`=0 ORDER BY `order_no` {$sort_type}", $field);
		}
        if(!empty($tmp)) { $i = 0;
            foreach ($tmp as $key => $val) {
                $html.='<option value="'.$val[$this->pkey].'"'.($val[$this->pkey]==$setting_id?' selected':'').'>'.$this->getTitle($val[$this->pkey], $val).'</option>';
                ++$i;
            }
			unset($tmp);
        }
        return $html;
    }
	function makeSelect($type, $selected=0, $cached = false) {
        global $core, $clsISO;
		if($cached == true){
			$tmp = $this->getCacheItems($type);
		} else {
			$field = "{$this->pkey},title";
			$tmp = $this->getAll("`is_trash`=0 AND `_type`='{$type}' ORDER BY `order_no` ASC", $field);
		}
		$html = '<option value="">Lựa chọn</option>';
        if (!empty($tmp)) {
            foreach($tmp as $val) {
                $html.='<option value="'.$val[$this->pkey].'"'.($selected==$val[$this->pkey]?' selected':'').'>
					'.$val['title'].'
				</option>';
            }
			unset($tmp);
        }
        return $html;
    }
	function getItems($setting_type, $setting_id=0, $field="*"){
		$cond = "`_type`='{$setting_type}'";
		if(intval($setting_id) > 0) 
			$cond .= " and `parent_id`='{$setting_id}'";
		return $this->getAll("{$cond} order by `order_no` ASC", $field);
	}
    function getListOption($setting_type="", $setting_id = '',$parent_id=0) {
        global $core, $dbconn, $clsISO;
		$html = ""; $arrOptions = array();
        $this->makeOption($parent_id, $setting_type, $setting_id, 0, $arrOptions);
		if(!empty($arrOptions)){
			foreach($arrOptions as $k => $v) {
				$selected = ($k == $setting_id) ? ' selected="selected"' : '';
				$html .= '<option value="'.$k.'"'.$selected.'>'.$v.'</option>';
			}
			unset($arrOptions);
		}
        return $html;
    }
    function makeOption($parent_id = 0, $setting_type = '', $selectedid = "", $level = 0, &$arrHtml) {
        global $core, $dbconn, $clsISO;
        $cond = "`is_trash`=0 AND `_type`='{$setting_type}' AND `parent_id`='{$parent_id}'";
		$field = "{$this->pkey},`title`,`more_information`";
        $arrListCat = $this->getAll($cond. " order by `order_no` ASC", $field);
        if (!empty($arrListCat)) {
            foreach ($arrListCat as $k => $v) {
				$value = $v[$this->pkey];
                $more_information = $v['more_information'];
			    $more_information = $clsISO->to_array_json($more_information);
                $selected = ($v[$this->pkey] == $selectedid) ? "selected" : "";
                $option = @str_repeat("|---- ", $level). sprintf('(%s) %s', $more_information['setting_code'], $v['title']);
                $arrHtml[$value] = $option;
                $this->makeOption($v[$this->pkey], $setting_type, $selectedid, $level + 1, $arrHtml);
            }
            return "";
        } else {
            return "";
        }
    }
	/**
	 * Bản đồ chi nhánh + phòng họp, dựng một lần cho cả request.
	 * Chi nhánh chưa cấu hình phòng họp nào thì sinh một phòng mặc định mang tên chi nhánh
	 * với room_id = 0 — không chèn bản ghi thật vào CSDL.
	 * Trả về: offices[office_id], rooms[office_id][], index["office_id:room_id"].
	 */
	function getMeetingRoomMap(){
		global $core, $clsISO;
		static $_room_map = null;
		if($_room_map !== null) return $_room_map;
		$offices = $rooms_by_office = $index = array();
		# Đọc trực tiếp (không qua cache liên-request) để admin đổi chi nhánh/phòng thấy hiệu lực
		# ngay, tránh bẫy Redis 5' + ADODB 24h. Dữ liệu nhỏ, đã có static cache trong một request.
		# Chi nhánh
		$tmp = $this->getAll("`is_trash`=0 AND `_type`='_OFFICE' order by `order_no` ASC", "`{$this->pkey}`,`title`,`more_information`");
		if(!empty($tmp)){
			foreach($tmp as $val){
				$office_id = (int) $val[$this->pkey];
				$more_information = $clsISO->to_array_json($val['more_information']);
				$offices[$office_id] = array(
					'office_id'	=> $office_id,
					'title'		=> $val['title'],
					'bgcolor'	=> $core->get_field($more_information, 'bgcolor', ''),
					'is_active'	=> (int) $core->get_field($more_information, 'is_active', 1)
				);
			}
			unset($tmp);
		}
		# Phòng họp thật, gắn về chi nhánh qua more_information.office_id
		$tmp = $this->getAll("`is_trash`=0 AND `_type`='_MEETING_ROOM' order by `order_no` ASC", "`{$this->pkey}`,`title`,`more_information`");
		if(!empty($tmp)){
			foreach($tmp as $val){
				$more_information = $clsISO->to_array_json($val['more_information']);
				$office_id = (int) $core->get_field($more_information, 'office_id', 0);
				# Phòng trỏ tới chi nhánh đã xoá thì bỏ qua, tránh sinh nhóm rỗng không tên.
				if(!isset($offices[$office_id])) continue;
				$rooms_by_office[$office_id][] = array(
					'office_id'		=> $office_id,
					'room_id'		=> (int) $val[$this->pkey],
					'title'			=> $val['title'],
					'office_title'	=> $offices[$office_id]['title'],
					'is_default'	=> 0
				);
			}
			unset($tmp);
		}
		foreach($offices as $office_id => $office){
			if(empty($rooms_by_office[$office_id])){
				$rooms_by_office[$office_id][] = array(
					'office_id'		=> $office_id,
					'room_id'		=> 0,
					'title'			=> $office['title'],
					'office_title'	=> $office['title'],
					'is_default'	=> 1
				);
			}
			foreach($rooms_by_office[$office_id] as $room){
				$index[$office_id.':'.$room['room_id']] = $room;
			}
		}
		$_room_map = array(
			'offices'	=> $offices,
			'rooms'		=> $rooms_by_office,
			'index'		=> $index
		);
		return $_room_map;
	}
	/**
	 * Chi nhánh của một phòng ban, đọc từ more_information.office_id.
	 * Phòng ban không tự gắn thì đi ngược lên parent_id cho tới khi tìm thấy — cây phòng ban
	 * sâu 4 tầng và chi nhánh thường chỉ được gắn ở tầng khối.
	 * Cả cây nạp một lần vào biến static nên việc đi lên không phát sinh truy vấn.
	 */
	function getOfficeIdByDepartment($department_id){
		global $core, $clsISO;
		static $_dep_tree = null;
		static $_resolved = array();
		$department_id = (int) $department_id;
		if($department_id <= 0) return 0;
		if(isset($_resolved[$department_id])) return $_resolved[$department_id];
		if($_dep_tree === null){
			$_dep_tree = array();
			$clsProperty = new Property();
			$tmp = $clsProperty->getArraySearchByKey("_DEPARTMENT");
			if(!empty($tmp)){
				foreach($tmp as $val){
					$more_information = $clsISO->to_array_json($val['more_information']);
					$_dep_tree[(int) $val[$clsProperty->pkey]] = array(
						'parent_id'	=> (int) $val['parent_id'],
						'office_id'	=> (int) $core->get_field($more_information, 'office_id', 0)
					);
				}
				unset($tmp);
			}
		}
		$office_id = 0;
		$current_id = $department_id;
		# Chặn 10 tầng: cây thật chỉ sâu 4, giới hạn này để parent_id trỏ vòng không treo vòng lặp.
		for($i = 0; $i < 10 && $current_id > 0; ++$i){
			if(!isset($_dep_tree[$current_id])) break;
			if($_dep_tree[$current_id]['office_id'] > 0){
				$office_id = $_dep_tree[$current_id]['office_id'];
				break;
			}
			$current_id = $_dep_tree[$current_id]['parent_id'];
		}
		$_resolved[$department_id] = $office_id;
		return $office_id;
	}
	/**
	 * Phòng họp cần chọn sẵn cho một nhân sự.
	 * Thứ tự ưu tiên: phòng chỉ định (người dùng đã chọn) -> phòng của chi nhánh suy từ phòng ban
	 * -> phòng của văn phòng mặc định (config _OFFICE_CALENDAR_DEFAULT_ID) -> phòng đầu tiên còn lại.
	 * Trả về đủ office_id và room_id để nơi gọi ghi thẳng vào default_calendar, không phải tra lại.
	 */
	function resolveMeetingRoom($department_id = 0, $room_id = 0){
		$map = $this->getMeetingRoomMap();
		$room_id = (int) $room_id;
		if($room_id > 0){
			foreach($map['index'] as $room){
				if($room['room_id'] == $room_id) return $room;
			}
		}
		$office_id = $this->getOfficeIdByDepartment($department_id);
		if($office_id > 0 && !empty($map['rooms'][$office_id])){
			return $map['rooms'][$office_id][0];
		}
		# Neo mặc định vào ID VĂN PHÒNG trụ sở, lấy từ default_configuration để đổi được
		# trong admin mà không phải sửa file rồi upload. Neo vào văn phòng chứ không vào một
		# phòng họp cụ thể: văn phòng luôn có ít nhất phòng ảo nên bước này không bao giờ rỗng,
		# kể cả khi trụ sở chưa cấu hình phòng họp nào, và không chết khi phòng bị xoá.
		static $_hq_office_id = null;
		if($_hq_office_id === null){
			# Configuration::getValue() không cache, giữ lại để mỗi request chỉ đọc một lần.
			$clsConfiguration = new Configuration();
			$_hq_office_id = (int) $clsConfiguration->getValue('_OFFICE_CALENDAR_DEFAULT_ID', 0);
		}
		if($_hq_office_id > 0 && !empty($map['rooms'][$_hq_office_id])){
			return $map['rooms'][$_hq_office_id][0];
		}
		foreach($map['rooms'] as $office_rooms){
			if(!empty($office_rooms)) return $office_rooms[0];
		}
		return array(
			'office_id' => 0, 'room_id' => 0,
			'title' => '', 'office_title' => '', 'is_default' => 1
		);
	}
}
?>
