<?php
class Company extends dbBasic{
	function Company(){
		global $_LANG_ID;
		$this->pkey = "company_id";
		$this->tbl = DB_PREFIX."company";
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
	function getBySlug($slug) {
        $res = $this->getAll("is_trash=0 and slug='$slug'");
        return $res[0][$this->pkey];
    }
	function getIntro($pvalTable, $_args= array()){
		if(is_array($_args) && $_args['intro'] != ''){
			return html_entity_decode($_args['intro']);
		}else{
			$one=$this->getOne($pvalTable);
			return html_entity_decode($one['intro']);
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
	function doDelete($service_id){
		// Delete
		$this->deleteOne($service_id);
		return 1;
	}
}
?>