<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2014-2015 Future Group.        	  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| #################################################################### ||
\*======================================================================*/
function default_default(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$image_page,$type_list,$profile_id;
}
function default_view_billing(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$image_page,$type_list,$profile_id;
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsCommission = new Commission();
	
	$uid = $clsISO->getUniqid();
	$billing_id = (int) Input::post('billing_id', 0);
	// $dbconn->debug = true;
	$field = "`t1`.*,`t2`.`more_information` as `commission_information`";
	$oneBilling = $dbconn->getRow("SELECT {$field} FROM {$clsBilling->tbl} AS `t1` 
		LEFT JOIN {$clsCommission->tbl} AS `t2` ON 	`t1`.`billing_id`=`t2`.`billing_id` 
		WHERE `t1`.`billing_id`='{$billing_id}'");
	$staff_id = $oneBilling['staff_id'];
	$more_information = $oneBilling['more_information'];
	$commission_information = $oneBilling['commission_information'];
	$more_information = $clsISO->to_array_json($more_information);
	$commission_information = $clsISO->to_array_json($commission_information);
	$dep_logs = $core->get_field($more_information, "dep_logs", []); // Logs
	$head_of_team_id = (int) $core->get_field($dep_logs, 'head_of_team_id', 0); // TPKD
	$head_of_dep_id = (int) $core->get_field($dep_logs, 'head_of_dep_id', 0); // GĐKD
	$project_director_id = (int) $core->get_field($dep_logs, 'project_director_id', 0); // GĐDA
	
	$htmlTable= '<tr>
		<td class="text-right" width="40%">Mã căn</td>
		<td class="">'.$oneBilling['stock_code'].'</td>
	</tr>';
	// $clsISO->print_pre($commission_information); die();
	$tax_vat_amount = $personal_tax_amount = $advance_paid_amount = 0;
	if(!empty($commission_information)){
		$contract_comm_base = $core->get_field($commission_information, "contract_comm_base", 0);
		$sales_commission_rate = $sales_commission_amount = $sales_total_amount = $sales_bonus_amount = 0;
		if($profile_id == $staff_id){ // Mình bán
			$sales_commission_rate += (float) $core->get_field($commission_information, "sales_commission_rate", 0);
			$sales_commission_amount += (float) $core->get_field($commission_information, "sales_commission_amount", 0);
			$sales_bonus_amount += (float) $core->get_field($commission_information, "sales_bonus_amount_out", 0);
			$sales_total_amount += (float) $core->get_field($commission_information, "sales_total_amount", 0);
			$tax_vat_amount = (float) $core->get_field($commission_information, "tax_vat_amount", 0);
			$personal_tax_amount = (float) $core->get_field($commission_information, "personal_tax_amount", 0);
			$advance_paid_amount = (float) $core->get_field($commission_information, "advance_paid_amount", 0);
			if($head_of_team_id == $profile_id){
				$leader_commission_rate = (float) $core->get_field($commission_information, 'leader_commission_rate', 0);
				$leader_commission_amount = (float) $core->get_field($commission_information, 'leader_commission_amount', 0);
				$leader_total_amount = (float) $core->get_field($commission_information, 'leader_total_amount', 0);
				$sales_commission_rate += $leader_commission_rate;
				$sales_commission_amount += $leader_commission_amount;
				$sales_total_amount += $leader_total_amount;
			}
			if($head_of_dep_id == $profile_id){ // Là GĐKD
				$sale_dir_commission_rate = (float) $core->get_field($commission_information, 'sale_dir_commission_rate', 0);
				$sale_dir_commission_amount = (float) $core->get_field($commission_information, 'sale_dir_commission_amount', 0);
				// $sale_dir_commission_amount = ($contract_comm_base * $sale_dir_commission_rate) / 100;
				$sales_commission_rate += $sale_dir_commission_rate;
				$sales_commission_amount += $sale_dir_commission_amount;
				$sales_total_amount += $sale_dir_commission_amount;
			} 
			if($project_director_id == $profile_id){ // Là GĐDA
				$project_dir_commission_rate = (float) $core->get_field($commission_information, "project_dir_commission_rate", 0);
				$project_dir_commission_amount = (float) $core->get_field($commission_information, "project_dir_commission_amount", 0);
				$project_dir_bonus_amount += (float) $core->get_field($commission_information, "project_dir_bonus_amount_out", 0);
				$project_dir_total_amount = (float) $core->get_field($commission_information, "project_dir_total_amount", 0);
				$sales_commission_rate += $project_dir_commission_rate;
				$sales_commission_amount += $project_dir_commission_amount;
				$sales_bonus_amount += $project_dir_bonus_amount;
				$sales_total_amount += $project_dir_total_amount;	
			}	
		} else {
			if($head_of_team_id == $profile_id){ // Là TPKD
				$leader_commission_rate = (float) $core->get_field($commission_information, "leader_commission_rate", 0);
				$leader_commission_amount = (float) $core->get_field($commission_information, "leader_commission_amount", 0);
				$leader_commission_bonus_amount = (float) $core->get_field($commission_information, "leader_commission_bonus_amount", 0);
				$leader_total_amount = (float) $core->get_field($commission_information, "leader_total_amount", 0);
				$sales_commission_rate += $leader_commission_rate;
				$sales_commission_amount += $leader_commission_amount;
				$sales_bonus_amount += $leader_commission_bonus_amount;
				$sales_total_amount += $leader_total_amount;
			} 
			if($head_of_dep_id == $profile_id){ // Là GĐKD
				$sale_dir_commission_rate = (float) $core->get_field($more_information, 'sale_dir_commission_rate', 0);
				$sale_dir_commission_amount = (float) $core->get_field($more_information, 'sale_dir_commission_amount', 0); 
				$sales_commission_rate += $sale_dir_commission_rate;
				$sales_commission_amount += $sale_dir_commission_amount;
				$sales_total_amount += $sale_dir_commission_amount;
			} 
			if($project_director_id == $profile_id){// Là GĐDA
				$project_dir_commission_rate = (float) $core->get_field($commission_information, "project_dir_commission_rate", 0);
				$project_dir_commission_amount = (float) $core->get_field($commission_information, "project_dir_commission_amount", 0);
				$project_dir_bonus_amount = (float) $core->get_field($commission_information, "project_dir_bonus_amount_in", 0);
				$project_dir_total_amount = (float) $core->get_field($commission_information, "project_dir_total_amount", 0);
				$sales_commission_rate += $project_dir_commission_rate;
				$sales_commission_amount += $project_dir_commission_amount;
				$sales_bonus_amount += $project_dir_bonus_amount;
				$sales_total_amount += $project_dir_total_amount;
			}
		}
		$remaining_amount = $sales_total_amount - $tax_vat_amount - $personal_tax_amount;
		$net_amount = $remaining_amount - $advance_paid_amount;
	} else {
		$contract_total = (float) $oneBilling['totalgrand'];
		$contract_comm_base = $contract_total * 0.8; // Giá trị tính HHMG
		$sales_commission_rate = $sales_commission_amount = $sales_total_amount = $sales_bonus_amount = 0;
		if($staff_id == $profile_id){ // Mình bán
			$sales_commission_rate = (float) $core->get_field($more_informatiion, "commission", 0);
			$sales_bonus_amount = (float) $core->get_field($more_information, 'sales_bonus', 0);
			if($head_of_team_id == $profile_id){ // Là TPKD
				$leader_commission_rate = (float) $core->get_field($more_information, 'leader_commission_rate', 0);
				$sales_commission_rate += $leader_commission_rate;
			}
			if($head_of_dep_id == $profile_id){ // Là GĐKD
				$sale_dir_commission_rate = (float) $core->get_field($more_information, 'sale_dir_commission_rate', 0);
				$sales_commission_rate += $sale_dir_commission_rate;
			} 
			if($project_director_id == $profile_id){ // Là GĐDA
				$project_dir_commission_rate = (float) $core->get_field($more_information, 'project_dir_commission_rate', 0);
				$sales_commission_rate += $project_dir_commission_rate;
			}
			$sales_commission_amount = Helper::cal($contract_comm_base, $sales_commission_rate);
			$sales_total_amount = ($sales_commission_amount + $sales_bonus_amount);
		} else {
			$sales_commission_rate = $sales_commission_amount = $sales_bonus_amount = $sales_total_amount = 0;
			if($head_of_team_id == $profile_id){ // Là TPKD
				$leader_commission_rate = (float) $core->get_field($more_information, 'leader_commission_rate', 0);
				$sales_commission_rate += $leader_commission_rate;
			}
			if($head_of_dep_id == $profile_id){ // Là GĐKD
				$sale_dir_commission_rate = (float) $core->get_field($more_information, 'sale_dir_commission_rate', 0);
				$sales_commission_rate += $sale_dir_commission_rate;
			}
			if($project_director_id == $profile_id){ // Là GĐDA
				$project_dir_commission_rate = (float) $core->get_field($more_information, 'project_dir_commission_rate', 0);
				$sales_commission_rate += $project_dir_commission_rate;
			}
			$sales_commission_amount = Helper::cal($contract_comm_base, $sales_commission_rate);	
			$sales_total_amount = ($sales_commission_amount + $sales_bonus_amount);
		}
		$remaining_amount = $sales_total_amount;
		$net_amount = $sales_total_amount; 
	}
	$htmlTable.= '<tr>
		<td class="text-right">Giá trị tính HHMG</td>
		<td class="algin-center">
			'.$clsISO->formatPrice($contract_comm_base).' '.$clsISO->getRate().'
		</td>
	</tr>
	<tr>
		<td class="text-right">Tỉ lệ</td>
		<td class="algin-center">'.$sales_commission_rate.'%</td>
	</tr>
	<tr>
		<td class="text-right">Thành tiền</td>
		<td class="algin-center">
			'.$clsISO->formatPrice($sales_commission_amount).' '.$clsISO->getRate().'
		</td>
	</tr>
	<tr>
		<td class="text-right">Thưởng sales</td>
		<td class="algin-center">
			'.$clsISO->formatPrice($sales_bonus_amount_out).' '.$clsISO->getRate().'
		</td>
	</tr>
	<tr>
		<td class="text-right">Tổng cộng</td>
		<td class="algin-center">
			'.$clsISO->formatPrice($sales_total_amount).' '.$clsISO->getRate().'
		</td>
	</tr>
	<tr>
		<td class="text-right">Giảm trừ</td>
		<td class="algin-center">
			'.$clsISO->formatPrice($tax_vat_amount).' '.$clsISO->getRate().'
		</td>
	</tr>
	<tr>
		<td class="text-right">Thuế TNCN</td>
		<td class="algin-center">
			'.$clsISO->formatPrice($personal_tax_amount).' '.$clsISO->getRate().'
		</td>
	</tr>
	<tr>
		<td class="text-right">Thành tiền(sau thuế)</td>
		<td class="algin-center">
			'.$clsISO->formatPrice($remaining_amount).' '.$clsISO->getRate().'
		</td>
	</tr>
	<tr>
		<td class="text-right">Đã tạm ứng</td>
		<td class="algin-center">
			'.$clsISO->formatPrice($advance_paid_amount).' '.$clsISO->getRate().'
		</td>
	</tr>
		<tr>
		<td class="text-right">Thực nhận</td>
		<td class="algin-center">
			'.$clsISO->formatPrice($net_amount).' '.$clsISO->getRate().'
		</td>
	</tr>';
	$smarty->assign('htmlTable', $htmlTable);
	$smarty->assign('oneBilling', $oneBilling);
	// Return
	$html = $core->build('_ajax.billing.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_mileston(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	$assign_list["upd_version"] = time();
	$clsProfile = new Profile(); 
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsProject = new Project();
	$clsStock = new Stock();
	$assign_list['clsProfile'] = $clsProfile;
	$assign_list['clsBilling'] = $clsBilling;
	$assign_list['clsProperty'] = $clsProperty;
	$assign_list['clsProject'] = $clsProject;
	$assign_list['clsStock'] = $clsStock;
	#
	$arr_style = [
		"background-image"	=>	"url('".URL_IMAGES."/bg_mileston.png')",
		"background-size"	=>	"cover",
		"background-position"	=>	"bottom center",
		"background-attachment"	=>	"fixed"
	];
	$bg_style = "";
	foreach ($arr_style as $key => $val) {
		$bg_style .= (!empty($bg_style) ? ";" : "") . ($key.":".$val);
	}
	$assign_list['bg_style'] = $bg_style;
	#
	$year = Input::get("year",date("Y")); $assign_list['year'] = $year;
	
    /*=============Title & Description Page==================*/
	$title_page = "Hành trình vinh danh";
	$assign_list["title_page"] = $title_page;
	$description_page = $clsConfiguration->getValue('meta_description');
	$assign_list["description_page"] = $description_page;
	$keyword_page = $clsConfiguration->getValue('meta_keyword');
	$assign_list["keyword_page"] = $keyword_page;
}
function default_load_mileston(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsConfiguration,$clsISO,$oneProfile,$profile_id;
	$clsStock = new Stock();
	$clsProfile = new Profile(); 
	$clsBilling = new Billing();
	$clsProperty = new Property();
	$clsProject = new Project();
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('clsBilling', $clsBilling);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsProject', $clsProject);
	$smarty->assign('clsStock', $clsStock);	
	$assign_list["upd_version"] = time();
	#
	$year = Input::post("year",date("Y")); 
	$assign_list['year'] = $year;
	$cond = "`is_trash`=0 AND `is_cancel`='0'";
	$cond.= " AND FROM_UNIXTIME(`deposit_date`,'%Y')='{$year}'";
	#
	$current_page = Input::post('page',1);;
	$per_page = Input::post('per_page',15);
	$lstTotal = $clsBilling->getAll($cond." GROUP BY `staff_id`","staff_id,SUM(`totalgrand`) as total, COUNT(`{$clsBilling->pkey}`) AS `total_bill`");
	$total_sale = $total_bill = 0;
	$total_sale = count($lstTotal);
	foreach ($lstTotal as $key => $val) {
		$total_record += (int)$val["total_bill"];
		$total_bill += (int)$val["total"];
	}
	//	$total_record = $clsBilling->getAll($cond);
	$total_page = @ceil($total_record/$per_page);
	$offset = ($current_page-1)*$per_page;
	$limitCond = " LIMIT {$offset},{$per_page}";
	#
	$lstBilling = $clsBilling->getAll($cond. " ORDER BY `deposit_date` DESC ".$limitCond,"`{$clsBilling->pkey}`,`more_information`,`billing_code`,`staff_id`,`deposit_date`,`project_id`,`totalgrand`");
	$arr_department = $clsProperty->getArraySearchByKey("_DEPARTMENT");
	$arr_role = $clsProperty->getArraySearchByKey("_ROLE");
	$arr_block = $clsProperty->getArraySearchByKey("_BLOCK");
	$arr_bedroom = $clsProperty->getArraySearchByKey("_BEDROOM");
	$arr_type_villa = $clsProperty->getArraySearchByKey("_TYPE_VILLA");
	$arr_cache_profile = $clsProfile->getProfileCached();
	// $clsISO->print_pre($arr_cache_profile); die();
	$arr_cache_project = $clsProject->getListProject();
	$bill_number = $total_record - ((count($lstBilling) * ($current_page - 1)));
	foreach ($lstBilling as $key => $val) {
		$more_information = $clsISO->to_array_json($val["more_information"]);
		if((empty($more_information["bedroom_id"]) && empty($more_information["type_id"])) || empty($more_information["stock_type"])){	
			$oneStock = $clsStock->getOne($more_information["stock_id"],"bedroom_id,stock_type,type_id");
			$more_information["bedroom_id"] = $oneStock["bedroom_id"];
			$more_information["stock_type"] = $oneStock["stock_type"];
			$more_information["type_id"] = $oneStock["type_id"];
			$clsBilling->updateOne($val[$clsBilling->pkey],["more_information" => json_encode($more_information)]);
		}
		$block_id = !empty($more_information["block_id"]) ? $more_information["block_id"] : 0;
		$project_id = !empty($val["project_id"]) ? $val["project_id"] : 0;
		if(!empty($block_id)) {
			$oneBlock = $arr_block[$block_id];
			$more_block = $oneBlock["more_information"];
			if(!empty($more_block["is_project"])) {
				$lstBilling[$key]["project_name"] = $oneBlock["title"];
			}else{
				$lstBilling[$key]["project_name"] = $arr_cache_project[$project_id]["title"] ;
			}
		}else{
			$lstBilling[$key]["project_name"] = $arr_cache_project[$project_id]["title"] ;
		}
//		$clsISO->print_pre($lstBilling[$key]);die;
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
		
		$lstBilling[$key]["image_poster"] = !empty($more_information["image_poster"]) ? $clsISO->getGoogleUrl($more_information["image_poster"]) : "";
		$lstBilling[$key]["more_information"] = $more_information;
		$lstBilling[$key]["oneStaff"] = $oneStaff;
		$lstBilling[$key]["total_price"] = $clsISO->shortNumber($val["totalgrand"],1);
		$lstBilling[$key]["bill_number"] = $bill_number;
		--$bill_number;
	}
    $smarty->assign('current_page', $current_page);
    $smarty->assign('lstBilling', $lstBilling);
	// Return
	$html = $core->build('_ajax.mileston.tpl');
	echo json_encode(array(
		'html' => $html,
		'cond' => $cond,
		'per_page' => $per_page,
		'total_page' => $total_page,
		'current_page' => $current_page,
		'total_record' => $total_record,
		'total_sale' => $total_sale,
		'total_bill' => $clsISO->shortNumber($total_bill),
	)); die();
}