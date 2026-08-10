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
class Brand extends dbBasic{
	public $data;
	
	function __construct(){
		$this->pkey = "brand_id";
		$this->tbl = DB_PREFIX."brand";	
	}
	function getTitle($page_id){
		global $_LANG_ID;
		if($page_id >0){
			$one=$this->getOne($page_id);
			return $one['title'];
		}
		return false;
	}
	function doDelete($pvalTable){
		$this->deleteOne($pvalTable);
		return 1;
	}
	function getOptionBrand($product){
		global $dbconn;
		$html='<option value="">-- Chọn suất sứ --</option>';
		$arrListCat = $dbconn->GetAll("SELECT * FROM ".$this->tbl." WHERE is_trash =0 AND is_online=1 order by order_no desc");
		if(count($arrListCat) >0){
			foreach($arrListCat as $key=> $val){
				if($val[$this->pkey] == $product){
					$select = 'selected';
				}else{
					$select='';
				}
				$html .='<option '.$select.' value="'.$val[$this->pkey].'"> '.$val['title'].' </option>';	
			}
		}
		return $html;
	}
	function getAllBrandHome(){
		return $res = $this->getAll("is_trash=0 AND is_online = 1 order by order_no DESC");
	}
	function updateLink($page_id){
		global $core, $extLang,$_LANG_ID;
		if(!$this->getOneField('is_plink',$page_id)){
			$title = $this->getOneField('title',$page_id);
			if($_LANG_ID == 'vn'){
				$link = '/thong-tin/'.$core->replaceSpace($title).'.html';
			}else{
			$link = '/en/info/'.$core->replaceSpace($title).'.html';
			}
		} else {
			if($_LANG_ID == 'vn'){
				$link = $extLang.'/thong-tin/gioi-thieu.html';
			}else{
			$link = $extLang.'/en/info/about-us.html';
			}
		}
		$this->updateOne($page_id,"link='".addslashes($link)."'");
	}
	function getImageUrl($page_id){
		$oneTable = $this->getOne($page_id, "image");
		return $oneTable['image'];
	}
	function getLink($page_id){
		$one=$this->getOne($page_id);
		return $one['link'];
	}
	function getUrl($page_id){
		$one=$this->getOne($page_id);
		return $one['url'];
	}
	function getStripIntro($page_id){
		global $_LANG_ID;
		$one=$this->getOne($page_id);
		return strip_tags(html_entity_decode($one['intro']));
	}
	function getIntro($page_id){
		global $_LANG_ID;
		$one=$this->getOne($page_id);
		return html_entity_decode($one['intro']);
	}
	function checkContain($haystack,$needle){
		if($needle==''){ return 0;}
		if(strpos($haystack,$needle)===FALSE){
			return 0;
		}else{
			return 1;
		}
	}
}
?>