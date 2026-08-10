<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is Â©2014-2015 Future Group.        	  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
$app->post('/shop/add', function ($request, $response) use ($app) {
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $dbconn, $clsISO;
	$clsISO = new ISO();
	$helper = new Helper();
	$clsFcmToken = new FcmToken();
	$clsProperty = new Property();
	$clsProject = new Project();
	$clsSetting = new Setting();
	$clsShop = new Shop();
	$status_code = 400;
	$apiresults = array(
		'error' => 1, 
		'message' => "Error"
	);
	###
	$inputs = $request->getParsedBody();
	$title  = $helper->getFieldValue("title", $inputs);
	$slug  = $clsISO->replaceSpace($title);
	$category  =$helper->getFieldValue("category", $inputs);
	$oneCategory = $clsProperty->getByCond("`property_type`='_SHOP' AND `slug`='".$clsISO->replaceSpace($category)."' AND `parent_id`='0'");
	$cat_id = 0;
	if(!empty($oneCategory)){
		$cat_id = $oneCategory[$clsProperty->pkey];
	}else{
		$max_id = $clsProperty->getMaxId();
		/*if($clsClassTable->insert(array(
			$clsProperty->pkey => $max_id,
			'title' => $category,
			'slug' => $core->replaceSpace($category),
			'property_type' => "_SHOP",
			'parent_id'	=>	0,
			'reg_date' => time(),
			'upd_date' => time(),
			'user_id' => 0,
			'user_id_update' => 0,
			'is_trash' => 0,
			'order_no' => $clsProperty->getMaxOrderNo(),
		))){
			$cat_id = $max_id;
		}*/
	}
//	echo json_encode($inputs,JSON_UNESCAPED_UNICODE);die;
	$category_child = $helper->getFieldValue("category_child", $inputs);
	$arr_child = !empty($category_child) ? explode(",",$category_child) : array();
	$arr_category_id = [];
	if(!empty($arr_child)) {
		foreach ($arr_child as $cat_child_name) {
			$oneCategoryChild = $clsProperty->getByCond("`property_type`='_SHOP' AND `slug`='".$clsISO->replaceSpace(trim($cat_child_name))."' AND `parent_id`='{$cat_id}'");
			$cat_child_id = 0;
			if(!empty($oneCategoryChild)){
				$cat_child_id = $oneCategoryChild[$clsProperty->pkey];
			}else{
				$max_id = $clsProperty->getMaxId();
				/*if($clsClassTable->insert(array(
					$clsProperty->pkey => $max_id,
					'title' => trim($cat_child_name),
					'slug' => $core->replaceSpace(trim($cat_child_name)),
					'property_type' => "_SHOP",
					'parent_id'	=>	$cat_id,
					'reg_date' => time(),
					'upd_date' => time(),
					'user_id' => 0,
					'user_id_update' => 0,
					'is_trash' => 0,
					'order_no' => $clsProperty->getMaxOrderNo(),
				))){
					$cat_child_id = $max_id;
				}*/
			}
			if(!empty($cat_child_id)) {
				$arr_category_id[] = $cat_child_id;
			}
		}		
	}
	$list_cat_id = $clsISO->makeSlashListFromArrayRoot($arr_category_id);
	#
	$project_name  =$helper->getFieldValue("project_name", $inputs);
	$oneProject = $clsProject->getByCond("`slug`='".$clsISO->replaceSpace($project_name)."' OR `code`='".$project_name."'",$clsProject->pkey);
	$project_id = !empty($oneProject) ? $oneProject[$clsProject->pkey] : 0;
	#
	$block_name  =$helper->getFieldValue("block_name", $inputs);
	$oneBlock = $clsProperty->getByCond("`property_type`='_BLOCK' AND (`slug`='".$clsISO->replaceSpace($block_name)."' OR `property_code`='{$block_name}')",$clsProperty->pkey.",parent_id");
	$block_id = !empty($oneBlock) ? $oneBlock[$clsProperty->pkey] : 0;
	#
	$building_name  =$helper->getFieldValue("building_name", $inputs);
	$property_type = "_BUILDING";
	if(!empty($oneBlock)){
		if($oneBlock["parent_id"] == _BLOCK_TYPE_LOWFLOOR_SALE){
			$property_type = "_RANGE";
		}else{
			$property_type = "_BUILDING";
		}
	}
	$oneBuilding = $clsProperty->getByCond("`property_type`='{$property_type}' AND (`slug`='".$clsISO->replaceSpace($building_name)."' OR `property_code`='{$building_name}')");
	$building_id = !empty($oneBuilding) ? $oneBuilding[$clsProperty->pkey] : 0;
	$address  =$helper->getFieldValue("address", $inputs);
	#
	$str_tags  =$helper->getFieldValue("tags", $inputs);
	$arr_tags = $clsISO->getArrayByTextSlash($str_tags);
//	echo json_encode($arr_tags);die;
//	$clsISO->print_pre($arr_tags);die;
//	$arr_tags = !empty($str_tags) ? explode(",",$str_tags) : array();
	$arr_slug_tag = [];
	foreach ($arr_tags as $key => $tag) {
		$arr_tags[$key] = trim($tag);
		$arr_slug_tag[] = $clsISO->replaceSpace($tag);
	}
	$tags = implode(",",$arr_tags);
	$slug_tags = $clsISO->makeSlashListFromArrayRoot($arr_slug_tag);
	#
	$map  = $helper->getFieldValue("map", $inputs);
	#
	$phone  = $helper->getFieldValue("phone", $inputs);
	$open_at_time  = $helper->getFieldValue("open_at_time", $inputs);
	$open_at_time = (!empty($open_at_time)) ? date("H:i", strtotime($open_at_time)) : "";
	#
	$close_at_time  = $helper->getFieldValue("close_at_time", $inputs);
	$close_at_time = (!empty($close_at_time)) ? date("H:i", strtotime($close_at_time)) : "";
	$image  = $helper->getFieldValue("image", $inputs);
	$intro  = $helper->getFieldValue("intro", $inputs);
	$image_menu  = $helper->getFieldValue("image-menu", $inputs);
	$image_menu = !empty($image_menu) ? $clsShop->crawlDriver($image_menu) : array();
	$image_content  = $helper->getFieldValue("image-content", $inputs);	
	$image_shop_gallery = !empty($image_content) ? $clsShop->crawlDriver($image_content) : array();
	#
	$template = $helper->getFieldValue("template", $inputs);	
	$oneTemplate = $clsSetting->getByCond("`_type`='_LIST_FORM_BUSINESS' AND `slug`='".$clsISO->replaceSpace($template)."'",$clsSetting->pkey.",more_information");
	$template_id = !empty($oneTemplate) ? $oneTemplate[$clsSetting->pkey] : 0;
	$more = $more_information = [];
	#
	if(!empty($template_id)) {		
		$more['dynamic']["template_id"] = $template_id;
		$more_template = $clsISO->to_array_json($oneTemplate["more_information"]);
		foreach ($more_template["configForm"] as $key => $_oField) {
			$field_value = $helper->getFieldValue("dynamic_".$_oField["field_code"], $inputs);
			if($_oField["type"] == "input_checkbox" || $_oField["type"] == "select_multiple") {
				$field_value = str_replace(" |","|",$field_value);
				$field_value = str_replace("| ","|",$field_value);
				$field_value = $clsISO->getArrayByTextSlash($field_value,"|");
			}
			$more['dynamic']["dynamic_".$_oField["field_code"]] = $field_value;
			unset($field_value);
		}
		/*foreach ($inputs as $keyField => $valueField) {
			if (strpos($keyField, 'dynamic_') === 0) {
				#y tế
				if($keyField == "dynamic_time_work") {
					$valueField = $clsISO->getArrayByTextSlash($valueField,"|");
				}
				$more['dynamic'][$keyField] = $valueField;
			}
		}*/
	}
	#	
//	$dbconn->debug= true;
	$oneShop = $clsShop->getByCond("`slug`='{$slug}' AND `project_id`='{$project_id}' AND `block_id`='{$block_id}' AND `building_id`='{$building_id}'");
//	echo json_encode($oneShop,JSON_UNESCAPED_UNICODE);die;
	if(!empty($oneShop)){
		$shop_id = $oneShop[$clsShop->pkey];
		$more_information = $clsISO->to_array_json($oneShop["more_information"]);
		$more_information["phone"] = !empty($phone) ? $phone : (!empty($more_information["phone"]) ? $more_information["phone"] : "");
		$more_information["address"] = !empty($address) ? $address : (!empty($more_information["address"]) ? $more_information["address"] : "");
		$more_information["map"] = !empty($map) ? $map : (!empty($more_information["map"]) ? $more_information["map"] : "");
		$more_information["dynamic"] = !empty($more['dynamic']) ? $more['dynamic'] : (!empty($more_information["dynamic"]) ? $more_information["dynamic"] : []);
		$more_information["open_at_time"] = !empty($open_at_time) ? $open_at_time : (!empty($more_information["open_at_time"]) ? $more_information["open_at_time"] : "");
		$more_information["close_at_time"] = !empty($close_at_time) ? $close_at_time : (!empty($more_information["close_at_time"]) ? $more_information["close_at_time"] : "");
		$more_information["image_menu"] = !empty($image_menu) ? $image_menu : (!empty($more_information["image-menu"]) ? $more_information["image-menu"] : []);
		$more_information["image_shop_gallery"] = !empty($image_shop_gallery) ? $image_shop_gallery : (!empty($more_information["image_shop_gallery"]) ? $more_information["image_shop_gallery"] : []);
		$data_upd = [
			"title"				=>	$title,
			"slug"				=>	$slug,
			"cat_id"			=>	$cat_id,
			"list_cat_id"		=>	$list_cat_id,
			"tags"				=>	$tags,
			"slug_tags"			=>	$slug_tags,
			"image"				=>	$image,
			"more_information"	=>	json_encode($more_information,JSON_UNESCAPED_UNICODE),
			"intro"				=>	addslashes(trim($intro)),
			"upd_date"			=>	time()
		];
//		$clsISO->print_pre($data_upd);die;
		if($clsShop->updateOne($shop_id,$data_upd)) {
			$status_code = 200;
			$apiresults = array(
				'error' => 0, 
				'message' => 'Update Success', 
				'data' => $data_upd, 
			);
		}
	}else{
		$shop_id = $clsShop->getMaxId();
		$more_information = [
			"map"	=>	!empty($map) ? $map : "",
			"phone"	=>	!empty($phone) ? $phone : "",
			"address"	=>	!empty($address) ? $address : "",
			"dynamic"	=>	!empty($more['dynamic']) ? $more['dynamic'] : [],
			"open_at_time"	=>	!empty($open_at_time) ? $open_at_time : "",
			"close_at_time"	=>	!empty($close_at_time) ? $close_at_time : "",
			"image_menu"	=>	!empty($image_menu) ? $image_menu : [],
			"image_shop_gallery"	=>	!empty($image_shop_gallery) ? $image_shop_gallery : [],
		];
		$data_upd = [
			$clsShop->pkey	=>	$shop_id,
			"title"			=>	$title,
			"slug"			=>	$slug,
			"cat_id"		=>	$cat_id,
			"list_cat_id"	=>	$list_cat_id,
			"project_id"	=>	$project_id,
			"block_id"		=>	$block_id,
			"building_id"	=>	$building_id,
			"tags"			=>	$tags,
			"slug_tags"		=>	$slug_tags,
			"image"			=>	$image,
			"more_information"	=>	json_encode($more_information,JSON_UNESCAPED_UNICODE),
			"intro"			=>	addslashes(trim($intro)),
			"is_online"		=>	1,
			"is_trash"		=>	0,
			"reg_date"		=>	time(),
			"upd_date"		=>	time(),
		];
//		echo "insert";
//		$clsISO->print_pre($data_upd);die;
		if($clsShop->insert($data_upd)) {
			$status_code = 200;
			$apiresults = array(
				'error' => 0, 
				'message' => 'Insert Success', 
				'data' => $data_upd, 
			);
		}
	}
	// Return
	echo echoResponse($status_code, $apiresults);
});
?>