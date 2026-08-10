<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the ISOCMS                         # ||
|| # ISOCMS 6.0.0 VietISO Techical Team (vanthiembui.it@gmail.com)    # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 VietISO JSC.             # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||
|| #################################################################### ||
\*======================================================================*/
global $assign_list, $core, $dbconn, $mod, $act, $sub, $_LANG_ID,$title_page,$description_page
,$keyword_page,$global_title_page,$global_description_page,$global_keyword_page, $clsISO, $clsConfiguration;
#
$current_year = date('Y',time());
$assign_list["current_year"] = $current_year;

$get_stock_type = vnSessionExist('_ss_stock_type') ? vnSessionGetVar('_ss_stock_type') : _BLOCK_TYPE_HIGHLEVEL_SALE;
$assign_list["get_stock_type"] = $get_stock_type;
#
$config_name = sprintf("Helper_%s_%s_%s",$mod, $sub, $act);
$helper_page = $clsConfiguration->getValue($config_name);
$helper_page = $clsISO->to_array_json($helper_page);
$assign_list["helper_page"] = $helper_page;


/*==================================#========================================*/
$clsMeta = new Meta();
$protocol = empty($_SERVER['HTTPS']) ? 'http' : 'https';
$full_link = $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$current_page_link = str_replace(PCMS_URL,'/',$full_link);
$current_page_link = str_replace('//','/',$current_page_link);

$assign_list["full_link"] = $full_link;
$assign_list["global_title_page"] = $title_page;
$assign_list["global_description_page"] = $description_page;
$assign_list["global_keyword_page"] = $keyword_page;
?>