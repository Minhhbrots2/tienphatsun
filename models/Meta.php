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
class Meta extends dbBasic {
    function __construct() {
        $this->pkey = "meta_id";
        $this->tbl = DB_PREFIX . "meta";
    }
	function getPreviewSEO($pvalTable, $config=array()){
		global $core, $clsISO;
		$clsMeta = new Meta();
		$html = '';
		$tmp = $clsMeta->getOne($pvalTable);
		
		if(!empty($tmp)){
			$config_value_title = $tmp['config_value_title'];
			$config_value_intro = $tmp['config_value_intro'];
			$html = '<div class="preview-search">
				<p class="title title-prev-inline size18">'.$config_value_title.'</p>
				<p class="link">'.DOMAIN_NAME.$linkMeta.'</p>
				<p class="desctiption title-desc-inline type--subdued">'.$clsISO->truncate($config_value_intro,320).'</p>
			</div>';
		} 
		return $html;
	}
    function checkExist($pvalTable, $clsTable) {
        $clsClassTable = new $clsTable();
        $cur_link = $clsClassTable->getPermalink($pvalTable);
        $res = $this->getAll("config_value_permalink='$cur_link' limit 0,1");
        return (!empty($res)) ? 1 : 0;
    }
    function getByCurrentLink($pvalTable, $clsTable) {
        $clsClassTable = new $clsTable();
        $cur_link = $clsClassTable->getPermalink($pvalTable);
        $meta_id = $this->getByPermalink($cur_link);
        return $meta_id;
    }
    function replaceTitle($pvalTable, $clsTable) {
        $clsClassTable = new $clsTable();
        $title = $clsClassTable->getTitle($pvalTable);
        return $title;
    }
    function getByPermalink($config_value_permalink) {
        $all = $this->getAll("is_trash=0 and (config_value_permalink='$config_value_permalink') order by " . $this->pkey . " limit 0,1");
        return $all[0][$this->pkey];
    }
    function getValue($link) {
        global $_LANG_ID;
        $one = $this->getAll("config_link='$link'");
        return $one[0];
    }
    function getMetaTitle($meta_id) {
        global $_LANG_ID;
        $one = $this->getOne($meta_id);
        return $one['config_value_title'];
    }
    function getMetaDescription($meta_id) {
        global $_LANG_ID;
        $one = $this->getOne($meta_id);
        return $one['config_value_intro'];
    }
    function getMetaKeyword($meta_id) {
        global $_LANG_ID;
        $one = $this->getOne($meta_id);
        return $one['config_value_keyword'];
    }
    function getStatus($field, $pvalTable) {
        if ($field == 'title') {
            $title = $this->getMetaTitle($pvalTable);
            $number = strlen($title);
            if ($number > 70) {
                return '<strong style="color:red">' . $number . '</strong>';
            }
        }
        if ($field == 'description') {
            $description = $this->getMetaDescription($pvalTable);
            $number = strlen($description);
            if ($number > 160) {
                return '<strong style="color:red">' . $number . '</strong>';
            }
            if ($number < 70) {
                return '<strong style="color:#ccc">' . $number . '</strong>';
            }
        }
        if ($field == 'keyword') {
            return '<strong style="color:#0C0">Good</strong>';
        }
        $status_ok = '<strong style="color:#0C0">' . $number . '</strong>';
        $status_failed = '<strong style="color:red">' . $number . '</strong>';
        $status_failed_short = '<strong style="color:red">' . $number . '</strong>';
        return $status_ok;
    }
    function getConfigLink($pvalTable) {
        $one = $this->getOne($pvalTable);
        return $one['config_link'];
    }
}
?>