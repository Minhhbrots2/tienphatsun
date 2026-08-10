<?php if (!defined('ABSPATH')) exit('No direct script access allowed');



function default_default(){

	global $assign_list, $_CONFIG, $core, $dbconn, $mod, $act, $_LANG_ID;

	global $title_page, $description_page, $keyword_page, $clsConfiguration, $clsISO;



	$title_page = 'Nền tảng bất động sản toàn quốc - ' . BRAND_NAME;

	$assign_list["title_page"] = $title_page;

	

	// SEO Meta

	$description_page = $clsConfiguration->getValue('meta_description');

	$assign_list["description_page"] = $description_page;

	$keyword_page = $clsConfiguration->getValue('meta_keyword');

	$assign_list["keyword_page"] = $keyword_page;

}

