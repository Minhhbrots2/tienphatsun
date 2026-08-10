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
function project_load_stock_popover(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$clsConfiguration,$clsISO,$clsProfile;
	$clsStock = new Stock();
	$clsPolicy = new Policy();
	$clsProject = new Project();
	$clsProperty = new Property();
	$assign_list["clsProject"] = $clsProject;
	$assign_list["clsProperty"] = $clsProperty;
	$dbconn->setFetchMode(ADODB_FETCH_ASSOC);
	
	$stock_id = Input::get('stock_id', 0);
	$oneStock = $clsStock->getOne($stock_id);
	$project_id 	= (int) $oneStock['project_id'];
	$block_id 		= (int) $oneStock['block_id'];
	$building_id 	= (int) $oneStock['building_id'];
	$status_id 		= (int) $oneStock['status_id'];
	$more_information = $oneStock['more_information'];
	$more_information = !empty($more_information) 
		? json_decode(html_entity_decode($more_information), true) : array();
	$price_sheets = isset($more_information['price_sheets']) && !empty($more_information['price_sheets']) 
		? $more_information['price_sheets'] : array();
	
	$html_image_price_sheets = "";
	if(!empty($price_sheets)){
		$price_sheets = array_reverse($price_sheets);
		$oneSheet = reset($price_sheets);
		$html_image_price_sheets = '<tr>
			<td>P.Tính giá <span class="label bg-success">IMG</span></td>
			<td><ul class="pl-3 mb-0">';
			foreach($oneSheet['sheets'] as $val){
				$html_image_price_sheets.= '<li><a href="'.$val['image'].'" download>'.$val['title'].'</a></li>';
			}
			$html_image_price_sheets .= '</ul></td>
		</tr>';
	}
	#- PTG
	$html_price_sheets = "";
	if($clsProperty->getOneField('parent_id', $block_id) == _BLOCK_TYPE_LOWFLOOR_SALE){
		$regex = sprintf('%s_%s', $project_id, $block_id);
	} else {
		$regex = sprintf('%s_%s_%s', $project_id, $block_id, $building_id);
	}
	$onePolicy = $clsPolicy->getByCond("`ms_date`<='".time()."' and `scope_slash` like '%|{$regex}|%' 
	order by ms_date DESC limit 0,1");
	if(!empty($onePolicy)){
		$html_price_sheets = '<tr>
			<th>CS Bán Hàng</th>
			<td><a target="_blank" href="'.$onePolicy['link_ns'].'">'.$clsISO->makeIcon('bx-link-external','Mở link').'</a></td>
		</tr>
		<tr>
			<th>P.Tính giá</th>
			<td><a target="_blank" href="'.$onePolicy['link_ms'].'">'.$clsISO->makeIcon('bx-link-external','Mở link').'</a></td>
		</tr>';
	}
	#
	$agency_name = "";
	if($oneStock['agency_id'] > 0){
		$oneProperty = $clsProperty->getOne($oneStock['agency_id'], "property_code,title");
		if($clsISO->checkSupper()){
			$agency_name = sprintf(' - %s', $oneProperty['title']);
		}
	}
	$html_last_updated = "";
	if($oneStock['upd_date'] > 0){
		$html_last_updated .= "<tr>
			<th>Cập nhật L.cuối</th>
			<td class=\"text-primary fs-12\">".$clsISO->convertTimeToText($oneStock['upd_date'], true)."</td>
		</tr>";
	}
	#- Status
	$oneStatus = array('bgcolor'=>'#FFF', 'textcolor' => '#566a7f');
	if($status_id > 0){
		$oneStatus= $clsProperty->getOne($status_id,"title,bgcolor,textcolor");
		$status_name= $clsProperty->getTitle($status_id, $oneStatus);
	} else{
		$status_name = "Đã bán";
		$oneStatus= $clsProperty->getOne(_STOCK_STATUS_SOLD_ID,"bgcolor,textcolor");
	}
	$html = '<div class="table-wrapper">
		<table class="table table-tooltip table-bordered">
			<tr>
				<th width="35%" class="bg-lighter fs-12">Mã căn</th>
				<td>'.$oneStock['ms_code'].' <a onClick="$Core.helper.toggle_wishlist(this,event)" stock_id="'.$stock_id.'" 
					class="btn btn-sm'.($liked?' saved':'').' btn-default p-1">'.$clsISO->makeIcon('bx-heart fs-11').'</a></td>
			</tr>
			<tr>
				<th class="bg-lighter fs-12">DT_TIM(m2)</th>
				<td>'.$more_information['DT_Tim'].'</td>
			</tr>
			<tr>
				<th class="bg-lighter">DT_TT(m2)</th>
				<td>'.$more_information['DT_TT'].'</td>
			</tr>
			<tr>
				<th class="bg-lighter fs-12">Loại căn</th>
				<td>'.$clsProperty->getTitle($oneStock['bedroom_id']).'</td>
			</tr>
			<tr>
				<th class="bg-lighter fs-12">Hướng BC</th>
				<td>'.$clsProperty->getTitle($oneStock['home_direction_id']).'</td>
			</tr>
			<!-- <tr>
				<th class="bg-lighter fs-12">View</th>
				<td>'.$clsProperty->getTitle($oneStock['view_id']).'</td>
			</tr> -->
			<tr>
				<th class="bg-lighter fs-12">Giá FULL VAT</th>
				<td>'.($more_information['total_price_vat']==0
					?'<span class="text-danger">Check Admin</span>'
					:$clsISO->priceFormat($clsISO->processSmartNumber($more_information['total_price_vat'])).'đ').'
				</td>
			</tr>
			<tr>
				<th class="bg-lighter fs-12">Tình trạng</th>
				<td class="fs-12" style="background:'.$oneStatus['bgcolor'].'; color:'.$oneStatus['textcolor'].'">
					'.$status_name.$agency_name.'
				</td>
			</tr>
			'.$html_image_price_sheets.'
			'.$html_price_sheets.'
			'.$html_last_updated.'
		</table>
	</div>';
	// Return
	echo $html; die();
}