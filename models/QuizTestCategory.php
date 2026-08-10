<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
class QuizTestCategory extends dbBasic{
	function __construct(){
		$this->pkey = "category_id";
		$this->tbl = DB_PREFIX."quiz_test_category";
	}
	function getTitle($category_id){
		return $this->getOneField('title', $category_id);
	}
	function getSelectOptions($selected_id = 0){
		$html = '';
		$tmp = $this->getAll("`is_trash`=0 ORDER BY `order_no` ASC", "{$this->pkey},`title`");
		if(!empty($tmp)){
			foreach($tmp as $val){
				$html .= '<option'.($selected_id == $val[$this->pkey] ? ' selected' : '').' value="'.$val[$this->pkey].'">'.$val['title'].'</option>';
			}
		}
		return $html;
	}
}
