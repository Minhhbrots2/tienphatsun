<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
class ProductCat extends dbBasic {
    function __construct() {
        $this->pkey = "product_cat_id";
        $this->tbl = DB_PREFIX . "product_cat";
    }
    function getMaxId() {
        $res = $this->getAll("1=1 order by product_cat_id desc");
        return intval($res[0]['product_cat_id']) + 1;
    }
    function getMaxOrder() {
        $res = $this->getAll("1=1 order by order_no desc");
        return intval($res[0]['order_no']) + 1;
    }
    function getOneValue($pvalTable, $key = '') {
        $one = $this->getOne($pvalTable);
        if (isset($one[$key]) && !empty($one[$key])) {
            return $one[$key];
        }
        return false;
    }
    function getTitle($pvalTable) {
        $one = $this->getOne($pvalTable);
        return $one['title'];
    }
    function getSlug($pvalTable) {
        $one = $this->getOne($pvalTable);
        return $one['slug'];
    }
    function getBySlug($slug) {
        $res = $this->getAll("is_trash=0 and slug='$slug'");
        return $res[0][$this->pkey];
    }
    function checkSelected($data, $val, $sect = '') {
        if (is_array($data) && !empty($data)) {
            foreach ($data as $item) {
                if ($item == $val) {
                    return $sect;
                }
            }
        } else {
            if ($data == $val) {
                return $sect;
            }
        }
        return false;
    }
    function getIntro($pvalTable) {
        $one = $this->getOne($pvalTable);
        return html_entity_decode($one['intro']);
    }
    function getImage($pvalTable, $w, $h, $ValImag = 'image') {
        global $clsISO;
        #
        $oneTable = $this->getOne($pvalTable, $ValImag);
        if ($oneTable[$ValImag] != '') {
            $image = $oneTable[$ValImag];
            return '/files/thumb/' . $w . '/' . $h . '/' . $image;
        }
        return false;
    }
    function getImageIcon($pvalTable, $w, $h) {
        global $clsISO;
        #
        $oneTable = $this->getOne($pvalTable, "image_icon");
        if ($oneTable['image_icon'] != '') {
            $image = $oneTable['image_icon'];
            return '/files/thumb/' . $w . '/' . $h . '/' . $image;
        }
        return URL_IMAGES . '/noimage.png';
    }
    function getImageIcon2($pvalTable, $w, $h) {
        global $clsISO;
        #
        $oneTable = $this->getOne($pvalTable, "image_icon2");
        if ($oneTable['image_icon2'] != '') {
            $image = $oneTable['image_icon2'];
            return '/files/thumb/' . $w . '/' . $h . '/' . $image;
        }
        return URL_IMAGES . '/noimage.png';
    }
    function makeOptionParent($selected = "", $level = 1, $arrHtml = array()) {
        global $dbconn, $_LANG_ID, $core;
        $arrListCat = $dbconn->GetAll("SELECT * FROM " . $this->tbl . " WHERE parent_id=0 and is_trash=0 order by order_no desc");
        if (is_array($arrListCat)) {
            foreach ($arrListCat as $k => $v) {
                $value = $v["product_cat_id"];
                $option = str_repeat("|----", $level) . $this->getTitle($v[$this->pkey]);
                $arrHtml[$value] = $option;
            }
            $html = '<option value="">-- ' . $core->get_Lang('Select') . ' --</option>';
            foreach ($arrHtml as $k => $v) {
                $html .= '<option value="' . $k . '" ' . ($k == $selected ? 'selected="selected"' : '') . '>' . $v . '</option>';
            }
            return $html;
        } else {
            return "";
        }
    }
	function makeOption($catid=0, $selectedid="", $level=0, &$arrHtml){
//		print_r($catid);die();
		global $dbconn;
			$arrListCat = $dbconn->GetAll("SELECT * FROM ".$this->tbl." WHERE is_trash=0 and is_online=1 and parent_id='$catid' order by order_no DESC");
		if (is_array($arrListCat)){
			foreach ($arrListCat as $k => $v){
				$selected = ($v["product_cat_id"]==$selectedid)? "selected" : "";
				$value = $v["product_cat_id"];
				$option = str_repeat("|----", $level).$v["title"];
				$arrHtml[$value] = $option;
//				print_r($value);die();
				$this->makeOption($v["product_cat_id"], $selectedid, $level+1, $arrHtml, $doc_id);
			}
			return "";
		}else{
			return "";
		}
	}
    function makeList($catid = 0, $level = 0, $arrHtml) {
        global $dbconn;
        #
        $arrListCat = $dbconn->GetAll("SELECT * FROM " . $this->tbl . " WHERE parent_id='$catid' order by order_no desc");
        if (is_array($arrListCat)) {
            foreach ($arrListCat as $k => $v) {
                $value = $v[$this->pkey];
                $v['level'] = str_repeat("----", $level);
                $arrHtml[$value] = $v;
                $this->makeList($v[$this->pkey], $level + 1, $arrHtml);
            }
            return "";
        } else {
            return "";
        }
    }
    function getALLByParentID($parent = 0) {
        global $dbconn;
        return $arrListCat = $dbconn->GetAll("SELECT * FROM " . $this->tbl . " WHERE parent_id = " . $parent . " order by order_no desc");
    }
    public $data;
    function getALLParent($parent = 0, $lavel = '|--', $zezo = 1) {
        global $dbconn;
        if ($zezo == 1) {
            return $arrListCat = $dbconn->GetAll("SELECT * FROM " . $this->tbl . " WHERE parent_id = " . $parent . " order by order_no desc");
        } else {
            $arrListCat = $dbconn->GetAll("SELECT * FROM " . $this->tbl . " WHERE parent_id = " . $parent . " order by order_no desc");
        }
        if (count($arrListCat) > 0) {
            foreach ($arrListCat as $key => $val) {
                $this->data[] = array(
                    'product_cat_id' => $val['product_cat_id'],
                    'parent_id' => $val['parent_id'],
                    'title' => $lavel . " " . $val['title'],
                    'slug' => $val['slug'],
                    'intro' => $val['intro'],
                    'image' => $val['image'],
                    'order_no' => $val['order_no'],
                    'user_id' => $val['user_id'],
                    'user_id_update' => $val['user_id_update'],
                    'reg_date' => $val['reg_date'],
                    'upd_date' => $val['upd_date'],
                    'is_trash' => $val['is_trash'],
                    'getNAV' => $val['getNAV'],
                    'image_icon' => $val['image_icon'],
                    'image_icon2' => $val['image_icon2'],
                    'is_online' => $val['is_online']);
                $this->getALLParent($val[$this->pkey], '|--');
            }
        } else {
            $val = $this->getOne($parent);
            if (!empty($val)) {
                $this->data[] = array(
                    'product_cat_id' => $val['product_cat_id'],
                    'parent_id' => $val['parent_id'],
                    'title' => $val['title'],
                    'slug' => $val['slug'],
                    'intro' => $val['intro'],
                    'image' => $val['image'],
                    'order_no' => $val['order_no'],
                    'user_id' => $val['user_id'],
                    'user_id_update' => $val['user_id_update'],
                    'reg_date' => $val['reg_date'],
                    'upd_date' => $val['upd_date'],
                    'is_trash' => $val['is_trash'],
                    'getNAV' => $val['getNAV'],
                    'image_icon' => $val['image_icon'],
                    'image_icon2' => $val['image_icon2'],
                    'is_online' => $val['is_online']);
            }
        }
        return $this->data;
    }
    public $InData;
    function getALLParentID($datta = array()) {
        global $dbconn;
        if (!empty($datta)) {
            if (is_array($datta)) {
                foreach ($datta as $valItem) {
                    $arrListCat = $dbconn->GetAll("SELECT * FROM " . $this->tbl . " WHERE parent_id = " . $valItem . " order by order_no desc");
                    if (!empty($arrListCat)) {
                        foreach ($arrListCat as $key => $val) {
                            $this->InData[] = $val['product_cat_id'];
                            $this->getALLParent($val[$this->pkey]);
                        }
                    } else {
                        $this->InData[] = $valItem;
                    }
                }
            } else {
            $arrListCat = $dbconn->GetAll("SELECT * FROM " . $this->tbl . " WHERE parent_id = " . $datta . " order by order_no desc");
                if (!empty($arrListCat)) {
                    foreach ($arrListCat as $key => $val) {
                        $this->InData[] = $val['product_cat_id'];
                        $this->getALLParent($val[$this->pkey]);
                    }
                } else {
                    $this->InData[] =$datta ;
                }
            }
        }
        if (count($this->InData) > 0) {
            return implode(" , ", array_unique($this->InData));
        }
    }
    function makeSelectboxOption($catid = 0, $selected = '') {
        return $this->getListOption($catid, $selected);
    }
	function getListOption($cat_id){
//		print_r($doc_id);die();
		$arrOptionsCategory = array();;
		$this->makeOption(0, "", 0, $arrOptionsCategory);
		$html = '<option value="0" >|---- Danh mục </option>';
		foreach ($arrOptionsCategory as $k => $v){
//			print_r($arrOptionsCategory);die();
			$selected = ($k==$cat_id)?'selected="selected"':''; 
			$oneItem = $this->getOne($k);
			$html .= '<option value="'.$k.'" '.$selected.'>|----|----'.$v.'</option>';
		}
		return $html;
	}
    function getListParent($cat_id) {
        $listChild = array();
        $allChild = $this->getAll();
        if ($allChild[0][$this->pkey] != '') {
            for ($i = 0; $i < count($allChild); $i++) {
                if ($this->checkIsParent($cat_id, $allChild[$i]['product_cat_id'])) {
                    $listChild[] = $allChild[$i]['product_cat_id'];
                }
            }
        }
        #
        $cond = "|";
        if (is_array($listChild) && count($listChild) > 0) {
            for ($i = 0; $i < count($listChild); $i++) {
                $cond .= $listChild[$i] . "|";
            }
        }
        $cond .= $cat_id . "|";
        return $cond;
    }
    function checkMove($pval, $group, $type) {
        $one = $this->getOne($pval);
        $parent_id = $one['parent_id'];
        if ($type == '_UP') {
            if (!$this->checkIsFirst($pval, $parent_id, $group)) {
                return 1;
            } else {
                return 0;
            }
        } else {
            if (!$this->checkIsLast($pval, $parent_id, $group)) {
                return 1;
            } else {
                return 0;
            }
        }
    }
    function checkIsFirst($pval, $parent_id, $group) {
        $allItem = $this->getAll("is_trash=0 and _type='$group' and parent_id='$parent_id' order by order_no ASC");
        if (is_array($allItem) && count($allItem) > 0) {
            if ($allItem[0][$this->pkey] == $pval) {
                return 1;
            } else {
                return 0;
            }
        }
    }
    function checkIsLast($pval, $parent_id, $group) {
        $allItem = $this->getAll("is_trash=0 and _type='$group' and parent_id='$parent_id' order by order_no ASC");
        if (is_array($allItem) && count($allItem) > 0) {
            if ($allItem[count($allItem) - 1][$this->pkey] == $pval) {
                return 1;
            } else {
                return 0;
            }
        }
    }
    function checkIsParent($cat_id, $parent_id_check) {
        $one = $this->getOne($cat_id);
        $parent_id = $one['parent_id'];
        if ($parent_id == $parent_id_check) {
            return 1;
        }
        if ($parent_id == 0) {
            return 0;
        }
        return $this->checkIsParent($parent_id, $parent_id_check);
    }
function countParent($cat_id){
	return	$count = $this->countItem("1=1 and parent_id='$cat_id'");
	}
    function doDelete($cat_id) {
		$count = $this->countItem("1=1 and parent_id='$cat_id'");
        if ($count > 0) {
			 $lstItem = $this->getAll("1=1 and parent_id='$cat_id'"); 
            foreach ($lstItem as $item) {
                $pval = $item[$this->pkey];
                $this->deleteOne($pval);
            }
        } else {
             $this->deleteOne($cat_id);
        }
    }
    function getLink($cat_id) {
        return '/danh-muc-san-pham/' . $this->getSlug($cat_id) .'.html';
    }
    function getLinkSort($cat_id) {
        return '/danh-muc-san-pham/' . $this->getSlug($cat_id);
    }
    function getChild($product_cat_id) {
        return $this->getAll("is_trash=0 and parent_id='$product_cat_id' order by order_no ASC");
    }
    function getChildParent($product_cat_id) {
        $data =  $this->getAll("is_trash=0 and parent_id='$product_cat_id' order by order_no ASC",$this->pkey);
		$html = $product_cat_id;
		$datax = count($data);
		if($datax > 1 ){
			foreach($data as $val){
				$html .= " , ".$val[$this->pkey];
				}
			}
		return $html;
    }
    function getNAV($product_cat_id) {
        global $dbconn;
        $getNAV = $this->getOneField('getNAV', $product_cat_id);
        if ($getNAV == '' || $getNAV == '0') {
            $oneCat = $this->getOne($product_cat_id);
            #
            $i = 1;
            $j = 0;
            $res = array();
            $res[0] = $product_cat_id;
            #
            while ($i == 1) {
                $oneCurrent = $this->getOne($res[$j], 'parent_id');
                $oneParent = $oneCurrent["parent_id"];
                if ($oneParent != 0 and $i == 1) {
                    $j++;
                    $res[$j] = $oneParent;
                } else {
                    $j++;
                    $res[$j] = $oneParent;
                    $i = 0;
                }
            }
            #$this->updateOne($product_cat_id,"getNAV='".unserialize(array_reverse($res))."'");
            return array_reverse($res);
        } else {
            return unserialize($getNAV);
        }
    }
    public $dataProductCate;
    function getALLParentCheckProduct($parent = 0, $level = true) {
        global $dbconn;
        $arrListCat = $dbconn->GetAll("SELECT  " . $this->pkey . " FROM " . $this->tbl . " WHERE parent_id = " . $parent . " order by order_no desc");
        if (!empty($arrListCat)) {
            foreach ($arrListCat as $key => $val) {
                $count = $dbconn->GetAll("SELECT COUNT(product_id) FROM default_product WHERE cat_id = '" . $val[$this->pkey] . "'");
                if ($count > 0) {
                    $this->dataProductCate[] = $val;
                    $this->getALLParentCheckProduct($val[$this->pkey]);
                }
            }
        } else {
            $count = $dbconn->GetAll("SELECT COUNT(product_id) FROM default_product WHERE cat_id = '" . $parent . "'");
            if ($count > 0) {
                $this->dataProductCate[] = $parent;
            }
        }
        if (!empty($this->dataProductCate)) {
            $cate = array_unique($this->dataProductCate);
        }
        if (isset($cate) && !empty($cate)) {
            $dataCate = array();
            foreach ($cate as $value) {
                $one = $this->getOne($value);
                if (!empty($one['product_cat_id'])) {
                    if ($level == true) {
                        if ($one['parent_id'] == 0) {
                            $dataCate[] = $one['product_cat_id'];
                        }
                    } else {
                        $dataCate[] = $one['product_cat_id'];
                    }
                }
            }
            return $dataCate;
        }return false;
    }
}
?>