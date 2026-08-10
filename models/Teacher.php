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
class Teacher extends dbBasic{
	function Teacher(){
		global $_LANG_ID;
		$this->pkey = "teacher_id";
		$this->tbl = DB_PREFIX."teacher";
	}
	function getTitle($pvalTable, $_args= array()){
		if(is_array($_args) && $_args['title'] != ''){
			return $_args['title'];
		}else{
			$one=$this->getOne($pvalTable);
			return $one['title'];
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
	function getFullName($pvalTable, $_args= array()){
		if(is_array($_args) && $_args['full_name'] != ''){
			return $_args['full_name'];
		}else{
			$one=$this->getOne($pvalTable);
			return $one['full_name'];
		}
	}
	function getPosition($pvalTable, $_args= array()){
		$clsCategory = new Category();
		#
		if(is_array($_args) && $_args['cat_id'] != ''){
			return $clsCategory->getTitle($_args['cat_id']);
		}else{
			$one=$this->getOne($pvalTable);
			return $clsCategory->getTitle($one['cat_id']);
		}
	}
	function getRegDate($pvalTable) {
		$one=$this->getOne($pvalTable);
		return date('m/d/Y',$one['reg_date']);
	}
	function getImage($pvalTable, $w, $h){
		global $clsISO;
		$oneTable = $this->getOne($pvalTable, "image");
		if($oneTable['image']!=''){
			$image = $oneTable['image'];
			return '/files/thumb/'.$w.'/'.$h.'/'.$clsISO->parseImageURL($image);
		}
		return URL_IMAGES.'/noimage.png';
	}
	function getImageUrl($pvalTable, $_args= array()){
		#
		if(is_array($_args) && $_args['image'] != ''){
			return $clsTeacherCategory->getTitle($_args['image']);
		}else{
			$one = $this->getOne($pvalTable,"image");
			return $one['image'];
		}
	}
	function getContent($pvalTable, $_args= array()){
		if(is_array($_args) && $_args['content'] != ''){
			return html_entity_decode($_args['content']);
		}else{
			$one=$this->getOne($pvalTable);
			return html_entity_decode($one['content']);
		}
	}
	function doDelete($teacher_id){
		// Delete
		$this->deleteOne($teacher_id);
		return 1;
	}
}
class TeacherMethod extends dbBasic{
	function TeacherMethod(){
		$this->pkey = "teacher_method_id";
		$this->tbl = DB_PREFIX."teacher_method";
	}
	function getSlash($level){
		return str_repeat("------", $level+1);
	}
	function getTitle($pvalTable, $_args= array()){
		if(is_array($_args) && @$_args['title'] != ''){
			return $_args['title'];
		}else{
			$one=$this->getOne($pvalTable);
			return $one['title'];
		}
	}
	function getSlug($pvalTable, $_args= array()){
		if(is_array($_args) && @$_args['slug'] != ''){
			return $_args['slug'];
		}else{
			$one=$this->getOne($pvalTable);
			return $one['slug'];
		}
	}
	function getBySlug($slug) {
        $res = $this->getAll("is_trash=0 and slug='$slug'");
        return $res[0][$this->pkey];
    }
	function getLink($pvalTable){
		global $extLang, $_LANG_ID;
		return $extLang.'/phuong-phap-giang-day/'.$this->getSlug($pvalTable).'.html';
	}
	function getImage($pvalTable, $w, $h){
		global $clsISO;
		$oneTable = $this->getOne($pvalTable, "image");
		if($oneTable['image']!=''){
			$image = $oneTable['image'];
			return '/files/thumb/'.$w.'/'.$h.'/'.$clsISO->parseImageURL($image);
		}
		return URL_IMAGES.'/noimage.png';
	}
	function getImageUrl($pvalTable){
		$one = $this->getOne($pvalTable);
		return $one['image'];
	}
	function getIntro($pvalTable, $_args= array()){
		if(is_array($_args) && @$_args['intro'] != ''){
			return html_entity_decode($_args['intro']);
		}else{
			$one=$this->getOne($pvalTable);
			return html_entity_decode($one['intro']);
		}
	}
	function getContent($pvalTable, $_args= array()){
		if(is_array($_args) && @$_args['content'] != ''){
			return html_entity_decode($_args['content']);
		}else{
			$one=$this->getOne($pvalTable);
			return html_entity_decode($one['content']);
		}
	}
	function getStripIntro($pvalTable){
		global $_LANG_ID;
		$one = $this->getOne($pvalTable);
		if(!empty($one['intro']))
			return strip_tags(html_entity_decode($one['intro']));
		return strip_tags(html_entity_decode($one['content']));
	}
	function doDelete($pvalTable){
		// Delete
		$this->deleteOne($pvalTable);
		return 1;
	}
}
?>