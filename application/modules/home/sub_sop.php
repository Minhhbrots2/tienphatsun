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
function sop_load_quantity_statistics(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$deviceType;
	$clsSop = new Sop();
	$clsProperty = new Property();
	$cond = "`is_trash`='0'";
	#- Tổng căn
	$total_sop = $clsSop->countItem($cond);
	#- Tổng căn đã duyệt
	$total_approved = $clsSop->countItem($cond." AND `is_online`='1'");
	#- Tổng căn chưa duyệt
	$total_not_approved = $clsSop->countItem($cond." AND `is_online`='0'");
	#- Tổng căn hôm nay
	$total_today = $clsSop->countItem($cond." AND FROM_UNIXTIME(`reg_date`,'%d/%m/%Y') = '".date("d/m/Y")."'");
	#- Tổng căn hôm qua
	$total_yesterday = $clsSop->countItem($cond." AND FROM_UNIXTIME(`reg_date`,'%d/%m/%Y') = '".date("d/m/Y",strtotime("-1 day"))."'");
	$html = '<div class="brief-item bg-orange a1a p-3 clickable">
			<p class="fs-16 mb-3">Tổng số</p>
			<h3 class="'.(($deviceType == "phone")?'text-fs-16':'text-fs-20').' mb-0 text-white">'.$total_sop.'dâd căn</h3>
		</div>
		<div class="brief-item p-3 bg-azure a2a">
			<p class="fs-16 mb-3">Đã duyệt</p>
			<h3 class="'.(($deviceType == "phone")?'text-fs-16':'text-fs-20').' mb-0 text-white">'.$total_approved.' căn</h3>
		</div>
		<div class="brief-item p-3 bg-warning a3a">
			<p class="fs-16 mb-3">Chờ duyệt</p>
			<h3 class="'.(($deviceType == "phone")?'text-fs-16':'text-fs-20').' mb-0 text-white">'.$total_not_approved.' căn</h3>
		</div>
		<div class="brief-item p-3 bg-cyan a4a">
			<p class="fs-16 mb-3">Hôm qua</p>
			<h3 class="'.(($deviceType == "phone")?'text-fs-16':'text-fs-20').' mb-0 text-white">'.$total_yesterday.' căn</h3>
		</div>
		<div class="brief-item p-3 bg-green a6a">
			<p class="fs-16 mb-3">Hôm nay</p>
			<h3 class="'.(($deviceType == "phone")?'text-fs-16':'text-fs-20').' mb-0 text-white">'.$total_today.' căn</h3>
		</div>';
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function sop_load_sop_chart(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$deviceType;
	$clsSop = new Sop();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$moth = Input::post('month');
	$year = Input::post('year');
	###
	$html_points = "";
	$data = $dataPoints = $barChartData = $colorSet = array();
	$list_sop_type = $clsProperty->getCacheItems('_SOPTYPE');
	// $list_sop_source = $clsProperty->getCacheItems('_SOURCE');
	if(!empty($list_sop_type)){
		foreach($list_sop_type as $key => $val){
			$colorSet[] = $val['bgcolor'];
			$total_sops = $clsSop->countItem("`sop_type`='{$val[$clsProperty->pkey]}'");
			$dataPoints[] = array(
				'label' => $val['title'],
				'y' => $total_sops * 1,
				'indexLabel' => sprintf('%s GD', $total_sops)
			);
			$html_points.= '<li class="d-flex align-items-center w-100 py-1  gap-2">
				<span class="d-inline-block w-px-15 h-px-15 rounded-pill" style="background:'.$val['bgcolor'].'"></span> 
				<span>'.$val['title'].': <strong>'.$total_sops.'</strong></span>
			</li>';
		}
	}
	$barChartData['animationEnabled'] = true;
	$barChartData['colorSet'] = $colorSet;
	$data['type'] = 'pie';
	$data['toolTipContent']='{label}: <strong>{y} GD</strong>';
	$data['title']['fontSize'] = '14';
	$data['title']['fontColor'] = 'rgb(159,34,58)';
	// $data['indexLabelPlacement'] = 'inside';
	$data['indexLabelFontColor'] = '#36454F';
	$data['dataPoints'] = $dataPoints;
	$barChartData['data'] = $data;
	$html = '<div class="form-row">
		<div class="col-8">
			<div id="'.$uid.'" class="chartContainer w-100" style="height:'.($deviceType=='phone'?'300':'350').'px"></div>
		</div>
		<div class="col-4">
			<ul class="list-unstyled" style="padding-top:5rem;">
				'.$html_points.'
			</ul>
		</div>
	</div>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html,
		'drawchart' => '1',
		'barChartData' => $barChartData
	)); die();
}
function sop_list_sop(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO,$profile_id,$deviceType;
	$clsSop = new Sop();
	$clsStock = new Stock();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	### 
	$month = (int)Input::post('month',0);
	$year = Input::post("year", date('Y'));
	$utm_source = Input::post('utm_source', "_me");
	###
	$cond = "`is_trash`=0";
	if($utm_source == '_me'){
		$cond.= " and `user_id`='"._PROFILE_SOP_ADMIN_ID."'";
	} else if($utm_source == '_market'){
		$cond.= " and `user_id`<>'"._PROFILE_SOP_ADMIN_ID."'";
	}
	if(!empty($month)){
		$month = $clsISO->parseNumber($month);
		$cond.= " AND FROM_UNIXTIME(`reg_date`,'%m/%Y')='".sprintf('%s/%s', $month, $year)."'";
	}else{
		$cond.= " AND FROM_UNIXTIME(`reg_date`,'%Y')='".sprintf('%s',$year)."'";
	}
	$limitCond = " limit 10";
	###
	$sop_field = "{$clsSop->pkey},`title`,`stock_code`,`price`,`more_information`,`stock_id`,`bedroom_id`
	,`home_direction_id`,`is_verified`,`floor`,`project_id`,`block_id`,`building_id`,`code`,`is_locked`,
	`is_online`,`is_solded`,`need_id`,`upd_date`,`reg_date`";
	###
	$order_by = " order by `upd_date` DESC";
	$list_sop = $clsSop->getAll($cond.$order_by.$limitCond,$sop_field);
	$lstBlock = $clsProperty->getArraySearchByKey("_BLOCK");
	$lstBuilding = $clsProperty->getArraySearchByKey("_BUILDING");
	$lstRange = $clsProperty->getArraySearchByKey("_RANGE");
	$lstBedroom = $clsProperty->getArraySearchByKey("_BEDROOM");
	$lstHomeDirection = $clsProperty->getArraySearchByKey("_DIRECTION");
	if(!empty($list_sop)){
		$arr_status_cached = $arr_property_cached = array();
		foreach($list_sop as $key => $val){
			$need_id = $val['need_id'];
			$more_information = $val['more_information'];
			$more_information = $clsISO->to_array_json($more_information);
			$list_sop[$key]['more_information'] = $more_information;
			$block_idd = $val['block_id'];
			$building_id = $val['building_id'];
			$bedroom_id = $val['bedroom_id'];
			$home_direction_id = $val['home_direction_id'];		
			###
			$list_sop[$key]['label_owner'] = "";
			if(isset($more_information['is_owner']) && $more_information['is_owner'] == 1) {
				$list_sop[$key]['label_owner']= '<span class="awe__sop-badge text-white bg-success">Chính chủ</span>';
			}
			if($need_id > 0 && !isset($arr_status_cached[$need_id])){
				$oProperty = $clsProperty->getOne($need_id, "title,bgcolor,textcolor");
				$arr_status_cached[$need_id] = sprintf(
					'<span class="badge" style="background:%s; color:%s">%s</span>', 
					$oProperty['bgcolor'], $oProperty['textcolor'], $oProperty['title']
				);
			}
			$list_sop[$key]['label_status'] = "";
			if($need_id > 0 && isset($arr_status_cached[$need_id])){
				$list_sop[$key]['label_status'] = $arr_status_cached[$need_id];
			}
			$images = isset($more_information['images']) ? $more_information['images'] : array();
			if($deviceType == 'phone'){
				if(!empty($images)){
					$has_img = 1;
					$image = reset($images);
				} else {
					$has_img = 0;
					$image = URL_IMAGES . '/no-image.jpg';
				}
				$list_sop[$key]['image'] = $image;
				$list_sop[$key]['has_img'] = $has_img;
			} else {
				$list_sop[$key]['images'] = $images;
			}			
			###
			$arr_property_cached[$block_idd] = $lstBlock[$block_idd]['title'];
			$sop_type = $more_information['sop_type'];
			if($sop_type == _SOP_TYPE_HIGHLEVEL) {			
				$arr_property_cached[$building_id] = $lstBuilding[$building_id]['title'];
			}else{				
				$arr_property_cached[$building_id] = $lstRange[$building_id]['title'];
			}
			$arr_property_cached[$bedroom_id] = $lstBedroom[$bedroom_id]['title'];
			$arr_property_cached[$home_direction_id] = $lstHomeDirection[$home_direction_id]['title'];
			$list_sop[$key]['block_name'] = $arr_property_cached[$block_idd];
			$list_sop[$key]['building_name'] = $arr_property_cached[$building_id];
			$list_sop[$key]['bedroom'] = $arr_property_cached[$bedroom_id];
			$list_sop[$key]['home_direction'] = $arr_property_cached[$home_direction_id];
			$list_sop[$key]['having_dq'] = !(empty($more_information['having_dq']))?$more_information['having_dq']:0;
			#location
			$location = [];
			$stock_code = $clsSop->getCode($list_sop[$key]);
			if(!empty($stock_code)){
				$location[] = $stock_code;
			}
			if(!empty($arr_property_cached[$building_id])) {
				$location[] = $arr_property_cached[$building_id];
			}
			if(!empty($arr_property_cached[$block_idd])) {
				$location[] = $arr_property_cached[$block_idd];
			}
			$list_sop[$key]['location'] = implode(", ",$location);
		}
	}
	//	$clsISO->print_pre($list_sop);die;
	$smarty->assign('clsSop', $clsSop);
	$smarty->assign('clsProfile', $clsProfile);
	$smarty->assign('list_sop', $list_sop);
	// Return
	$html = $core->build('sop'.DS.'_ajax.sop.tpl');
	echo json_encode(array(
		'url' => $url,
		'html' => $html,
		'cond' => $cond,
	), JSON_UNESCAPED_UNICODE); die();
}
function sop_open_chat(){
	
}