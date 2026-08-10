<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
class Property extends dbBasic {
    function __construct() {
        $this->pkey = "property_id";
        $this->tbl = DB_PREFIX."property";
    }
    function getMaxId() {
		$res = $this->getAll("1=1 order by `{$this->pkey}` desc limit 0,1");
        return intval($res[0][$this->pkey]) + 1;
    }
    function getMaxOrder($property_type) {
        $res = $this->getAll("1=1 and `property_type`='{$property_type}' order by `order_no` desc limit 0,1");
        return intval($res[0]['order_no']) + 1;
    }
	function getLink($link_type, $params){
		if($link_type == 'stock'){
			return sprintf('/project/p%s/bl%s.html', $params['project_id'], $params['block_id']);
		} else if($link_type == 'stock_layout'){
			return sprintf('/project/p%s/bl%s/layout.html', $params['project_id'], $params['block_id']);
		}
	}
	function checkContain($haystack, $needle) {
        $pos = strpos($haystack, $needle);
        if ($pos === false) {
            return 0;
        } else {
            return 1;
        }
    }
	function getCode($property_id, $oDataTable = array()) {
        global $_LANG_ID;
		if(!isset($oDataTable['property_code'])){
			$oDataTable = $this->getOne($property_id, "property_code");
		}
        return $oDataTable['property_code'];
    }
    function getTitle($property_id, $oDataTable = array()) {
        global $core, $dbconn, $_LANG_ID;
		if(!isset($oDataTable['title'])){
			$oDataTable = $this->getOne($property_id, "title");
		}
        return $oDataTable['title'];
    }
	function getTitleCache($property_type, $property_id, $def = ""){
		global $_LANG_ID;
		$title = $def;
		$tmp = $this->getCacheItems($property_type);
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				if($val[$this->pkey] == $property_id){
					$title = $val['title'];
					break;
				}
			}
		}
		if(empty($title)) 
			$title = $this->getTitle($property_id);
		return $title;
	}
	function getFieldCache($field = "", $property_type, $property_id, $def = ""){
		global $_LANG_ID;
		$value = "";
		$tmp = $this->getCacheItems($property_type);
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				if($val[$this->pkey] == $property_id){
					$value = $val[$field];
					break;
				}
			}
		}
		// Return
		return $value;
	}
	function getTitleQR($property_id, $oDataTable = array()) {
        global $_LANG_ID;
		if(!isset($oDataTable['title_vn'])){
			$oDataTable = $this->getOne($property_id, "title_vn,title");
		}
        return !empty($oDataTable['title_vn']) ? $oDataTable['title_vn'] : $oDataTable['title'];
    }
	function getTitleArray($ids, $label=false, $data = array()){
		$titles = "";
		if(!empty($ids)){
			$tmp = array();
			if(!empty($data)){
				foreach($ids as $id){
					if($id > 0 && isset($data[$id])){
						$tmp[] = $data[$id];
					}
				}
			} else {
				$list = $this->getAll("{$this->pkey} in (".implode(",", $ids).")", "title");
				if(!empty($list)){
					foreach($list as $item){
						$tmp[] = $item['title'];
					}
					unset($list);
				}
			}
			if(!$label){
				$titles = implode(', ', $tmp);
			} else {
				$titles = "";
				foreach($tmp as $tit){
					$titles .= '<label class="label label-default ml-1">'.$tit.'</label>';
				}
			}
		}
		return $titles;
	}
	function getLabel($property_id, $class="", $shorted = false){
		global $core,$dbconn,$clsISO;
		if((int) $property_id > 0){
			$oneProperty = $this->getOne($property_id,"`property_code`,`title`,`bgcolor`");
			return '<label class="badge_status badge'.$class.'" style="background:'.$oneProperty['bgcolor'].'">
				'.($shorted==true ? $oneProperty['property_code'] : $oneProperty['title']).'
			</label>';
		} else {
			return '<label class="badge_status badge" style="background:#cbcbcb">
				Chưa rõ
			</label>';
		}
	}
	function getLabelV2($property_id, $oDataTable=array(), $class="", $shorted = false){
		global $core,$dbconn,$clsISO;
		if((int) $property_id > 0){
			if(empty($oDataTable)){

				$oDataTable = $this->getOne($property_id,"`property_code`,`title`,`bgcolor`");
			}
			return '<label class="badge_status badge'.$class.'" style="background:'.$oDataTable['bgcolor'].'">
				'.($shorted==true ? $oDataTable['property_code'] : $oDataTable['title']).'
			</label>';
		} else {
			return '<label class="badge_status badge" style="background:#cbcbcb">
				Chưa rõ
			</label>';
		}
	}
	function getTextColor($property_id, $oDataTable = array()){
		global $core,$dbconn,$clsISO;
		if($property_id > 0){
			if(empty($oDataTable)){
				$oDataTable = $this->getOne($property_id,"`title`,`bgcolor`,`textcolor`");
			}
			$textcolor = isset($oDataTable['textcolor']) ? $oDataTable['textcolor'] : $oDataTable['bgcolor'];
			return '<span style="color:'.$textcolor.'">'.$oDataTable['title'].'</span>';
		} else {
			return '<span class="text-muted">
				<i class=\'bx bx-info-circle\'></i> Chưa có
			</span>';
		}
	}
	function getLoaiHinh($property_id, $oDataTable = array()){
		if((int) $property_id==0){
			return 'Mới CĐT';
		} else {
			return $this->getTitle($property_id, $oDataTable);
		}
	}
	function getValueField($property_id, $field, $oDataTable = array()){
		global $_LANG_ID;
		if(!isset($oDataTable[$field])){
			$oDataTable = $this->getOne($property_id, $field);
		}
        return $oDataTable[$field];
	}
	function getFieldValue($property_id, $field){
		global $core, $dbconn, $clsISO;
		$more_information = $this->getOneField('more_information', $property_id);
		$more_information = $clsISO->to_array_json($more_information);
		return $core->get_field($more_information, $field, []);
	}
	function getRootId($cat_id){
		$one = $this->getOne($cat_id, "parent_id");
		if($one['parent_id'] == 0)
			return $cat_id;
		return $this->getRootId($one['parent_id']);
	}
	#- Vùng kinh doanh: id phòng ban tổ tiên (tính cả chính nó) có more_information.is_business_area=1; 0 nếu không thuộc vùng KD nào.
	#- Cache tĩnh dùng lại trong cùng request (backfill nhiều hồ sơ chỉ đọc mỗi phòng ban 1 lần).
	function getBusinessAreaId($department_id){
		global $clsISO;
		static $cache = array();
		$cur = (int) $department_id;
		$guard = 0;
		while($cur > 0 && $guard++ < 20){
			if(!array_key_exists($cur, $cache)){
				$one = $this->getOne($cur, "`parent_id`,`more_information`");
				if(empty($one)){
					$cache[$cur] = null;
				} else {
					$mi = $clsISO->to_array_json($one['more_information']);
					$cache[$cur] = array('parent' => (int) $one['parent_id'], 'biz' => !empty($mi['is_business_area']));
				}
			}
			if($cache[$cur] === null) break;
			if($cache[$cur]['biz']) return $cur;
			$cur = $cache[$cur]['parent'];
		}
		return 0;
	}
	function getIntro($pval) {
        global $_LANG_ID;
        return $this->getOneField('intro', $pval);
    }
    function getContent($pval) {
        global $_LANG_ID;
        return $this->getOneField('content', $pval);
    }
    function getBySlug($slug, $type) {
        $res = $this->getAll("is_trash=0 and type='$type' and (slug='".$slug."')");
        return $res[0]['property_id'];
    }
    function getImage($property_id) {
        global $_LANG_ID;
        $one = $this->getOne($property_id);
        if ($one['image'] != '')
            return $one['image'];
    }
    function getSelectByProperty($property_type, $property_id = 0, $title="", $cached=false, $is_event=false) {
        global $core, $clsISO;
		$html = '';
		if(IS_ADMIN_PAGE==1){
			$html = '<option value="0">'.(!empty($title) ? $title : 'Lựa chọn').'</option>';
		}
		if($cached == true){
			$all = $this->getCacheItems($property_type);
		} else {
			$field = "{$this->pkey},property_code,title";
			$sort_type = ($property_type=='_PERIOD') ? "DESC" : "ASC";
			$cond = "`is_trash`=0 AND `is_locked`=0 AND `property_type`='{$property_type}'";
			$all = $this->getAll("{$cond} AND `parent_id`=0 order by `order_no` {$sort_type}", $field);
		}
        if(!empty($all)) { $i = 0;
            foreach ($all as $key => $val) {
                $html.='<option value="'.$val[$this->pkey].'"'.($val[$this->pkey]==$property_id?' selected':'').'>
					'.$this->getTitle($val[$this->pkey], $val).'
				</option>';
				$childs = $this->getAll("{$cond} AND `parent_id`=".$val[$this->pkey]." order by `order_no` {$sort_type}", $field);
				if(!empty($childs)){
					foreach ($childs as $okey => $oval) {
						$html.='<option value="'.$oval[$this->pkey].'"'.($oval[$this->pkey]==$property_id?' selected':'').'>|--'.$this->getTitle($oval[$this->pkey], $oval).'</option>';
					}
					unset($childs);
				}
                ++$i;
            }
			unset($all);
        }
        return $html;
    }
	function makeSelect($property_type, $selected=0, $arr_properies = array()) {
        global $core, $clsISO;
		$html = '<option value="0">Lựa chọn</option>';
		$tmp = $this->getCacheItems($property_type);
        if (!empty($tmp)) {
            foreach ($tmp as $item) {
                $html.='<option value="'.$item[$this->pkey].'"'.($selected==$item[$this->pkey]?' selected':'').'>
					'.$item['title'].'
				</option>';
            }
			unset($tmp);
        }
        return $html;
    }
	function getSelectByPropertyV2($property_type, $arr_selected = array(), $title="", $cached=false, $show="all") {
        global $core, $clsISO,$profile_id;
		$html = '';
		if(IS_ADMIN_PAGE==1){
			if(!empty($title)){
				$html = '<option value="0">'.$title.'</option>';
			} else {
				$html = '<option value="0">'.$core->get_Lang('select').'</option>';
			}
		}
		if($cached == true){
			$list_items = $this->getCacheItems($property_type);
		} else {
			$field = "{$this->pkey},`property_code`,`title`,`parent_id`";
			$cond = "`is_trash`=0 and `property_type`='{$property_type}' and `title`<>''";
			if($property_type=='_AGENCY') $cond.= " and `is_locked`='0'";
			$sort_type = ($property_type=='_PERIOD') ? "DESC" : "ASC";
			$list_items = $this->getAll("{$cond} order by `order_no` {$sort_type}", $field);
		}
        if(!empty($list_items)) { $i = 0;
            foreach ($list_items as $key => $val) {
				if($show == "parent") {
					if($val['parent_id'] == 0 && $val[$this->pkey] != _DEPARTMENT_SALE_ID) {
						$html.='<option data-info="1" value="'.$val[$this->pkey].'"'.($clsISO->checkItemInArray($val[$this->pkey],$arr_selected)?' selected':'').'>'.$this->getTitle($val[$this->pkey], $val).'</option>';
					}else if($val['parent_id'] != 0 && $val['parent_id'] == _DEPARTMENT_SALE_ID ){
						$html.='<option value="'.$val[$this->pkey].'"'.($clsISO->checkItemInArray($val[$this->pkey],$arr_selected)?' selected':'').'>'.$this->getTitle($val[$this->pkey], $val).'</option>';
					}
				}else{
					$html.='<option data-info="1" value="'.$val[$this->pkey].'"'.($clsISO->checkInArray($arr_selected,$val[$this->pkey])?' selected':'').'>'.$this->getTitle($val[$this->pkey], $val).'</option>';
				}                
                ++$i;
            }
			unset($list_items);
        }
        return $html;
    }
	function getSelectOptimizeProperty($type, $selected=0, $arr_properies = array()) {
        global $core, $clsISO;
		if(empty($arr_properies)){
			$field = "{$this->pkey},title";
			$arr_properies =  $this->getAll("`is_trash`=0 AND `property_type`='{$type}' AND `is_locked`='0' ORDER BY `order_no` ASC", $field);
		}
		$html = '<option value="0">'.$core->get_Lang('select').'</option>';
        if (!empty($arr_properies)) {
            foreach ($arr_properies as $item) {
                $html.='<option value="'.$item[$this->pkey].'"'.($selected==$item[$this->pkey]?' selected':'').'>
					'.$item['title'].'
				</option>';
            }
        }
        return $html;
    }
	function getSelectOptimizePropertyNotTile($type, $selected=0, $arr_properies = array()) {
        global $core, $clsISO;
		$html = '';
		if(empty($arr_properies)){
			$field = "{$this->pkey},title";
			$arr_properies =  $this->getAll("property_type='{$type}' order by order_no ASC", $field);
		}
        if (!empty($arr_properies)) {
            foreach ($arr_properies as $item) {

                $html.='<option value="'.$item[$this->pkey].'"'.($selected==$item[$this->pkey]?' selected':'').'>
					'.$item['title'].'
				</option>';
            }
        }
        return $html;
    }
	function getSelectFromSource($arrs, $selected_id=0, $property_type='_BLOCK'){
		$is_block = ($property_type == '_BLOCK');
		$html = sprintf('<option value="0">%s</option>', ($is_block ? 'Chọn phân khu' : 'Chọn dãy'));
		if(empty($arrs)){
			$arrs = $this->getSourceFallback($property_type);
		}
		if(empty($arrs)){
			return $html;
		}
		$sub_key = $is_block ? 'list_blocks' : 'list_ranges';
		$parent_key = $is_block ? 'project_id' : $this->pkey;
		foreach($arrs as $val){
			$list_items = isset($val[$sub_key]) ? $val[$sub_key] : array();
			if(empty($list_items)){
				$parent_id = isset($val[$parent_key]) ? (int) $val[$parent_key] : 0;
				$list_items = $this->getChildBySource($property_type, $parent_id);
			}
			if(empty($list_items)){
				continue;
			}
			$html.= '<optgroup label="'.htmlspecialchars($val['title'], ENT_QUOTES | ENT_SUBSTITUTE).'">';
			foreach($list_items as $oval){
				$item_id = $oval[$this->pkey];
				$selected = ($selected_id == $item_id) ? ' selected' : '';
				$html.= sprintf('<option value="%s"%s>%s</option>', $item_id, $selected, htmlspecialchars($oval['title'], ENT_QUOTES | ENT_SUBSTITUTE));
			}
			$html.='</optgroup>';
		}
		return $html;
	}
	/* Dựng lại mảng nút cha khi nơi gọi truyền vào rỗng. Gặp thật trên production: đoạn
	   gán $arrBlock trong module không chạy nên cả cột dãy trắng trơn dù dữ liệu còn đủ.
	   Chỉ dựng được cho _RANGE (cha là phân khu, suy ra từ dự án đang xem trên URL);
	   _BLOCK có cha là dự án nên trả rỗng, giữ nguyên hành vi cũ. */
	function getSourceFallback($property_type){
		static $cached = array();
		if(isset($cached[$property_type])){
			return $cached[$property_type];
		}
		$cached[$property_type] = array();
		if($property_type != '_RANGE'){
			return $cached[$property_type];
		}
		$field = "{$this->pkey},title";
		$project_id = class_exists('Input') ? (int) Input::get('project_id', 0) : 0;
		if($project_id > 0){
			// Không lọc is_trash để khớp đúng danh sách mà module vẫn dựng.
			$cached[$property_type] = $this->getAll("`property_type`='_BLOCK' AND `for_id`='{$project_id}' ORDER BY `order_no` ASC", $field);
		}
		return $cached[$property_type];
	}
	function getChildBySource($property_type, $for_id){
		static $cached = array();
		if($for_id <= 0){
			return array();
		}
		$key = $property_type.'_'.$for_id;
		if(!isset($cached[$key])){
			$field = "{$this->pkey},title";
			$cached[$key] = $this->getAll("`property_type`='{$property_type}' AND `for_id`='{$for_id}' ORDER BY `order_no` ASC", $field);
		}
		return $cached[$key];
	}
	function getSelectByPropertyOrigin($type, $for_id=0, $selected = 0, $title="") {
		global $core, $clsISO;
		if(!empty($title)){
			$html = '<option value="0">'.$title.'</option>';
		} else {
			$html = '<option value="0">'.$core->get_Lang('select').'</option>';
		}
		$field = "{$this->pkey},title";
		$tmp = $this->getAll("property_type='{$type}' and for_id='{$for_id}' order by order_no ASC", $field);
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
		$tmp = $this->getAll("property_type='{$type}' and for_id='{$for_id}' order by order_no ASC", $field);
		if (!empty($tmp)) {
			foreach ($tmp as $item) {
				$html .= '<option value="'. $item[$this->pkey]. '"' .($selected==$item[$this->pkey]?' selected':'').'>'.$item['title'].'</option>';
			}
			unset($tmp);
		}
		return $html;
	}
    function getSelectSingleProperty($type, $parent_id = 0, $selected = 0, $title="") {
        global $core, $clsISO;
        #
		$field = "{$this->pkey},title";
        $tmp = $this->getAll("is_trash=0 and property_type='{$type}' and parent_id='{$parent_id}' order by order_no ASC", $field);
		if(!empty($title)){
			$html = '<option value="0">'.$title.'</option>';
		} else {
			$html = '<option value="0">Lựa chọn</option>';
		}
        if (!empty($tmp)) {
            foreach ($tmp as $val) {
                $html.='<option value="'.$val[$this->pkey].'"'.($selected==$val[$this->pkey]?' selected':'').'>'.$val['title'].'</option>';
            }
        }
        return $html;
    }
    function makeOption($parent_id = 0, $property_type = '', $level = 0, &$arrHtml) {
        global $core, $dbconn, $clsISO;
		$field = "{$this->pkey},`property_code`,`title`";
		$cond = "`is_trash`=0 AND `parent_id`='{$parent_id}' AND `property_type`='{$property_type}'";
        $arrListCat = $this->getAll("{$cond} order by `order_no` ASC", $field);
        if (!empty($arrListCat)) {
            foreach ($arrListCat as $k => $v) {
				$value = $v[$this->pkey];
				$option = @str_repeat("|__ ", $level). sprintf('%s', $v['title']);
                $arrHtml[$value] = $option;
                $this->makeOption($v[$this->pkey], $property_type, $level + 1, $arrHtml);
            }
			unset($arrListCat);
        }
    }
	function makeList($parent_id="0", $property_type="", &$arrReturns= array()){
		global $dbconn, $core, $clsISO;
		if( !$arrReturns) $arrReturns = array();
		$tmp = $this->getAll("`is_trash`=0 AND `property_type`='{$property_type}' 
			AND `parent_id`='{$parent_id}' ORDER BY `order_no` ASC", "{$this->pkey},`title`,`more_information`,`parent_id`");
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$arrReturns[$val[$this->pkey]] = $val;
				$this->makeList($val[$this->pkey], $property_type, $arrReturns);
			}
			unset($tmp);
		}
		return $arrReturns;
	}
    function getmakeOption($parent_id = 0, $property_type = '', $selectedid = "", $level = 0, &$arrHtml) {
        global $core, $dbconn, $clsISO;
        $cond = "`is_trash`=0 and `parent_id`='{$parent_id}'";
		$cond.= " and `property_type`='{$property_type}'";
		$field = "{$this->pkey},property_code,title";
        $arrListCat = $this->getAll($cond. " order by `order_no` ASC", $field);
        if (!empty($arrListCat)) {
            foreach ($arrListCat as $k => $v) {
				$value = $v[$this->pkey];
                $selected = ($v[$this->pkey] == $selectedid) ? "selected" : "";
                $option = $v['title'];
                $arrHtml[$value] = $option;
                $this->getmakeOption($v[$this->pkey], $property_type, $selectedid, $level + 1, $arrHtml);
            }
            return "";
        } else {
            return "";
        }
    }
    function getListOption($property_type="", $propperty_id = '',$parent_id=0) {
        global $core, $dbconn, $clsISO;
		$html = ""; $arrOptions = array();
        $this->makeOption($parent_id, $property_type, 0, $arrOptions);
		if(!empty($arrOptions)){
			foreach($arrOptions as $k => $v) {
				$selected = ($k == $propperty_id) ? ' selected="selected"' : '';
				$html .= '<option value="'.$k.'"'.$selected.'>'.$v.'</option>';
			}
			unset($arrOptions);
		}
        return $html;
    }
	function getItems($property_type, $property_id=0, $field="*"){
		$cond = "`property_type`='{$property_type}'";
		if(intval($property_id) > 0) 
			$cond .= " and `parent_id`='{$property_id}'";
		return $this->getAll("{$cond} order by `order_no` ASC", $field);
	}
	function getOItems($property_type, $for_id = 0, $field="",$is_active=1){
		global $clsISO;
		if(!$is_active){
			$cond = "`property_type`='{$property_type}'";
		}else{
			$cond = "`is_trash`='0' and `property_type`='{$property_type}'";
		}
		
		if(intval($for_id) > 0) $cond .= " and `for_id`='{$for_id}'";
		$field= "{$this->pkey},title,slug,for_id,more_information,image,property_code,parent_id,order_no,is_trash,bgcolor,textcolor".(!empty($field)?",{$field}" : "");
		return $this->getAll("{$cond} order by `order_no` ASC", $field);
	}
	function getCacheItems($property_type,$is_active=1){
		$clsCache = new Cache();
		if($clsCache->has($property_type) && 1==2){
			return $clsCache->get($property_type);
		} else {
			return $this->getOItems($property_type,0,"",$is_active);
		}
	}
	function getStatus($property_id, $oDataTable=array()){
		global $core;
		if(!isset($oDataTable['is_trash'])){
			$oDataTable = $this->getOne($property_id, "is_trash");
		}
		if($oDataTable['is_trash']==0)
			return '<span class="label label-success">'.$core->get_Lang('InActive').'</span>';
		return '<span class="label label-danger">'.$core->get_Lang('Active').'</span>';
	}
	 function getChilds($property_id, &$arrReturns = array()){
		$tmp = $this->getAll("`parent_id`='{$property_id}'", $this->pkey);
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$arrReturns[] = $val[$this->pkey];
				$this->getChilds($val[$this->pkey], $arrReturns);
			}
		}
		return "";
	} 
	function checkIsParent($property_id,$parent_id_check){
        $one = $this->getOne($property_id, "parent_id");
        $parent_id = $one['parent_id'];
        if($parent_id==$parent_id_check){
            return 1;
        }
        if($parent_id==0){return 0;}
        return $this->checkIsParent($parent_id,$parent_id_check);
    }
    function getListParent($property_id,$property_type = '_DEPARTMENT'){
        $listChild = array($property_id);
        $allChild = $this->getAll("property_type='{$property_type}'");
        if(!empty($allChild)){
			foreach($allChild as $item){
                if($this->checkIsParent($property_id, $item[$this->pkey])){
                    $listChild[] = $item[$this->pkey];
                }
            }
        }
        #
		$str = "";
		if(!empty($listChild)){
			$str = "|" . implode("|",$listChild) . "|";
        }
        return $str;
    }
	function getArraySearchByKey($property_type,$id=0,$parent_id="",$is_active=1){
		global $core, $dbconn, $clsISO;
		$data = array();
		$tmp = $this->getCacheItems($property_type,$is_active);
		if(!empty($tmp)){
			foreach($tmp as $key => $val) {
				$property_id = $val[$this->pkey];
				$more_information = $val['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				if(($parent_id === "" || $parent_id == $val['parent_id']) && (($is_active == 1 && $val['is_trash'] == 0) || empty($is_active) )) {
					$data[$property_id] = array(
						$this->pkey		=>	$property_id,
						"property_code"	=>	$val['property_code'],
						"title"			=>	$val['title'],
						"slug"			=>	$val['slug'],
						"title_vn"		=>	$val['title_vn'],
						"slug_vn"		=>	$val['slug_vn'],
						"image"			=>	$val['image'],
						"bgcolor"		=>	$val['bgcolor'],
						"textcolor"		=>	$val['textcolor'],
						"project_id"	=>	$val['for_id'],
						"for_id"		=>	$val['for_id'],
						"parent_id"		=>	$val['parent_id'],
						"order_no"		=>	$val['order_no'],
						"is_trash"		=>	$val['is_trash'],
						"more_information"	=>	$more_information,
						"ward" => isset($more_information['ward']) ? $more_information['ward'] : "",
						"street" => isset($more_information['street']) ? $more_information['street'] : "",
						"floor_hierarchy" => isset($more_information['floor_hierarchy']) ? $more_information['floor_hierarchy'] : "",
					);
					if($property_type == "_BLOCK") {
						$data[$property_id]["is_project"] = !empty($more_information["is_project"]) ? $more_information["is_project"] : 0;
					}
				}				
			}		
		}
			
		if(!empty($id)) {
			return $data[$id];
		}
		return $data;
	}
	function getArraySearchByKeyCode($property_type,$id=0,$parent_id="",$is_active=1){
		global $core, $dbconn, $clsISO;
		$data = array();
		$tmp = $this->getArraySearchByKey($property_type,$id,$parent_id,$is_active);
		if(!empty($tmp)) {
			foreach($tmp as $key => $val) {
				$data[$val["property_code"]] = $val;
			}
		}
		return $data;
	}
	function getNumberFloor($str) {
		preg_match('/(\d+[A-Za-z]*\s*(?:-\s*\d+[A-Za-z]*)?)/', $str, $match);
		if (!$match) {
			return [];
		}
		$tmp = trim($match[1]);
		if (strpos($tmp, "-") === false) {
			return [$tmp];
		}
		list($start, $end) = array_map('trim', explode("-", $tmp));
		preg_match('/(\d+)([A-Za-z]*)/', $start, $m1);
		preg_match('/(\d+)([A-Za-z]*)/', $end, $m2);
		$startNum = intval($m1[1]);
		$startSuffix = $m1[2] ?? "";
		$endNum = intval($m2[1]);
		$endSuffix = $m2[2] ?? "";
		$arr_number = [];
		for ($i = $startNum; $i <= $endNum; $i++) {
			if ($i == $startNum && $startSuffix !== "") {
				$arr_number[] = $i . $startSuffix;
			} else if ($i == $endNum && $endSuffix !== "") {
				$arr_number[] = $i . $endSuffix;
			} else {
				$arr_number[] = $i;
			}
		}
		return $arr_number;
	}
	function getLayout($building_id,$floor,$code="",$type="floor",$oneBuilding = null) {
		global $clsISO,$profile_id;
		$clsProject = new Project();
		if(!empty($oneBuilding['more_information'])) {
			$oneBuilding = $this->getOne($building_id);
		}		
		if(!empty($oneBuilding)) {
			$more_information = $clsISO->to_array_json($oneBuilding['more_information']);
			if($type == "floor") {
				$layout_ms = !empty($more_information['layout_ms']) ? $more_information['layout_ms'] : array();
				foreach ($layout_ms as $key => $val) {
					$arr_floor = $this->getNumberFloor($val['title']);
					if($clsISO->checkItemInArray($floor,$arr_floor)) {
						return $val['image'];
						break;
					}
				}	
				if(!empty($more_information["layout_ns"])) {
					return $more_information["layout_ns"];
				}
			}else if($type == "code") {
				$floor_specical = !empty($more_information['floor_specical']) ? $more_information['floor_specical'] : array();
				$arr_cols = array();
				if(!empty($floor_specical)) {
					$key=array_search($floor,$floor_specical);
					$template_specical = !empty($more_information['template_specical'][$key]) ? $more_information['template_specical'][$key] : array();
					if(!empty($template_specical)) {
						foreach($template_specical as $key => $val){
							$arr_cols[$val['code']] = $val;
						}
					}
				}
				return $clsProject->getFieldInCol($code,$arr_cols,'layout');
			}
			
		}
		return "";
	}
	function getMenuTotal($menu_id) {
		//11130-bang hang,8762-khach hang,11129-du an,8763-ban tin
		global $clsISO,$profile_id,$dbconn,$oneProfile;
		$clsStock = new Stock();
		$clsNotify = new Notify();
		$clsCustomer = new Customer();
		$clsProject = new Project();
		$clsNews = new News();
		$total = 0;
		return 0;
		switch($menu_id) {
			case 11130:
				$field = "{$clsProject->pkey},`code`,`title`,`link`,`is_menu`,`image`,`more_information`,`utilities`,`list_block_type`";
				$list_projects = $clsProject->getAll("`is_menu`='1' AND JSON_CONTAINS(JSON_EXTRACT(`more_information`, '$.site_manager_ids'),'\"".DOMAIN_ID."\"')  order by `reg_date` ASC", $field);
				$arr_project = [];
				foreach ($list_projects as $key => $val) {
					$arr_project[] = $val[$clsProject->pkey];
				}
				if(!empty($arr_project)) {
					$cond_stock = "`project_id` IN (".implode(',',$arr_project).") AND `status_id`>0 and `status_id`<>'"._STOCK_STATUS_SOLD_ID."' and `status_id`<>'"._STOCK_STATUS_NON_ID."'";
					if(defined('_STOCK_SEARCH_HIDE_ENABLE') && _STOCK_SEARCH_HIDE_ENABLE==0){
						$cond_stock.= " and `status_id`<>'"._STOCK_STATUS_HIDDEN_ID."'";
					}
					$total = $clsStock->countItem($cond_stock);
				}
				break;
			case 8784:
				$field = "{$clsProject->pkey},`code`,`title`,`link`,`is_menu`,`image`,`more_information`,`utilities`,`list_block_type`";
				$list_projects = $clsProject->getAll("`is_menu`='1' AND JSON_CONTAINS(JSON_EXTRACT(`more_information`, '$.site_manager_ids'),'\"".DOMAIN_ID."\"')  order by `reg_date` ASC", $field);
				$arr_project = [];
				foreach ($list_projects as $key => $val) {
					$arr_project[] = $val[$clsProject->pkey];
				}
				if(!empty($arr_project)) {
					$cond_stock = "`project_id` IN (".implode(',',$arr_project).") AND `status_id`>0 and `status_id`<>'"._STOCK_STATUS_SOLD_ID."' and `status_id`<>'"._STOCK_STATUS_NON_ID."' and `status_id`='"._STOCK_STATUS_DQ_ID."'";
					if(defined('_STOCK_SEARCH_HIDE_ENABLE') && _STOCK_SEARCH_HIDE_ENABLE==0){
						$cond_stock.= " and `status_id`<>'"._STOCK_STATUS_HIDDEN_ID."'";
					}
					$total = $clsStock->countItem($cond_stock);
				}
				break;
			case 8762:
				$cond_cus = "`is_trash`=0 AND `status_id`<>'"._CRM_STATUS_TRASH_ID."' and `customer_id` not in ( select `customer_id` from `default_archived` where `profile_id`='{$profile_id}' ) and (`admin_id`='{$profile_id}')  ";
				$total = $clsCustomer->countItem($cond_cus);
				break;
			case 11129:
				$total = $clsProject->countItem("`is_menu`='1' AND JSON_CONTAINS(JSON_EXTRACT(`more_information`, '$.site_manager_ids'),'\"".DOMAIN_ID."\"')");
				break;
			case 8763:
				$query_total = $dbconn->getAll("SELECT COUNT(*) AS total FROM default_notify WHERE `list_user_slash` LIKE '%|{$profile_id}|%' AND `list_user_read` NOT LIKE '%|{$profile_id}|%' AND DATE_SUB(FROM_UNIXTIME(`send_date`),INTERVAL 12 HOUR)<='".date('Y-m-d H:i:s')."' and `tbl`='News'  ");
				$total = !empty($query_total[0]["total"]) ? $query_total[0]["total"] : 0;
				break;
			case 8795:
				$clsGroupProfile = new GroupProfile();
				$clsTraining = new Training();
				$cond = " AND t.`is_trash`='0' AND t.`is_online`='1' AND (t.`is_all_staff`='1' ";
				$list_department_id = $oneProfile['list_department_id'];
				$arr_department_ids = !empty($list_department_id) ? $clsISO->getArrayByTextSlash($list_department_id) : array();
				$list_group = $clsGroupProfile->getAll("`is_trash`='0' AND `is_online`='1' AND `list_profile_id` LIKE '%|".$profile_id."|%'",$clsGroupProfile->pkey);
				if(!empty($arr_department_ids) || !empty($list_group) ) {
					$first = 0;
					$cond .= " OR  (t.`is_all_staff`='0' AND ( t.`list_profile_id` LIKE '%|{$profile_id}|%'";
					if(!empty($arr_department_ids)) {
						foreach ($arr_department_ids as $department_id) {
							$cond .= " OR t.`list_department_id` LIKE '%|{$department_id}|%'";
						}
					}
					if(!empty($list_group)) {
						foreach ($list_group as $k => $_oGroup) {
							$cond .= " OR t.`list_group_profile_id` LIKE '%|{$_oGroup[$clsGroupProfile->pkey]}|%'";	
						}	
					}
					$cond .="))";
				}
				$cond .= " )";
				$query_total = $dbconn->getAll("SELECT COUNT(DISTINCT t.training_id) AS total
				FROM default_training t
				LEFT JOIN JSON_TABLE(
						t.more_information,
						'$.history_learning.*[*]'
						COLUMNS (
							profile_id INT PATH '$.profile_id'
						)
					) AS jt
					 ON jt.profile_id = '{$profile_id}'
				WHERE jt.profile_id IS NULL".$cond);
				$total = !empty($query_total[0]["total"]) ? $query_total[0]["total"] : 0;
				break;
			case 11161:
				$clsBooking = new Booking();
				$clsBookingMeta = new BookingMeta();
				$cond = "`t1`.`is_trash`=0 AND `t1`.`booking_type`='"._BOOKING_TYPE_INTERNAL_ID."'";
				$cond.= " AND `t1`.`block_id`='11110'";
				if($clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('ADMIN_PROJECT')){
				} elseif($clsISO->checkPermissionGroup('SALE_DIRECTOR')) {	
					$department_id = $oneProfile["department_id"];
					$cond.= " AND JSON_EXTRACT(`t1`.`more_information`,\"$.department_id\")='{$department_id}'";
				} else {
					$cond.= " AND (JSON_EXTRACT(`t1`.`more_information`,\"$.staff_id\")='{$profile_id}')";
				}
				$list_bookings = $dbconn->getAll("SELECT COUNT(`t1`.`{$clsBooking->pkey}`) AS total FROM `{$clsBooking->tbl}` AS `t1` 
					LEFT JOIN `{$clsBookingMeta->tbl}` AS `t2` ON `t1`.`booking_id`=`t2`.`booking_id` 
					WHERE {$cond} AND `t2`.`type`='priority' ORDER BY `t1`.`reg_date` DESC");
				$total = !empty($list_bookings[0]["total"]) ? $list_bookings[0]["total"] : 0; 
				break;
			default:
				$total = 0;
		}
		return $total;
	}
	function getRoleUtilities($role_id) {
		global $clsISO;
		if($clsISO->checkItemInArray($role_id, array(_ROLE_BGD_MANAGER, _ROLE_GD_MANAGER, _ROLE_PGD_MANAGER))) {
			$role = "DIRECTOR";
		} elseif($clsISO->checkItemInArray($role_id,array(_ROLE_REGIONAL_DIRECTOR_ID))) {
			$role = "REGIONAL_DIRECTOR";
		} elseif($clsISO->checkItemInArray($role_id,array(_ROLE_GD_PROJECT, _ROLE_GD_SALE))) {
			$role = "SALE_DIRECTOR";
		} elseif($clsISO->checkItemInArray($role_id,array(_ROLE_STAFF_ADMIN))) {
			$role = "ADMIN_PROJECT";
		} elseif($clsISO->checkItemInArray($role_id,array(_ROLE_ACCOUNTANT))) {
			$role = "ACCOUNTANT";
		} else{
			$role = "SALE";
		}
		return $role;
	}
	function getStaffDirectorDep ($department_id) {
		global $core;
		$one_dep = $this->getArraySearchByKey("_DEPARTMENT",$department_id);
		
		$more_information = $one_dep['more_information'];
		$department_id = $core->get_field($one_dep, 'department_id', 0);
		$head_of_dep_id = $core->get_field($more_information, 'head_of_dep_id', 0);
		$is_regional = 0;
		if($one_dep["parent_id"] == _DEPARTMENT_SALE_ID){
			if((int) $core->get_field($more_information, "is_business_area", 0) == 1){
				$regional_director_id = (int) $core->get_field($more_information, "head_of_dep_id", 0);
				$is_regional = 1;
			}else{				
				$regional_director_id = 0;
			}
		} else {
			$oneParent = $this->getArraySearchByKey("_DEPARTMENT",$one_dep["parent_id"]);
			$more_information = $oneParent['more_information'];
			if((int) $core->get_field($more_information, "is_business_area", 0) == 1){
				$regional_director_id = (int) $core->get_field($more_information, "head_of_dep_id", 0);
			}
		}
		return [
			"head_of_dep_id"		=>	$head_of_dep_id,
			"regional_director_id"	=>	$regional_director_id,
			"is_regional"	=>	$is_regional,
		];
	}
	// Xác định Vùng / GĐ Vùng / GĐ Kinh doanh từ phòng ban của 1 nhân viên bằng cách leo TRỌN chuỗi cha.
	// (getStaffDirectorDep chỉ nhìn 1 cấp nên sót NV nằm ở tổ sâu hơn, và không trả về id Vùng.)
	// Ưu tiên head_of_dep_id cấu hình trên node nếu người đó còn làm việc; hết thì tra người giữ role tại node.
	function resolveStaffDepChain($department_id){
		global $core, $clsISO;
		$clsISO = is_object($clsISO) ? $clsISO : new ISO();
		$clsProfile = new Profile();
		$result = array(
			'region_id' => 0,
			'regional_director_id' => 0,
			'head_of_dep_id' => 0
		);
		$department_id = (int) $department_id;
		if($department_id <= 0){
			return $result;
		}
		$_gate = "`is_trash`=0 and `is_active`=1 and `status_id`='" . _STATUS_STAFF_ON_ID . "'";
		$node = $department_id;
		$hops = 0;
		while($node > 0 && $node != _DEPARTMENT_SALE_ID && $hops < 8){
			// getOne theo pkey, KHÔNG lọc is_trash: node xóa mềm (vd PTĐT) vẫn đang gánh nhân sự thật
			$one_dep = $this->getOne($node, "`parent_id`,`more_information`");
			if(empty($one_dep)){
				break;
			}
			$more_information = $clsISO->to_array_json($one_dep['more_information']);
			$node_head = (int) $core->get_field($more_information, 'head_of_dep_id', 0);
			// Head cấu hình trên node đã nghỉ việc thì không tin, chuyển sang tra role tại node
			if($node_head > 0 && $clsProfile->countItem("{$_gate} and `{$clsProfile->pkey}`='{$node_head}'") == 0){
				$node_head = 0;
			}
			if((int) $core->get_field($more_information, 'is_business_area', 0) == 1){
				$result['region_id'] = (int) $node;
				$result['regional_director_id'] = $node_head;
				if($result['regional_director_id'] == 0){
					$_boss = $clsProfile->getByCond("{$_gate} and `department_id`='{$node}' and `role_id`='" . _ROLE_REGIONAL_DIRECTOR_ID . "'", "{$clsProfile->pkey}");
					if(!empty($_boss)){
						$result['regional_director_id'] = (int) $_boss[$clsProfile->pkey];
					}
				}
				break; // trên Vùng chỉ còn node gốc Kinh Doanh
			}
			if($result['head_of_dep_id'] == 0){
				$result['head_of_dep_id'] = $node_head;
				if($result['head_of_dep_id'] == 0){
					$_boss = $clsProfile->getByCond("{$_gate} and `department_id`='{$node}' and `role_id`='" . _ROLE_GD_SALE . "'", "{$clsProfile->pkey}");
					if(!empty($_boss)){
						$result['head_of_dep_id'] = (int) $_boss[$clsProfile->pkey];
					}
				}
			}
			$node = (int) $one_dep['parent_id'];
			$hops++;
		}
		return $result;
	}
}
?>