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
class Category extends dbBasic{
	function __construct(){
		$this->pkey = "cat_id";
		$this->tbl = DB_PREFIX."category";
	}
	function getListType(){
		global $core;
		$lstType = array();
		$lstType['_NEWS'] = $core->get_Lang('categorynews');
		$lstType['_FAQs'] = $core->get_Lang('CategoryFAQs');
		$lstType['_CATEGORY'] = $core->get_Lang('ProductType');
		$lstType['_PRODUCT'] = $core->get_Lang('ProductCategory');
		$lstType['_SERVICE'] = $core->get_Lang('categoryservices');
		$lstType['_PROJECT'] = $core->get_Lang('ProjectCategory');
		return $lstType;
	}
	function getNameType($type){
		$lstType = $this->getListType();
		return $lstType[$type];
	}
	function getSlash($level){
		return str_repeat("------", $level+1);
	}
	function getTitle($pvalTable, $_args= array()){
		if(is_array($_args) && $_args['title'] != ''){
			return $_args['title'];
		}else{
			$one=$this->getOne($pvalTable);
			return $one['title'];
		}
	}
	function getTitleLg($pvalTable, $_args= array()){
		if(is_array($_args) && $_args['titlelg'] != ''){
			return $_args['titlelg'];
		}else{
			$one=$this->getOne($pvalTable);
			return $one['titlelg'];
		}
	}
	function getSlug($pvalTable, $_args= array()){
		if(is_array($_args) && $_args['slug'] != ''){
			return $_args['slug'];
		}else{
			$one=$this->getOne($pvalTable);
			return $one['slug'];
		}
	}
	function getBySlug($slug) {
        $tmp = $this->getAll("is_trash=0 and slug='{$slug}' limit 0,1", $this->pkey);
        return !empty($tmp) ? $tmp[0][$this->pkey] : 0;
    }
	
