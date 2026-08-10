<?
class Recruit extends dbBasic{
	function Recruit(){
		$this->pkey = "recruit_id";
		$this->tbl = DB_PREFIX."recruit";
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
	function getLink($pvalTable){
		global $extLang, $_LANG_ID;
		return $extLang.'/tin-tuyen-dung/'.$this->getSlug($pvalTable).'.html';
	}
	function getSalary($pvalTable){
		if(is_array($_args) && $_args['salary'] != ''){
			return $_args['salary'];
		}else{
			$one=$this->getOne($pvalTable);
			return $one['salary'];
		}
	}
	function getPositionType($pvalTable, $_args= array()){
		if(is_array($_args) && $_args['position_type'] != ''){
			return $_args['position_type'];
		}else{
			$one=$this->getOne($pvalTable);
			return $one['position_type'];
		}
	}
	function getEmailContact($pvalTable, $_args= array()){
		if(is_array($_args) && $_args['email_contact'] != ''){
			return $_args['email_contact'];
		}else{
			$one=$this->getOne($pvalTable);
			return $one['email_contact'];
		}
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
	function getIntro($pvalTable){
		$one = $this->getOne($pvalTable);
		return html_entity_decode($one['intro']);
	}
	function getContent($pvalTable){
		$one = $this->getOne($pvalTable);
		return html_entity_decode($one['content']);
	}
	function getStripIntro($pvalTable){
		$one = $this->getOne($pvalTable);
		if(!empty($one['intro']))
			return strip_tags(html_entity_decode($one['intro']));
		return strip_tags(html_entity_decode($one['content']));
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
	function doDelete($pvalTable){
		// Delete
		$this->deleteOne($pvalTable);
		return 1;
	}
}
?>