<?php
/**
 *  Created by   :
 *  @author		: Technical Group (technical@aboutpro.com)
 *  @date		: 2009/1/18
 *  @version		: 2.1.1
 */
class Region extends dbBasic {
    function Region() {
        $this->pkey = "region_id";
        $this->tbl = DB_PREFIX."region";
    }
    function getTitle($pvalTable) {
        $one = $this->getOne($pvalTable);
        return $one['title'];
    }
    function getSlug($pvalTable) {
        $one = $this->getOne($pvalTable);
        return $one['slug'];
    }
	function checkAvailble($country_id){
    	$n = $this->countItem("is_trash=0 and country_id='$country_id'");
	  	return ($n > 0) ? 1: 0;
    }
    function getBySlug($slug) {
        $res = $this->getAll("is_trash=0 and slug = '" . $slug . "'");
        return $res[0]['area_id'];
    }
    function getIntro($pvalTable) {
        $one = $this->getOne($pvalTable);
        return html_entity_decode($one['intro']);
    }
    function getContent($pvalTable) {
        $one = $this->getOne($pvalTable);
        return html_entity_decode($one['content']);
    }
    function getStripIntro($pvalTable) {
        $one = $this->getOne($pvalTable);
        if (!empty($one['intro']))
            return strip_tags(html_entity_decode($one['intro']));
        return strip_tags(html_entity_decode($one['content']));
    }
    function getByCountryLimit($country_id, $limit) {
        return $this->getAll("is_trash=0 and country_id='$country_id' order by order_no asc limit 0,$limit");
    }
    function checkSlug($slug) {
        global $_LANG_ID;
        $res = $this->getAll("slug='" . $slug . "'");
        if (is_array($res) && count($res) > 0)
            return 0;
        return 1;
    }
    function getImage($pvalTable, $w, $h) {
        global $clsISO;
        #
        $oneTable = $this->getOne($pvalTable, "image");
        if ($oneTable['image'] != '') {
            $image = $oneTable['image'];
            return '/files/thumb/' . $w . '/' . $h . '/' . $image;
        }
        return URL_IMAGES . '/noimage.png';
    }
    function getLink($pvalTable) {
        $clsCountry = new Country();
        $one = $this->getOne($pvalTable);
        $link = '/vung-mien/' . $this->getSlug($pvalTable) . '-c' . $pvalTable;
        return $link;
    }
    function countNumberCity($region_id) {
        $clsCity = new City();
		return $clsCity->countItem("is_trash='0' and region_id='$region_id'");
    }
    function checkExits($country_id = 0, $continent_id = 0, $region_id = 0) {
		global $core, $dbconn;
		#
        $cond = "is_trash=0";
        if (intval($continent_id) != 0) {
            $cond.= " and continent_id = '$continent_id'";
        }
        if (intval($country_id) != 0) {
            $cond.= " and country_id = '$country_id'";
        }
        if(intval($region_id) != 0) {
            $cond.= " and region_id = '$region_id'";
        }
		#
		$sql = "SELECT region_id FROM ".DB_PREFIX."region WHERE ".$cond." limit 0,1";
        $res = $dbconn->GetAll($sql);
		return !empty($res) ? 1 : 0;
    }
    function makeSelectboxOption($country_id=0,$region_id=0){
        global $core;
		#
		$cond = "is_trash=0";
		if(intval($country_id)) {$cond.= " and country_id = '$country_id'";}
        $lstItem = $this->getAll($cond." order by order_no asc", $this->pkey);
		#
        $html = '<option value=""> -- ' . $core->get_Lang('selecregion') . ' -- </option>';
	    if(is_array($lstItem) && count($lstItem)>0){
            foreach ($lstItem as $k => $v) {
				$selected_index = ($region_id == $v[$this->pkey]) ? 'selected="selected"' : '';
				$html.='<option value="'.$v[$this->pkey].'" '.$selected_index.'>' . $this->getTitle($v[$this->pkey]) . '</option>';
            }
			unset($lstItem);
        }
        return $html;
    }
}
?>