	function _getByCond($criteria = array()){
		if(!empty($criteria)){
			$where = "is_trash=0 and is_online='1' and " .$this->_array_to_string($criteria, " AND ");
			$field = "{$this->pkey},title,slug";
			return $this->getAll($where, $field);
		}
		return false;
	}
	function getIntro($cat_id){
		global $_LANG_ID;
		$one = $this->getOne($cat_id, "intro");
		return html_entity_decode($one['intro']);
	}
	function getImageUrl($pvalTable){
		$one = $this->getOne($pvalTable);
		return $one['image'];
	}
	function getImage($pvalTable,$w,$h){
		global $clsISO;
		#
		$oneTable = $this->getOne($pvalTable, "image");
		if($oneTable['image']!=''){
			$image = $oneTable['image'];
			if($clsISO->checkContainer($image, DOMAIN_NAME))
				$image = str_replace(DOMAIN_NAME.'/','',$image);
			return '/files/thumb/'.$w.'/'.$h.'/'.$image;
		}
		$noimage = URL_IMAGES.'/noimage.png';
		return '/files/thumb/'.$w.'/'.$h.'/'.$clsISO->parseImageURL($noimage);
	}
	function getIcon($cat_id){ 
		global $_LANG_ID;
		$_Icon = $this->getOneField('image_Icon',$cat_id);
		return ($_Icon!='' && $_Icon != '0') ? $_Icon : '';
	}
	function getImageIcon($pvalTable,$w,$h){
		global $clsISO;
		#
		$oneTable = $this->getOne($pvalTable, "image_Icon");
		if($oneTable['image_Icon']!=''){
			$image = $oneTable['image_Icon'];
			if($clsISO->checkContainer($image, DOMAIN_NAME))
				$image = str_replace(DOMAIN_NAME.'/','',$image);
			return '/files/thumb/'.$w.'/'.$h.'/'.$image;
		}
		return URL_IMAGES.'/noimage.png';
	}
	function getNAV($cat_id){
		global $dbconn;
		$i = 1; $j = 0;
		$res = array();
		$res[0] = $cat_id;
		while ($i == 1) {
			$parent_id = $this->getOneField('parent_id', $res[$j]);
			if($parent_id != 0 and $i == 1){
				$j++;
				$res[$j] = $parent_id;
			} else{
				$j++;
				$res[$j] = $parent_id;
				$i = 0;
			}				
		}
		return array_reverse($res);
	}
	function countChild($cat_id){
		global $_LANG_ID;
		$one = $this->getAll("is_trash=0 and parent_id='$cat_id'");
		if($one[0]['cat_id']!='')
			return count($one);
		return 0;			
	}
	function getChild($cat_id){
		global $_LANG_ID;
		$field = "{$this->pkey},slug,title";
		$res = $this->getAll("is_online='1' and parent_id='{$cat_id}' order by order_no ASC", $field);
		return $res;			
	}
	function makeList($parent_id="0", $type="_NEWS", $level='----', $arrHTML= array()){
		global $dbconn, $core;
		if( !$arrHTML){ $arrHTML = array(); }
		$tmp = $dbconn->getAll("SELECT * FROM ".$this->tbl." WHERE is_trash=0 and _type='{$type}' 
		and parent_id='{$parent_id}' order by order_no ASC");
		if(!empty($tmp)){
			foreach($tmp as $v){
				$cat_id = $v[$this->pkey];
				$v['title'] = $level.'&nbsp;'.trim($this->getTitle($cat_id));
				$arrHTML[$cat_id] = $v;
				$arrHTML = $this->makeList($cat_id, $type, $level.'----', $arrHTML);
			}
			unset($tmp);
		}
		return $arrHTML;
	}
	function makeOption($parent_id=0, $type, $level='', $arrHTML=array(), $keyword=""){
		global $dbconn;
		if( !$arrHTML){ $arrHTML = array(); }
		$field = "{$this->pkey},title";
		$tmp = $dbconn->getAll("SELECT {$field} FROM ".$this->tbl." WHERE is_trash=0 and is_online='1' and _type='{$type}' 
		and parent_id='{$parent_id}' order by order_no asc");	
		if(!empty($tmp)){
			foreach($tmp as $k=>$v){
				$cat_id = $v[$this->pkey];
				$option = $level . $this->getTitle($cat_id);
				$arrHTML[$cat_id] = $option;
				$arrHTML = $this->makeOption($cat_id, $type, $level.'----', $arrHTML);
			}
		}
		return $arrHTML;
	}
	function getListOptionMultiple($type, $selected = '', $is_multiple=false) {
        global $core, $clsISO;
        #
        $all = $this->getAll("is_trash=0 and _type='$type' order by order_no desc");
		$html = '<option value="">-- ' . $core->get_Lang('select') . ' --</option>';
        if(!empty($all)) {
            foreach($all as $k=>$v){
				if(!$is_multiple){
					$selected_index = ($selected == $v[$this->pkey]) ? 'selected="selected"' : '';
					$html.='<option value="'.$v[$this->pkey].'" ' . $selected_index . '>'.$this->getTitle($v[$this->pkey]).'</option>';
				} else {
					$_array = $this->getArray($selected);
					$html .= '<option value="'.$v[$this->pkey].'" '.($clsISO->checkItemInArray($v[$this->pkey],$_array)?'selected="selected"':'').'>-- '.$this->getTitle($v[$this->pkey]).'</option>';
				}
            }
        }
        return $html;
    }
	function getListOption($parent_id=0, $type='', $selected='0', $title=""){
		global $core, $dbconn, $_LANG_ID;
		if((int) $parent_id == 0){
			$html = '<option value="0"> '.($title?$title:'Danh mục gốc').' </option>';
		}else{
			$html = '<option value="'.$parent_id.'"> '.$this->getTitle($parent_id).' </option>';	
		}
		$arrOptionsCategory = $this->makeOption($parent_id, $type, "");
		foreach ($arrOptionsCategory as $cat_id => $text){
			$html .= '<option value="'.$cat_id.'" '.($cat_id==$selected?'selected="selected"':'').'>'.$text.'</option>';
		}
		return $html;
	}
	function getListOptionNotNull($parent_id=0, $type='', $selected='0'){
		global $core, $dbconn;
		$arrOptionsCategory = array();
		$this->makeOption($parent_id, $type, "", 0, $arrOptionsCategory);
		
		$html ='<option value="00">'.$core->get_Lang('All').'</option>';
		foreach ($arrOptionsCategory as $k => $v){
			$oneItem = $this->getOne($k);
			$html .= '<option value="'.$k.'" '.($k==$selected?'selected="selected"':'').'>'.$v.'</option>';
		}
		return $html;
	}
	function makeSelectboxOption($catid=0, $type='_NEWS', $selected='', $title=""){
		return $this->getListOption($catid, $type, $selected, $title);
	}
	function makeSelectboxOptionNotNull($catid=0, $type='', $selected=''){
		$_lang = new _Lang();
		return $this->getListOptionNotNull($catid, $type, $selected);
	}
	function makeSelectboxOptionMultiple($parent_id=0, $type='_NEWS', $selected=array()){
		$html = '';
		$arrOptionsCategory = $this->makeOption($parent_id, $type, "");
		foreach ($arrOptionsCategory as $cat_id => $text){
			$html .= '<option value="'.$cat_id.'" '.(in_array($cat_id, $selected) ?'selected="selected"':'').'>'.$text.'</option>';
		}
		return $html;
	}
	function makeSelectboxSignle($parent_id, $type="", $selected='', $text=""){
		if($text==''){
			$html = '<option value="">-- Lựa chọn --</option>';
		}else{
			$html = '<option value="">-- '.$text.' --</option>';
		}
		#
		$lst = $this->getAll("is_trash=0 and _type='$type' and parent_id='$parent_id' order by order_no ASC");
		if(!empty($lst)){
			for($i=0; $i<count($lst); $i++){
				$html .= '<option value="'.$lst[$i][$this->pkey].'" '.($selected==$lst[$i][$this->pkey]?'selected="selected"':'').'>'.$this->getTitle($lst[$i][$this->pkey]).'</option>';	
			}
		}
		return $html;
	}
	function getArray($string){
		if($string=='' || $string=='|'){ return array();}
		$string = str_replace('||','|',$string);
		$string = str_replace(',','|',$string);
		$string = str_replace(':','|',$string);
		$string = str_replace(';','|',$string);
		$string = ltrim($string, '|');
		$string = rtrim($string, '|');
		return explode('|',$string);
	}
	function checkIsParent($cat_id,$parent_id_check){
        $one = $this->getOne($cat_id);
        $parent_id = $one['parent_id'];
        if($parent_id==$parent_id_check){
            return 1;
        }
        if($parent_id==0){return 0;}
        return $this->checkIsParent($parent_id,$parent_id_check);
    }
    function getListParent($cat_id){
        $listChild = array();
        $allChild = $this->getAll();
        if($allChild[0][$this->pkey]!=''){
            for($i=0;$i<count($allChild);$i++){
                if($this->checkIsParent($cat_id,$allChild[$i]['cat_id'])){
                    $listChild[] = $allChild[$i]['cat_id'];
                }
            }
        }
        #
        $cond = "|";
        if(is_array($listChild)&&count($listChild)>0){           
            for($i=0;$i<count($listChild);$i++){
                $cond .= $listChild[$i]."|";
            }   
        }
		$cond.= $cat_id."|";
        return $cond;
    }
	function getFirstItem($parent_id, $group){
		$res = $this->getAll("");
		if(!empty($res))
			return $res[0][$this->pkey];
		return 0;
	}
	function getLastItem($parent_id, $group){
		$res = $this->getAll("is_trash=0 and _type='$group' and parent_id='$parent_id' order by order_no ASC");
		if(!empty($res))
			return $res[count($res)-1][$this->pkey];
		return 0;
	}
	function checkIsFirst($pval, $parent_id, $group){
		$allItem = $this->getAll("is_trash=0 and _type='$group' and parent_id='$parent_id' order by order_no ASC");
		if(is_array($allItem) && count($allItem)>0){
			if($allItem[0][$this->pkey]==$pval){
				return 1;
			}else{
				return 0;	
			}
		}
	}
	function checkIsLast($pval, $parent_id, $group){
		$allItem = $this->getAll("is_trash=0 and _type='$group' and parent_id='$parent_id' order by order_no ASC");
		if(is_array($allItem) && count($allItem)>0){
			if($allItem[count($allItem)-1][$this->pkey]==$pval){
				return 1;
			}else{
				return 0;	
			}
		}
	}
	function checkMove($pval, $group, $type){
		$one = $this->getOne($pval);
		$parent_id = $one['parent_id'];
		if($type=='_UP'){
			if(!$this->checkIsFirst($pval, $parent_id, $group)){
				return 1;
			}else{
				return 0;
			}
		}else{
			if(!$this->checkIsLast($pval, $parent_id, $group)){
				return 1;
			}else{
				return 0;
			}
		}
	}
	function getLink($cat_id){
		global $_LANG_ID, $extLang, $clsISO;
		
		$link = '';
		$extension = ".html";
		$oneTable = $this->getOne($cat_id, "_type");
		if($oneTable['_type']=='_NEWS'){
			$extension = '.html';
			//$link = $clsISO->getLink('news', false);
		} else if($oneTable['_type']=='_PRODUCT'){
			$extension = '/';
			$link = $clsISO->getLink('product', false);
		} else if($oneTable['_type']=='_CATEGORY'){
			$extension = '.html';
			$link = $clsISO->getLink('category', false);
		} else if($oneTable['_type']=='_SERVICE'){
			$link = $clsISO->getLink('service', false);
		} else if($oneTable['_type']=='_PROJECT'){
			$extension = '/';
			$link = $clsISO->getLink('project', false);
		}
		$temp = $this->getNAV($cat_id);
		if(!empty($temp)){
			for($i=1; $i<count($temp);$i++){
				$link.= '/'.$this->getSlug($temp[$i]);
			}
			unset($temp);
		}
		return $link.$extension;
	}
	function getItems($type,$limit='') {
		$limit = !empty($limit)?'limit '.$limit:'';
		$res = $this->getAll("is_trash=0 and _type='{$type}' order by order_no asc ".$limit,$this->pkey);
		return !empty($res)?$res:'';
	}
	function doDelete($cat_id){
		$lstItem = $this->getAll("1=1 and parent_id='$cat_id'");
		if(!empty($lstItem)){
			foreach($lstItem as $item){
				$pval = $item[$this->pkey];
				$this->deleteOne($pval);
			}
			unset($lstItem);
		}
		$this->deleteOne($cat_id);
	}
	function getProductByCategory($cat_id) {
		$clsProduct = new Product();
        $tmp = $clsProduct->getAll("is_trash=0 and is_online=1 and (cat_id='$cat_id' or list_cat_id like '%|$cat_id|%') order by order_no ASC", $clsProduct->pkey);
        return $tmp;
    }
	function getCountProduct($cat_id) {
		$clsProduct = new Product();
        $tmp = $clsProduct->getAll("is_trash=0 and is_online=1 and (cat_id='$cat_id' or list_cat_id like '%|$cat_id|%') order by order_no ASC", $clsProduct->pkey);
        return count($tmp);
    }
}
?>