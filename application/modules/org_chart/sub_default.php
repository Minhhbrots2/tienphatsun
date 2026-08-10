<?php if (!defined('ABSPATH')) exit('No direct script access allowed');

/*======================================================================*\

|| #################################################################### ||

|| # The Classes configurations of the MaxxCMS                        # ||

|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||

|| # ---------------------------------------------------------------- # ||

|| # All PHP code in this file is ©2014-2015 Future Group.        	  # ||

|| # This file may not be redistributed in whole or significant part. # ||

|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||

|| #################################################################### ||

\*======================================================================*/

function default_default(){

	###

	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$act;

	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$assign_list,$title_page;	

	$clsProfile = new Profile(); $smarty->assign("clsProfile",$clsProfile);

	$clsProperty = new Property(); $smarty->assign("clsProperty",$clsProperty);

	$lstProfile = $clsProfile->getAll("`is_trash`='0' AND`status_id` <> '"._STATUS_STAFF_OFF_ID."'");

	$smarty->assign("lstProfile",$lstProfile);

	

	require_once(DIR_INCLUDES.'/json_master/autoload.php');

	$cachedFile = DIR_CACHE_JSON.'/org/org_chart.json';

	$arr_node_level = [];

	if(@file_exists($cachedFile)){

		$decoder = new Webmozart\Json\JsonDecoder();

		$data_node = $decoder->decodeFile($cachedFile);

		$arr_data_node = $clsISO->to_array_json($data_node);

		$lst_node = !empty($arr_data_node['nodes']) ? $arr_data_node['nodes'] : array();

		$lst_point = !empty($arr_data_node['points']) ? $arr_data_node['points'] : array();

		if(!empty($lst_node)) {

			foreach ($lst_node as $key => $val) {

				if(!empty($val['staff_id'])) {

					if(!isset($arr_cache_profile[$val['staff_id']])) {

						$arr_cache_profile[$val['staff_id']] = $clsProfile->getOne($val['staff_id']);

					}	

					$lst_node[$key]['oneStaff'] = $arr_cache_profile[$val['staff_id']];

				}

				if(!isset($arr_cache_property[$val['role_id']])) {

					$arr_cache_property[$val['role_id']] = $clsProperty->getOne($val['role_id']);

				}	

				$lst_node[$key]['oneRole'] = $arr_cache_property[$val['role_id']];

				$lst_node[$key]['arr_parent'] = !empty($val['commonParentIds']) ? explode(",",$val['commonParentIds']) : array();

				$arr_node_level[$val["level"]][] = $lst_node[$key];

			}

		}

		$assign_list["lst_node"] = $lst_node;

		$assign_list["lst_point"] = $lst_point;

	}

	$assign_list["arr_node_level"] = $arr_node_level;

	/*=============Title & Description Page==================*/

	$title_page = 'Sơ đồ cơ cấu tổ chức '.BRAND_NAME.' - '.PAGE_NAME;

	$assign_list["title_page"] = $title_page;

}

