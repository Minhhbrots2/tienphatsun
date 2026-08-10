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
class Promotion extends dbBasic{
	function __construct(){
		$this->pkey = "promotion_id";
		$this->tbl = DB_PREFIX."promotion";
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
	function getByPermalink($permalink){
		$all=$this->getAll("is_trash=0 and (permalink_en='$permalink' or permalink_vn='$permalink') order by ".$this->pkey." limit 0,1");
		return $all[0][$this->pkey];
	}
	function getLink($promotion_id, $full_link=false){
		global $extLang, $_LANG_ID;
		$prefix = 'khuyen-mai';
		if($_LANG_ID=='en'){
			$prefix = 'promotion';
		}
		if($full_link)
			return PCMS_URL.$extLang.'/'.$prefix.'/'.$this->getSlug($promotion_id).'.html';
		return $extLang.'/'.$prefix.'/'.$this->getSlug($promotion_id).'.html';
	}
	function getPermalink($promotion_id){
		global $_LANG_ID;
		$one = $this->getOne($promotion_id);
		if($one['permalink']=='')
			return $one['slug'];
		return $one['permalink'];
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
	function getRegDate($pvalTable) {
		global $clsISO;
		$one=$this->getOne($pvalTable);
		return $clsISO->formatDate($one['reg_date'],3);
	}
	function getUpdDate($pvalTable) {
		global $clsISO;
		$one=$this->getOne($pvalTable);
		return $clsISO->formatDate($one['upd_date'],3);
	}
	function getTermCondition($promotion_id){
		global $_LANG_ID;
		$one = $this->getOne($promotion_id);
		return html_entity_decode($one['term']);
	}
	function getPromotionDate($promotion_id) {
		$clsISO = new ISO();
		$one = $this->getOne($promotion_id);
		$html='';
		if(!empty($one['date_begin']) && !empty($one['date_end'])) {
			$html.='Stay Between '.$clsISO->converTextToText($one['date_begin']).' and '.$clsISO->converTextToText($one['date_end']).'';
		}
		elseif(!empty($one['date_begin']) && $one['date_end'] == '0') {
			$html.='Stay Starting '.$clsISO->converTextToText($one['date_begin']).'';
		}
		return $html;
	}
	function checkShow($promotion_id){
		$now = time();
		$one = $this->getOne($promotion_id, "start_date,due_date");
		if($one['start_date'] <= $now and $one['due_date'] >= $now)
			return 1;
		if($one['start_date'] >= $now)
			return 2;
		return 0;
	}
	function getStartDate($promotion_id, $isFullDate = false) {
		$clsISO = new ISO();
		$one = $this->getOne($promotion_id, "start_date");
		if(!empty($one['start_date'])){
			if($isFullDate)
				return date('d/m/Y h:i A', $one['start_date']);
			return date('d/m/Y', $one['start_date']);
		}
	}
	function getEndDate($promotion_id, $isFullDate = false) {
		$clsISO = new ISO();
		$one = $this->getOne($promotion_id, "due_date");
		if(!empty($one['due_date'])){
			if($isFullDate)
				return date('d/m/Y h:i A', $one['due_date']);
			return date('d/m/Y', $one['due_date']);
		}
	}
	function checkHide($promotion_id) {
		$one = $this->getOne($promotion_id);
		$start_date = $one['start_date'];
		$due_date = $one['due_date'];
		if($date_end < strtotime(date('m/d/Y',time()).' 23:59'))
			return 1;
		return 0;
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