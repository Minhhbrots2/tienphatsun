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
class Tour extends dbBasic{
	function __construct(){
		$this->pkey = "tour_id";
		$this->tbl = DB_PREFIX."tour";
	}
	function getSlash($level){
		return str_repeat("------", $level + 1);
	}
	function getTitle($tour_id, $oDataTable = []){
		global $_LANG_ID;
		if (!isset($oDataTable["title"])) {
			$oDataTable = $this->getOne($tour_id, "title");
		}
		return $oDataTable["title"];
	}
	function getSlug($tour_id, $oDataTable = []){
		global $_LANG_ID;
		if (!isset($oDataTable["slug"])) {
			$oDataTable = $this->getOne($tour_id, "slug");
		}
		return $oDataTable["slug"];
	}
	function getBySlug($slug){
		$all = $this->getAll(
			"is_trash=0 and (slug_en='$slug' or slug_vn='$slug') order by " .
				$this->pkey .
				" limit 0,1"
		);
		return $all[0][$this->pkey];
	}
	function getLink($tour_id, $oDataTable = []){
		global $extLang, $_LANG_ID;
		$link = "/du-an/";
		if ($_LANG_ID == "en") {
			$link = "/projects/";
		}
		return $link . $this->getSlug($tour_id, $oDataTable) . ".html";
	}
	function getImage($tour_id, $w, $h, $oDataTable = []){
		global $_LANG_ID, $clsISO;
		if (!isset($oDataTable["image"])) {
			$oDataTable = $this->getOne($tour_id, "image");
		}
		$image = $oDataTable["image"];
		if (!empty($image) && file_exists(ABSPATH . DS . $image)) {
			return "/files/thumb/" .$w ."/" .$h ."/" .$clsISO->parseImageURL($image);
		}
		return URL_IMAGES . "/noimage.png";
	}
	function getIntro($tour_id, $oDataTable = []){
		global $_LANG_ID;
		if (isset($oDataTable["intro_" . $_LANG_ID])) {
			$oDataTable = $this->getOne($tour_id, "intro_" . $_LANG_ID);
		}
		return html_entity_decode($oDataTable["intro_" . $_LANG_ID]);
	}
	function getContent($tour_id, $oDataTable = []){
		global $_LANG_ID;
		if (isset($oDataTable["content"])) {
			$oDataTable = $this->getOne($tour_id, "content");
		}
		return html_entity_decode($oDataTable["content"]);
	}
}