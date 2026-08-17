<?php

	global $core,$smarty,$clsISO,$deviceType,$dbconn,$profile_id;

	$clsShare = new Share();

	$clsBilling = new Billing();

	$clsProperty = new Property();

	$clsProfile = new Profile();

	$clsProject = new Project();

	$clsStock = new Stock();

	$user_id = _PROFILE_NQH_ID;

	$oneUser = $clsProfile->getProfile($user_id);

	//$clsISO->print_pre($oneUser);die;
	$show_mileston = $clsISO->screenSales();

	$cond = "`is_trash`=0 AND `is_cancel`='0' AND FROM_UNIXTIME(`deposit_date`,'%Y')='".date("Y")."'";

	$limitCond = " LIMIT 0,20";

	$total_record = $show_mileston ? $clsBilling->countItem($cond) : 0;

	$lstBilling = array();

	if($show_mileston) {

		$lstBilling = $clsBilling->getAll($cond. " ORDER BY `deposit_date` DESC ".$limitCond,"`{$clsBilling->pkey}`,`more_information`,`billing_code`,`staff_id`,`deposit_date`,`project_id`,`totalgrand`");

	}
	$arr_block = $clsProperty->getArraySearchByKey("_BLOCK");

	$arr_bedroom = $clsProperty->getArraySearchByKey("_BEDROOM");

	$arr_type_villa = $clsProperty->getArraySearchByKey("_TYPE_VILLA");

	$arr_cache_profile = $clsProfile->getProfileCached();

	$arr_cache_project = $clsProject->getListProject();

	$bill_number = $total_record;

	

	$ii = 2;

	foreach ($lstBilling as $key => $val) {

		$staff_id = (int) $val['staff_id'];

		$project_id = (int) $val['project_id'];

		$more_information = $val['more_information'];

		$more_information = $clsISO->to_array_json($more_information);

		if((empty($more_information["bedroom_id"]) && empty($more_information["type_id"])) || empty($more_information["stock_type"])){	

			$stock_id = (int) $core->get_field($more_information, "stock_id", 0);

			if($stock_id > 0){

				$oneStock = $clsStock->getOne($stock_id,"bedroom_id,stock_type,type_id");

				$more_information["bedroom_id"] = $oneStock["bedroom_id"];

				$more_information["stock_type"] = $oneStock["stock_type"];

				$more_information["type_id"] = $oneStock["type_id"];	

				$clsBilling->updateOne($val[$clsBilling->pkey],["more_information" => json_encode($more_information)]);

			}

		}

		$block_id = !empty($more_information["block_id"]) ? $more_information["block_id"] : 0;

		if(!empty($block_id)) {

			$oneBlock = $arr_block[$block_id];

			$more_block = $oneBlock["more_information"];

			if(!empty($more_block["is_project"])) {

				$lstBilling[$key]["project_name"] = $oneBlock["property_code"];

			}else{

				$lstBilling[$key]["project_name"] = $arr_cache_project[$project_id]["code"] ;

			}

		}else{

			$lstBilling[$key]["project_name"] = $arr_cache_project[$project_id]["code"] ;

		}

		$oneStaff = $arr_cache_profile[$val["staff_id"]];

		if($more_information["stock_type"] == _BLOCK_TYPE_HIGHLEVEL_SALE) {

			$bedroom_name = $arr_bedroom[$more_information["bedroom_id"]]["title"];

			$lstBilling[$key]["bedroom"] = $bedroom_name;

			$lstBilling[$key]["content"] = "Chúc mừng <strong>{$oneStaff["full_name"]}</strong> đã chốt thành công căn hộ cao tầng {$bedroom_name} <strong class='text-main'>{$val["stock_code"]}</strong>. Tiếp tục bứt phá chinh phục những cột mốc mới nhé!";

		}elseif($more_information["stock_type"] == _BLOCK_TYPE_LOWFLOOR_SALE) {

			$type_villa = $arr_type_villa[$more_information["type_id"]]["title"];

			$lstBilling[$key]["type_villa"] = $type_villa;

			$lstBilling[$key]["content"] = "Chúc mừng <strong>{$oneStaff["full_name"]}</strong> đã chốt thành công căn hộ {$type_villa} <strong class='text-main'>{$val["stock_code"]}</strong>. Tiếp tục bứt phá chinh phục những cột mốc mới nhé!";

		}

		$image_poster = $core->get_field($more_information, "image_poster", "");

		$lstBilling[$key]["image_poster"] = !empty($image_poster) ? $clsISO->getGoogleUrl($image_poster) : "";

		$lstBilling[$key]["more_information"] = $more_information;

		$lstBilling[$key]["oneStaff"] = $oneStaff;

		$lstBilling[$key]["total_price"] = $clsISO->shortNumber($val["totalgrand"],1);

		$lstBilling[$key]["bill_number"] = $bill_number;

		if($val["staff_id"] == $profile_id) {

			$lstBilling[$key]["order_no"] = 1;

		}else{

			$lstBilling[$key]["order_no"] = $ii;

			++$ii;

		}

		

		--$bill_number;

		unset($image_poster);

	}

	$smarty->assign("lstBilling",$lstBilling);

	#

	if($deviceType=='computer'){

		$list_boxs['birthday'] = array(

			'title' => 'Chúc mừng Sinh nhật',

			'link' => '',

			'icon' => 'bx-cake',

			'empty' => 'Chưa có thiệp sinh nhật nào',

			'images' => $clsShare->getIMG('birthday')

		);

		$list_boxs['wellcome'] = array(

			'title' => 'Chào đón thành viên mới',

			'link' => '',

			'icon' => 'bx-user-plus',

			'empty' => 'Chưa có ảnh thành viên mới',

			'images' => $clsShare->getIMG('wellcome')

		);

	}

	$smarty->assign("list_boxs",$list_boxs);

	$smarty->assign("user_id",$user_id);

	$smarty->assign("oneUser",$oneUser);

	$smarty->assign("clsShare",$clsShare);

	$smarty->assign("clsProfile",$clsProfile);

?>