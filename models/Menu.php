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
class Menu extends dbBasic{
	function __construct(){
		$this->pkey = "menu_id";
		$this->tbl = DB_PREFIX."menu";
	}
	function getTitle($pvalTable){
		$one=$this->getOne($pvalTable);
		return $one['title'];
	}
	function getAlias($pvalTable){
		$one=$this->getOne($pvalTable);
		return $one['alias'];
	}
	function getUrl($type, $mod='menu'){
		return PCMS_URL.'/index.php?mod='.$mod.'&act=load_'.$type.'_search';
	}
	function checkSubMenu($parent_id, $alias){
		$submenu = $this->countItem("parent_id='{$parent_id}' and alias='{$alias}'");
		return $submenu;
	}
	function getSubMenuId($parent_id, $alias){
		$tmp = $this->getAll("parent_id='{$parent_id}' and alias='{$alias}'");
		return !empty($tmp) ? $tmp[0][$this->pkey] : 0;
	}
	function getLinkType(){
		global $core, $clsISO,$clsConfig;
		$lstType = array();
		$lstType['frontpage'] = 'Trang chủ';
		
			$lstType['product'] = 'Sản phẩm';
			$lstType['catalog'] = 'Tất cả sản phẩm';
			$lstType['collection'] = 'Danh mục sản phẩm';
			$lstType['component'] = 'Danh mục loại sản phẩm';
		if($clsConfig->get('product',0)==1){}
		if($clsConfig->get('service',0)==1){
			$lstType['service'] = $core->get_Lang('Service');
			$lstType['serviceall'] = $core->get_Lang('ServiceAll');
			if($clsConfig->get('service_category',0)==1){
				$lstType['servicecategory'] = $core->get_Lang('ServiceCategory');
			}
		}
		if($clsConfig->get('project',0)==1){
			$lstType['project'] = $core->get_Lang('Project');
			$lstType['projectall'] = $core->get_Lang('ProjectAll');
			if($clsConfig->get('project_category',0)==1){
				$lstType['projectcategory'] = $core->get_Lang('ProjectCategory');
			}
		}
		$lstType['newscategory'] = $core->get_Lang('Newscategory');
		$lstType['news'] = $core->get_Lang('News');
		$lstType['newsall'] = 'Tất cả tin tức';
		$lstType['page'] = 'Trang nội dung';
		$lstType['search'] = 'Trang tìm kiếm';
		$lstType['http'] = 'Địa chỉ web';
		return $lstType;
	}
	function getItems($parent_id){
		return $this->getAll("parent_id='{$parent_id}' order by reg_date ASC");
	}
	function getItemsByAlias($alias, $parent_id=0){
		$results = array();
		$tmp = $this->getAll("alias='{$alias}' and parent_id='{$parent_id}'", "menu_id,parent_id,links");
		if(!empty($tmp)){
			$links = !empty($tmp[0]['links']) ? @json_decode($tmp[0]['links'], true) : array();
			if(!empty($links)){
				foreach($links as $link){
					$link['menu_id'] = $tmp[0]['menu_id'];
					$link['parent_id'] = $tmp[0]['parent_id'];
					$results[] = $link;
				}
			}
		}
		return $results;
	}
	function getCheckAlias($pvalTable) {
        global $_LANG_ID,$core;
		$one=$this->getOne($pvalTable);
		$parent_id = $one['parent_id'];
		$alias = $one['alias'];
		
		$links = $this->getOneField("links", $pvalTable);
		$links = !empty($links) ? @json_decode($links, true) : array();
		
		$title = $links[0]['title'];
		$slug = $core->replaceSpace($title);
		
		$res = $this->getAll("is_trash=0 and menu_id='$parent_id' and alias='$slug' LIMIT 0,1");
		$aaa ="is_trash=0 and menu_id='$parent_id' and alias='$slug' LIMIT 0,1";
		
		$menu_id = $res[0][$this->pkey];
		return $slug;
    }
	function getLink($source=array()){
		global $core, $dbconn, $extLang, $_LANG_ID, $clsISO, $clsConfiguration;
		$type = $source['type'];
		if($type=='http') return $source['url'];
		if($type=='faqs') return '/faqs.html';
		if($type=='frontpage') return PCMS_URL;
		if($type=='catalog') return '/san-pham.html';
		if($type=='newsall') return '/tin-tuc/';
		if($type=='serviceall') return '/dich-vu/';
		if($type=='projectall') return '/du-an/';
		if($type=='search') return '/search.html';
		if(in_array($type, ['collection','newscategory','servicecategory','projectcategory','component'])) $clsTable = new Category();
		if($type=='product') $clsTable = new Product();
		if($type=='news') $clsTable = new News();
		if($type=='page') $clsTable = new Page();
		if($type=='service') $clsTable = new Service();
		if($type=='project') $clsTable = new Project();
		return $clsTable->getLink($source['id']);
	}
	function getTitleLink($id, $type){
		if(!empty($type) && !in_array($type, array('newsall','serviceall','projectall'))){
			if(in_array($type, ['collection','newscategory','servicecategory','projectcategory'])) $clsTable = new Category();
			if($type=='product') $clsTable = new Product();
			if($type=='news') $clsTable = new News();
			if($type=='service') $clsTable = new Service();
			if($type=='project') $clsTable = new Project();
			if($type=='page') $clsTable = new Page();
			if($type=='faq') $clsTable = new FAQ();
			return $clsTable->getTitle($id);
		}
		return "";
	}
	function getLinks($menu_id){
		$links = $this->getOneField("links", $menu_id);
		return !empty($links) ? @json_decode($links, true) : array();
	}
}
?>