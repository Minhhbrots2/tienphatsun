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
class Project extends dbBasic{
	var $is_html = true;
	function __construct(){
		global $_LANG_ID;
		$this->pkey = "project_id";
		$this->tbl = DB_PREFIX."project";
	}
	function getCode($project_id){
		return $this->getOneField('code', $project_id);
	}
	function getTitle($project_id){
		return $this->getOneField('title', $project_id);
	}
	function isLowFloor($project_id, $list_block_type){
		global $core, $dbconn, $clsISO;
		if(!empty($list_block_type)){
			return in_array(_BLOCK_TYPE_LOWFLOOR_SALE, $list_block_type);
		} else {
			$oneProject = $this->getOne($project_id, "list_block_type");
			$list_block_type = $oneProject['list_block_type'];
			$list_block_type = $clsISO->getArrayByTextSlash($list_block_type);
			return in_array(_BLOCK_TYPE_LOWFLOOR_SALE, $list_block_type);
		}
	}
	function getLink($project_id, $building_id=0, $block_id=0){
		global $core, $dbconn, $clsISO;
		if($building_id==0){
			if($block_id == 0) {
				$link = sprintf('/project/p%s.html', $project_id);	
			}else{
				$link = sprintf('/project/p%s/block%s.html', $project_id,$block_id);
			}			
		} else {
			$link = sprintf('/project/p%s/b%s.html', $project_id,$building_id);
		}
		return $link;
	}
	function getLinkInfo($project_id, $block_id, $building_id, $cat_id, $cat_name=""){
		global $core, $dbconn, $clsISO;
		$this->is_html = false;
		$link = $this->getLinkDetail($project_id, $block_id, $building_id);
		$this->is_html = true;
		if(empty($cat_name)){
			$clsProperty = new Property();
			$cat_name = $clsProperty->getOneField('title', $cat_id);
		}
		if($cat_id == '_utility'){
			return $link . 'tien-ich.html';
		} elseif ($cat_id == '_map'){
			return $link . 'map.html';
		} else {
			return $link . $core->replaceSpace($cat_name).'-c'.$cat_id.'.html';
		}
	}
	function getLinkDetail($project_id,$block_id=0,$building_id=0,$oneProject=array()){
		global $core, $dbconn, $clsISO,$profile_id;
		$clsProperty = new Property();
		$str_page = "thong-tin";
		if($profile_id == 289) {
			$str_page = "thong-tin";
		}
		if($block_id == 0) {
			if(!isset($oneProject['slug'])) {
				$oneProject = $this->getOne($project_id, "slug");
			}
			$link = sprintf('/%s/p%s',$str_page,$project_id).($this->is_html ? '.html' : '/');		
		}else {
			if($building_id==0){		
				$oneBlock = $clsProperty->getOne($block_id,"slug");
				$link = sprintf('/%s/p%s/b%s',$str_page,$project_id,$block_id).($this->is_html ? '.html' : '/');
			} else {
				$oneBuilding = $clsProperty->getOne($building_id,"slug");
				$link = sprintf('/%s/p%s/b%s/bu%s',$str_page,$project_id,$block_id,$building_id).($this->is_html ? '.html' : '/');
			}
		}
		return $link;
	}
	function getLinkPr($project_id){
		return sprintf('/project/pt%s.html', $project_id);
	}
	function getLinkBl($project_id, $block_id){
		return sprintf('/project/pt%s/bl%s.html', $project_id, $block_id);
	}
	function getLinkBu($project_id, $block_id, $building_id){
		return sprintf('/project/pt%s/bl%s/bu%s.html', $project_id, $block_id, $building_id);
	}
	function getSelectOptions($project_id){
		$html = '';
		$field  = "{$this->pkey},`title`";
		$tmp = $this->getAll("`is_trash`=0 order by `reg_date` ASC", $field);
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$html.= '<option'.($project_id==$val[$this->pkey]?' selected':'').' value="'.$val[$this->pkey].'">'.$val['title'].'</option>';
			}
		}
		return $html;
	}	
	function getFieldInCol($code, $stock_templates, $field){
		global $core, $dbconn, $clsISO;
		if($field=='DT_Tim' || $field=='DT_TT'){
			if(isset($stock_templates[$code][$field]) && !empty($stock_templates[$code][$field]))
				return $stock_templates[$code][$field];
			return '0';
		} else {
			if(isset($stock_templates[$code][$field]) && !empty($stock_templates[$code][$field]))
				return $stock_templates[$code][$field];
			return 0;
		}
	}
	function getFieldInStock($code,$stock_templates,$field,$shorten=false){
		global $core, $dbconn, $clsISO;
		$clsProperty = new Property();
		if($field=='DT_Tim' || $field=='DT_TT'){
			if(isset($stock_templates[$code][$field]) && !empty($stock_templates[$code][$field]))
				return $stock_templates[$code][$field];
			return '0';
		} else {
			if(isset($stock_templates[$code][$field]) && !empty($stock_templates[$code][$field])){
				if($shorten)
					return $clsProperty->getOneField('property_code', $stock_templates[$code][$field]);
				return $clsProperty->getTitle($stock_templates[$code][$field]);
			}	
			return '-';
		}
	}
	function getFieldValue($floor, $code, $stocks, $field){
		global $core, $dbconn, $clsISO;
		if($field=='total_price' || $field=='total_price_vat'){
			$more_information = $stocks[$floor][$code]['more_information'];
			$more_information = !empty($more_information) 
				? json_decode(html_entity_decode($more_information), true) : array();
			$price = $core->is_empty($more_information, $field) ? $more_information[$field] : 0;
			if(empty($price)) $price = $more_information['total_price_early'];
			if(empty($price)) $price = $more_information['total_price_progress'];
			if(strtolower($price) != 'check'){
				$price = $clsISO->processSmartNumber($price);
				return number_format((float) $clsISO->priceFormat($price),3,'.','');
				//return $clsISO->_roundFix($clsISO->priceFormat($more_information[$field]),3);
			} else {
				return "";
			}
		} else {
			if(isset($stocks[$floor][$code][$field]))
				return $stocks[$floor][$code][$field];
			return 0;
		}
	}
	function getPriceFieldValue($floor, $code, $stocks, $field){
		global $core, $dbconn, $clsISO;
		$more_information = $stocks[$floor][$code]['more_information'];
		$more_information = !empty($more_information) 
			? json_decode(html_entity_decode($more_information), true) : array();
		if($field=='price_m2' || $field=='price_tts_m2' || $field=='price_tttd_m2' || $field=='price_vay_m2'){
			if(!empty($stocks[$floor][$code]["DT_TT"]) && $clsISO->convertToNumber($stocks[$floor][$code]["DT_TT"]) > 0) {
				if($field == 'price_tts_m2') {
					$total_price = $core->get_field($more_information, "total_price_early", "");
				}elseif($field == 'price_tttd_m2') {
					$total_price = $core->get_field($more_information, "total_price_progress", "");
				}elseif($field == 'price_vay_m2') {
					$total_price = $core->get_field($more_information, "total_price_bank", "");
				}else{
					$total_price = $core->get_field($more_information, "total_price_vat", "");
				}
				if(!empty($total_price)){
					$price = $clsISO->processSmartNumber($total_price) / $clsISO->convertToNumber($stocks[$floor][$code]["DT_TT"]);
					$price = $clsISO->shortNumberV2($price);
					return str_replace("triệu","",$price);
				}
			}	
		}else{			
			$price = $core->is_empty($more_information, $field) ? $more_information[$field] : 0;
			if(strtolower($price) != 'check' && !empty($price)){
				$price = $clsISO->processSmartNumber($price);
				return number_format((float) $clsISO->priceFormat($price),3,'.','');
			}
		}
		return "check";
	}
	// getProjectCached
	function getListProject(){
		global $core, $dbconn, $clsISO;
		$arr_project = [];
		$clsCache = new Cache();
		if($clsCache->has('_list_project_cached')){
			$list_projects = $clsCache->get('_list_project_cached');
		} else {
			$list_projects = $this->getAll("`is_trash`='0'");
			if(!empty($list_projects)){
				foreach($list_projects as $key => $val){
					$utilities = $val['utilities'];
					$banner_stock = $val['banner_stock'];
					$more_information = $val['more_information'];
					// Convert to array
					$utilities = $clsISO->to_array_json($utilities);
					$banner_stock = $clsISO->to_array_json($banner_stock);
					$more_information = $clsISO->to_array_json($more_information);
					$list_projects[$key]['utilities'] = $utilities;
					$list_projects[$key]['banner_stock'] = $banner_stock;
					$list_projects[$key]['more_information'] = $more_information;
				}
			}
			$clsCache->put('_list_project_cached', $list_projects, 60*60);
		}
		if(!empty($list_projects)){
			foreach ($list_projects as $key => $val) {
				$arr_project[$val[$this->pkey]] = $val;
			}
		}
		return $arr_project;
	}
	function getHtmlContactZaloProject($block_id,$oneBlock = null,$type="popup"){
		global $clsISO,$profile_id,$deviceType;
		$clsProfile = new Profile();
		$clsProperty = new Property();
		if(empty($oneBlock["more_information"])) {
			$oneBlock = $clsProperty->getOne($block_id,"more_information");
		}
		$block_information = $clsISO->to_array_json($oneBlock["more_information"]);
		$lst_profile_cached = $clsProfile->getProfileCached("active");
		$project_manager = !empty($block_information["project_manager"]) ? $block_information["project_manager"] : 0;
		$project_admins = !empty($block_information["project_admins"]) ? $block_information["project_admins"] : [];
		$html_zalo = "";
		if($type == "modal" && $deviceType != "phone") {
			$clswidth = " w-30 ";
		}else{
			$clswidth = " w-100 ";
		}
		if(!empty($project_manager) || !empty($project_admins)) {
			$html_zalo = '<div class="d-flex flex-wrap gap-1 p-2 border my-1 rounded-1" style="background: #fffaf3" ><h5 class="w-100 mb-1 fs-14 text-upper fw-bold text-main">Liên hệ</h5>';
			if(!empty($project_manager)) {
				$_oPM = !empty($lst_profile_cached[$project_manager]) ? $lst_profile_cached[$project_manager] : [];
				if(!empty($_oPM["phone"])) {
					$toId = $clsISO->getUniqid();
					$html_zalo .= '<div class="card '.$clswidth.' flex-fill">
						<div class="card-body px-2 py-1 d-flex gap-1 align-items-center">
							<img class="avatar rounded-pill w-px-30 h-px-30" src="'.$clsProfile->getAvatar($project_manager,$_oPM,30,30).'" width="30" height="30" />
							<div class="d-flex justify-content-between flex-wrap flex-fill align-items-center">
								<div class="fs-10">
									<span class="fw-bold fs-12 lh-1">'.$_oPM["full_name"].'</span>
									<div class="d-flex align-items-center lh-1 gap-1">
										<span class="text-main" >GĐDA:</span>
										<span class="phone_contact lh-1 fw-semibold" id="'.$toId.'" >**********</span> 
										<span class="fs-12 p-1 btn btn-xs" onClick="$Core.helper.phoneVisibled(this,event)" data-show="'.$_oPM["phone"].'" data-hidden="**********" toId="'.$toId.'" ><i class="fa fa-eye-slash"></i></span>
									</div>
								</div>									
								<div class="">
									<a href="tel:'.$_oPM["phone"].'" target="_blank" class="btn btn-sm text-white" style="background: #5517da">
										<i class="bx bx-phone fs-14"></i>
									</a>
									<a href="https://zalo.me/'.$_oPM["phone"].'" target="_blank" class="btn btn-sm" style="background: #10a3ff;" >
										<img src="'.URL_IMAGES.'/zalo_chat.png" style="width: 14px">
									</a>
								</div>									
							</div>
						</div>						
					</div>';					
				}			
			}
			if(!empty($project_admins)) {				
				$arr_admin = [];
				foreach ($project_admins as $admin_id) {
					$_oAdm = !empty($lst_profile_cached[$admin_id]) ? $lst_profile_cached[$admin_id] : [];
					if($_oAdm["role_id"] == _ROLE_STAFF_ADMIN && !empty($_oAdm["phone"])) {
						$arr_admin[] = $admin_id;
					}
				}
				if(!empty($arr_admin)) {
					foreach ($arr_admin as $admin_id) {
						$_oAdm = !empty($lst_profile_cached[$admin_id]) ? $lst_profile_cached[$admin_id] : [];
						if($_oAdm["role_id"] == _ROLE_STAFF_ADMIN && !empty($_oAdm["phone"])) {
							$toId = $clsISO->getUniqid();
							$html_zalo .= '<div class="card '.$clswidth.' flex-fill">
								<div class="card-body px-2 py-1 d-flex gap-1 align-items-center">
									<img class="avatar rounded-pill w-px-30 h-px-30" src="'.$clsProfile->getAvatar($admin_id,$_oAdm,30,30).'" width="30" height="30" />
									<div class="d-flex justify-content-between flex-wrap flex-fill align-items-center">
										<div class="fs-10">
											<span class="fw-bold fs-12 lh-1">'.$_oAdm["full_name"].'</span>
											<div class="d-flex align-items-center lh-1 gap-1">
												<span class="text-main" >Admin:</span>
												<span class="phone_contact lh-1 fw-semibold" id="'.$toId.'" >**********</span> 
												<span class="fs-12 p-1 btn btn-xs" onClick="$Core.helper.phoneVisibled(this,event)" data-show="'.$_oAdm["phone"].'" data-hidden="**********" toId="'.$toId.'" ><i class="fa fa-eye-slash"></i></span>
											</div>
										</div>									
										<div class="">
											<a href="tel:'.$_oAdm["phone"].'" target="_blank" class="btn btn-sm text-white" style="background: #5517da">
												<i class="bx bx-phone fs-14"></i>
											</a>
											<a href="https://zalo.me/'.$_oAdm["phone"].'" target="_blank" class="btn btn-sm" style="background: #10a3ff;" >
												<img src="'.URL_IMAGES.'/zalo_chat.png" style="width: 14px">
											</a>
										</div>									
									</div>
								</div>						
							</div>';							
						}
					}
				}				
			}
			$html_zalo .='</div>';
		}
		return $html_zalo;
	}
}
?>