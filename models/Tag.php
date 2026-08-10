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
class Tag extends dbBasic{
	function __construct(){
		$this->pkey = "tag_id";
		$this->tbl = DB_PREFIX."tag";
	}
	function getTitle($tag_id){
		return $this->getOneField('title', $tag_id);
	}
	function getTags($arrs){
		$html = "";
		if(!empty($arrs)){
			$tmp = array();
			foreach($arrs as $tag_id){
				$tmp[] = $this->getTitle($tag_id);
			}
			$html = implode(',', $tmp);
		}
		return $html;
	}
	function getTagArrs($arrs, $tags_sources = array()){
		if(!empty($arrs)){
			$html = array();
			foreach($arrs as $tag_id){
				$html[] = $tags_sources[$tag_id];
			}
			return implode(',', $html);
		}
		return "";
	}
	function getTagsId($arr_tags, $tag_type="_data_central") {
		global $core, $clsISO;
		$arr_tag_id = [];
		if(!empty($arr_tags)){
			foreach($arr_tags as $tag_name) {
				$slug = $core->replaceSpace($tag_name);
				$oneTag = $this->getByCond("`is_trash`=0 AND `tag_type`='{$tag_type}' AND `slug`='{$slug}'");
				if(!empty($oneTag) && !in_array($oneTag['tag_id'], $arr_tag_id)) {
					$arr_tag_id[] = $oneTag['tag_id'];
				}else{
					$tag_id = $this->getMaxId();
					if($this->insert([
						"tag_id"	=>	$tag_id,
						"tag_type"	=>	$tag_type,
						"title"		=>	$tag_name,
						"slug"		=>	$slug,
						"user_id"	=>	0,
						"is_trash"	=>	0,
					])){
						$arr_tag_id[] = $tag_id;
					}
				}
			}
		}
		return $arr_tag_id;
	}
	function getOption($type,$arr_selected = array()) {
		global $core, $clsISO,$profile_id;
		$cond = "`tag_type`='{$type}' and (`user_id`='{$profile_id}')";
		$field = "{$this->pkey},title";
		$tmp = $this->getAll($cond, $field);
		$html = "";
		if (!empty($tmp)) {
			foreach ($tmp as $item) {
				if(!empty($item['title'])) {
					$html .= '<option value="'. $item[$this->pkey]. '"' .(in_array($item[$this->pkey],$arr_selected)?' selected':'').'>'.$item['title'].'</option>';	
				}				
			}
			unset($tmp);
		}
		return $html;
	}
}
?>