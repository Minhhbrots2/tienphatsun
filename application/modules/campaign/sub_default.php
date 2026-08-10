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

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,

	$description_page,$keyword_page,$extLang,$clsISO;

	/*=============Title & Description Page==================*/

	$title_page = 'Chiến dịch - '.PAGE_NAME;

	$assign_list["title_page"] = $title_page;

}

function default_list(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,

	$description_page,$keyword_page,$extLang,$clsISO,$deviceType;

	$clsProfile = new Profile();

	$clsProperty = new Property();

	$clsCampaign = new Campaign();

	

	$html = "";

	$list_campaigns = $clsCampaign->getAll("`is_trash`=0 and `campaign_type`='_global' order by `reg_date` DESC");

	if(!empty($list_campaigns)){ $ii = 1;

		foreach($list_campaigns as $key => $val){

			$campaign_id = $val[$clsCampaign->pkey];

			$html.= '<tr>

				'.($deviceType=='phone'?'':'<td class="algin-center text-center">'.$ii.'</td/>').'

				<td class="algin-center fw-bold text-left">'.$val['title'].'</td/>

				<td class="algin-center text-center"><a href="'.$clsCampaign->getLink($campaign_id).'" target="_blank" class="btn btn-sm btn-icon btn-outline-default"><i class=\'bx bx-line-chart-down\'></i></a></td/>

				<td class="algin-center text-center">'.$clsISO->convertTimeToText($val['start_date']).'</td/>

				<td class="algin-center text-center">'.$clsISO->convertTimeToText($val['end_date']).'</td/>

				<td class="algin-center text-center">'.$clsISO->convertTimeToText($val['reg_date'], true).'</td/>

				<td class="algin-center text-center"><div class="dropdown">

					<a class="btn btn-sm btn-icon btn-outline-default" onClick="$Core.campaign.open(this, event)" campaign_id="'.$campaign_id.'" href="javascript:void(0);"><i class="bx bx-pencil"></i></a>

					<a class="btn btn-sm btn-icon btn-outline-default" onClick="$Core.campaign.delete(this,event)" campaign_id="'.$campaign_id.'" href="javascript:void(0);"><i class="bx bx-trash"></i></a>

				</div></td/>

			</tr>';

			++$ii;

		}

	}

	// Return

	echo json_encode(array(

		'html' => $html

	)); die();

}

function default_view_campaign(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,

	$description_page,$keyword_page,$extLang,$clsISO;

	$clsProfile = new Profile();

	$clsProperty = new Property();

	$clsCampaign = new Campaign();

	$clsBilling = new Billing();

	#

	$uid = $clsISO->getUniqid();

	$campaign_id = Input::post('campaign_id', 0);

	#

	$oneCampaign = $clsCampaign->getOne($campaign_id);

	$start_date = $oneCampaign['start_date'];

	$end_date = $oneCampaign['end_date'];

	$campaign_info = $oneCampaign['campaign_info'];

	$campaign_info = !empty($campaign_info) 

		? json_decode(html_entity_decode($campaign_info), true) : array();

	// $clsISO->print_pre($campaign_info); die();

	if(!empty($campaign_info)){

		if($oneCampaign['is_terms']==1){

			$campaign_terms = $oneCampaign['campaign_terms'];

			$campaign_terms = !empty($campaign_terms) 

				? json_decode(html_entity_decode($campaign_terms), true) : array();

			$smarty->assign('campaign_terms', $campaign_terms);

			if($oneCampaign['selector'] == 'staff'){

                $cond = "`is_trash`=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."'";

                $cond.= " and {$clsProfile->pkey}<>'"._PROFILE_PARTNER_ID."'";

                if($campaign_info['opt_staff']=='_sale'){

                    $cond.= " and `role_id`<>'"._ROLE_GD_PROJECT."' and `list_department_id` like '%|"._DEPARTMENT_SALE_ID."%'";

                } else if($campaign_info['opt_staff']=='_sale2'){

                    $cond.= " and `list_department_id` like '%|"._DEPARTMENT_SALE_ID."%'";

                } else if($campaign_info['opt_staff']=='_select'){

                    $ids = isset($campaign_info['staff']) && !empty($campaign_info['staff']) 

                        ? $campaign_info['staff'] : array();

                    if(!empty($ids)){

                        $cond.= " and {$clsProfile->pkey} in (".implode(',', $ids).")";

                    }

                }

                $field = "{$clsProfile->pkey},full_name,last_name,first_name,code,department_id,role_id";

                $list_staffs = $clsProfile->getAll($cond, $field);

                if(!empty($list_staffs)){

                    foreach($list_staffs as $key => $val){

                        $profile_id = $val[$clsProfile->pkey];

                        #- Điểm

                        $total_scores = 0;

                        $field = "{$clsBilling->pkey},billing_type";

                        $list_billings = $clsBilling->getAll("`is_trash`=0 and `is_cancel`=0 and `staff_id`='{$profile_id}' and (`deposit_date` between '{$start_date}' AND '{$end_date}')", $field);

                        if(!empty($list_billings)){

                            foreach($list_billings as $okey => $oval){

                                $total_scores += floatval($campaign_terms[$oval['billing_type']]);

                            }

                        }

                        $list_staffs[$key]['full_name'] = $clsProfile->getIndentityV2($profile_id, $val, false);

                        $list_staffs[$key]['total_scores'] = $total_scores;

                    }

                    $total_scores_arrs = @array_column($list_staffs, 'total_scores');

                    @array_multisort($total_scores_arrs, SORT_DESC, $list_staffs);

                }

                $smarty->assign('list_staffs', $list_staffs);

            } else {

                 $list_groups = array();

                 foreach($campaign_info as $key => $val){

                    $group_members = $val['group_members'];

                    #- Điểm

                    $total_scores = 0;

                    $field = "{$clsBilling->pkey},billing_type";

                    $list_billings = $clsBilling->getAll("`is_trash`=0 and `is_cancel`=0 and `staff_id` in (".implode(',',$group_members).") 

                    and (`deposit_date` between '{$start_date}' AND '{$end_date}')", $field);

                    if(!empty($list_billings)){

                        foreach($list_billings as $okey => $oval){

                            $total_scores += floatval($campaign_terms[$oval['billing_type']]);

                        }

                    }

                    $list_groups[] = array(

                        'name' => $val['group_name'],

                        'group_members' => $clsProfile->getNameArray($group_members),

                        'total_scores' => $total_scores

                    );

                }

                $total_scores_arrs = @array_column($list_groups, 'total_scores');

                @array_multisort($total_scores_arrs, SORT_DESC, $list_groups);

                $smarty->assign('list_groups', $list_groups);

            }

		} else {

            $list_groups = array();

			if(!empty($campaign_info)){

				foreach($campaign_info as $key => $val){

					$group_members = $val['group_members'];

					$total_scores = 0;

					$list_groups[] = array(

						'name' => $val['group_name'],

						'group_members' => $clsProfile->getNameArray($group_members),

						'total_scores' => $total_scores

					);

				}

			}

            $smarty->assign('list_groups', $list_groups);

		}

	}

	$smarty->assign('uid', $uid);

	$smarty->assign('campaign_id', $campaign_id);

	$smarty->assign('oneCampaign', $oneCampaign);

	$smarty->assign('clsProperty', $clsProperty);

	// Return

	$html = $core->build('_ajax.view_campaign.tpl');

	echo json_encode(array(

		'uid' => $uid,

		'html' => $html

	)); die();

}

function default_open_campaign(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,

	$description_page,$keyword_page,$extLang,$clsISO;

	$clsProfile = new Profile();

	$clsProperty = new Property();

	$clsCampaign = new Campaign();

	##

	$uid = $clsISO->getUniqid();

	$campaign_id = Input::post('campaign_id', 0);

	$smarty->assign('uid', $uid);

	$smarty->assign('campaign_id', $campaign_id);

	##

	$list_selector = array(

        'staff' => 'Nhân viên',

		'group_sale' => 'Nhóm kinh doanh',

		'group_department' => 'Nhóm phòng ban',

		'group_staff' => 'Nhóm nhân viên'

	);

	$smarty->assign('list_selector', $list_selector);

	##

	$field = "{$clsProperty->pkey},title";

	$list_billing_type = $clsProperty->getAll("is_trash=0 and parent_id='0' 

	and property_type='_BILLING_TYPE' order by reg_date ASC", $field);

	$smarty->assign('list_billing_type', $list_billing_type);

	###

	$campaign_terms = array();

	foreach($list_billing_type as $key => $val){

		$campaign_terms[$val[$clsProperty->pkey]] = 0;

	}

	###

	$action = "_add";

	$oneCampaign = array(

		'is_terms' => 0,

		'selector' => 'group_sale',

		'start_date' => time(), 

		'end_date' => strtotime('+1 days')

	);

	if($campaign_id > 0){

		$action = "_edit";

		$oneCampaign = $clsCampaign->getOne($campaign_id);

		if($oneCampaign['is_terms']==1){

			$campaign_terms = $oneCampaign['campaign_terms'];

			$campaign_terms = !empty($campaign_terms) 

				? json_decode(html_entity_decode($campaign_terms), true) 

				: array();

		}

	}

	$smarty->assign('action', $action);

    $smarty->assign('clsCampaign', $clsCampaign);

	$smarty->assign('oneCampaign', $oneCampaign);

	$smarty->assign('campaign_terms', $campaign_terms);

	// Return

	$html = $core->build('_ajax.campaign.tpl');

	echo json_encode(array(

		'uid' => $uid,

		'html' => $html,

		'campaign_id' => $campaign_id,

		'selector' => $oneCampaign['selector']

	)); die();

}

function default_load_content(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,

	$description_page,$keyword_page,$extLang,$clsISO;

	$clsProperty = new Property();

	$clsProfile = new Profile();

	$clsCampaign = new Campaign();

	###

	$uid = Input::post('uid');

	$selector = Input::post('selector');

	$campaign_id = (int) Input::post('campaign_id', 0);

	if($selector=='staff'){

        $campaign_info = array('opt_staff' => '_all', 'staff' => array());

		if($campaign_id > 0){

			$tmp = $clsCampaign->getOneField('campaign_info', $campaign_id);

			$campaign_info = !empty($tmp) 

				? json_decode(html_entity_decode($tmp), true) : array();

		}

        // $clsISO->print_pre($campaign_info); die();

        $list_staffs_selected = $campaign_info['staff'];        

		$field = "{$clsProfile->pkey},full_name,last_name,first_name,code,department_id,role_id";

		$list_staffs = $clsProfile->getAll("`is_trash`=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."' order by code ASC");	

        ####

		$html.= '<div class="form-check mb-2">

			<input type="radio" name="campaign_info[opt_staff]" class="form-check-input opt_staff" value="_all" uid="'.$uid.'" onChange="$Core.campaign.set_staff_rdo(this,event)"'.($campaign_info['opt_staff']=='_all'?' checked':'').' id="rdo_'.$uid.'_all">

			<label class="form-check-label" for="rdo_'.$uid.'_all">Tất cả nhân viên</label>

		</div>

        <div class="form-check mb-2">

			<input type="radio" name="campaign_info[opt_staff]" class="form-check-input opt_staff" value="_sale" uid="'.$uid.'" onChange="$Core.campaign.set_staff_rdo(this,event)"'.($campaign_info['opt_staff']=='_sale'?' checked':'').' id="rdo_'.$uid.'_sale">

			<label class="form-check-label" for="rdo_'.$uid.'_sale">Nhân viên KD</label>

		</div>

        <div class="form-check mb-2">

			<input type="radio" name="campaign_info[opt_staff]" class="form-check-input opt_staff" value="_sale2" uid="'.$uid.'" onChange="$Core.campaign.set_staff_rdo(this,event)"'.($campaign_info['opt_staff']=='_sale2'?' checked':'').' id="rdo_'.$uid.'_sale2">

			<label class="form-check-label" for="rdo_'.$uid.'_sale2">Nhân viên KD & GĐ dự án</label>

		</div>

		 <div class="form-check mb-2">

			<input type="radio" name="campaign_info[opt_staff]" class="form-check-input opt_staff" value="_sale3" uid="'.$uid.'" onChange="$Core.campaign.set_staff_rdo(this,event)"'.($campaign_info['opt_staff']=='_sale3'?' checked':'').' id="rdo_'.$uid.'_sale3">

			<label class="form-check-label" for="rdo_'.$uid.'_sale3">Nhân viên KD & GĐ dự án & Ban giám đốc</label>

		</div>

		<div class="form-check mb-2">

			<input type="radio" name="campaign_info[opt_staff]"'.($campaign_info['opt_staff']=='_select'?' checked':'').' class="form-check-input opt_staff" 

			id="rdo_'.$uid.'_select" uid="'.$uid.'" onChange="$Core.campaign.set_staff_rdo(this,event)" value="_select">

			<label class="form-check-label" for="rdo_'.$uid.'_select">Chọn nhân viên</label>

		</div>

		<div class="pl-4">

			<select id="rdo_staff_'.$uid.'"'.($campaign_info['opt_staff']=='_select'?'':' disabled').' multiple name="campaign_info[staff][]" 

			data-placeholder="Nhân viên" data-width="100%" class="form-control iso-select2">';

				if(!empty($list_staffs)){

					foreach($list_staffs as $key => $val){

						$html.= '<option'.(in_array($val[$clsProfile->pkey],$list_staffs_selected)?' selected':'').' value="'.$val[$clsProfile->pkey].'">'.$clsProfile->getIndentityV2($val[$clsProfile->pkey], $val, false).'</option>';

					}

				}

			$html.= '</select>

		</div>

		<hr class="clearfix" />

		<a class="btn btn-outline-default" onClick="$Core.campaign.open_target(this,event)" campaign_id="'.$campaign_id.'">Thiết lập KPI</a>';

	} else if($selector=='group_sale'){

         $campaign_info = array( 'opt_department' => '_all', 'department' => array());

		if($campaign_id > 0){

			$tmp = $clsCampaign->getOneField('campaign_info', $campaign_id);

			$campaign_info = !empty($tmp) 

				? json_decode(html_entity_decode($tmp), true) : array();

		}

        $list_departments_selected = $campaign_info['department'];  

		$field = "{$clsProperty->pkey},title";

		$list_departments = $clsProperty->getAll("is_trash=0 and property_type='_DEPARTMENT' and parent_id='"._DEPARTMENT_SALE_ID."' order by order_no ASC", $field);	

		$html.= '<div class="form-check mb-2">

			<input type="radio" name="campaign_info[opt_department]" class="form-check-input opt_department"'.($campaign_info['opt_department']=='_all'?' checked':'').' value="_all" uid="'.$uid.'" onChange="$Core.campaign.set_department_rdo(this,event)" id="rdo_'.$uid.'_all">

			<label class="form-check-label" for="rdo_'.$uid.'_all">Tất cả phòng ban </label>

		</div>

		<div class="form-check mb-2">

			<input type="radio" name="campaign_info[opt_department]"'.($campaign_info['opt_department']=='_select'?' checked':'').' class="form-check-input opt_department" 

			id="rdo_'.$uid.'_select" uid="'.$uid.'" onChange="$Core.campaign.set_department_rdo(this,event)" value="_select">

			<label class="form-check-label" for="rdo_'.$uid.'_select"> Chọn phòng ban </label>

		</div>

		<div class="pl-4">

			<select id="rdo_department_'.$uid.'"'.($campaign_info['opt_department']=='_all'?' disabled':'').' multiple name="campaign_info[department][]" data-placeholder="Phòng ban" data-width="100%" class="form-control iso-select2">';

				if(!empty($list_departments)){

					foreach($list_departments as $key => $val){

						$html.= '<option'.(in_array($val[$clsProperty->pkey],$list_departments_selected)?' selected':'').' value="'.$val[$clsProperty->pkey].'">'.$val['title'].'</option>';

					}

				}

			$html.= '</select>

		</div>';

	} else {

		$campaign_info = array();

		$campaign_config = array('is_target' => 0,'is_complete' => 0);

		if($campaign_id > 0){

            $oneCampaign = $clsCampaign->getOne($campaign_id);

            $campaign_info = $oneCampaign['campaign_info'];

			$campaign_info = !empty($campaign_info) 

				? json_decode(html_entity_decode($campaign_info), true) : array();

			$campaign_config = $oneCampaign['campaign_config'];

			$campaign_config = !empty($campaign_config) 

				? json_decode(html_entity_decode($campaign_config), true) : array();

            if(isset($_POST['is_target'])){

                $is_target = (int) Input::post('is_target', 0);

                $campaign_config['is_target'] = $is_target;

            }

		}

		$html = '<div class="form-row mb-2">

			<div class="col-12 col-md-6">

				<div class="p-3 bg-lighter rounded-2">

					<div class="d-flex align-items-center">

						<label class="switch mr-2">

							<input type="checkbox" onChange="$Core.campaign.sw_target(this, event)" class="chk_target_'.$uid.'" uid="'.$uid.'" campaign_id="'.$campaign_id.'" 

							selector="'.$selector.'" name="campaign_config[is_target]"'.($campaign_config['is_target']==1?' checked':'').' value="1" />

							<span class="slider round"></span>

						</label>

						<span>Thiết lập chỉ tiêu chiến dịch</span>

					</div>

				</div>

			</div>

			<div class="col-12 col-md-6">

				<div class="p-3 bg-lighter rounded-2">

					<div class="d-flex align-items-center">

						<label class="switch mr-2">

							<input type="checkbox" uid="'.$uid.'"'.($campaign_config['is_target']==0?' disabled':'').' class="chk_complete_'.$uid.'" 

							name="campaign_config[is_complete]"'.($campaign_config['is_complete']==1?' checked':'').' value="1" />

							<span class="slider round"></span>

						</label>

						<span>Hiển thị % hoàn thành mục tiêu</span>

					</div>

				</div>

			</div>

		</div>

        <div class="table-wrapper mb-2">

			<table width="100%" class="table table-bordered" style="table-layout:fixed">

				<thead><tr>

					<th width="5%" class="text-center bg-lightest">No.</th>

					<th width="25%" class="algin-center bg-lightest">Tên nhóm</th>

					<th width="'.($campaign_config['is_target']=='1'?'40':'60').'%" class="algin-center bg-lightest">Chọn thành viên</th>

					<th width="20%" class="algin-center bg-lightest">Sản phẩm</th>

					'.($campaign_config['is_target']=='1'?'<td width="15%" class="algin-center bg-lightest">Chỉ tiêu</td>':'').'

					<th width="60px" class="bg-lightest"></th>

				</tr></thead>

				<tbody>';

					if(!empty($campaign_info)){ $ii = 0;

						if($selector=='group_department'){

							$field = "{$clsProperty->pkey},title";

							$list_departments = $clsProperty->getAll("is_trash=0 and property_type='_DEPARTMENT' and parent_id='"._DEPARTMENT_SALE_ID."' order by order_no ASC", $field);

						} else {

							$field = "{$clsProfile->pkey},full_name,last_name,first_name,code,department_id,role_id";

							$list_staffs = $clsProfile->getAll("`is_trash`=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."' order by code ASC");

						}			

						foreach($campaign_info as $gid => $oval){

							$group_members = isset($oval['group_members']) ? $oval['group_members'] : array();

							$group_target  = isset($oval['group_target']) ? $oval['group_target'] : 0;

							$group_product = isset($oval['group_product']) ? $oval['group_product'] : "ALL";

							// $clsISO->print_pre($oval); die();

							$html.= '<tr class="tr_campaign_'.$gid.' tr_campaign_'.$uid.'">

								<td class="text-center">'.($ii+1).'</td>

								<td class="text-left">

									<input type="text" name="campaign_info['.$gid.'][group_name]" placeholder="Tên nhóm" class="form-control" value="'.$oval['group_name'].'" maxlength="255" />

								</td>

								<td class="text-left">';

									if($selector=='group_department'){

										$html.= '<select data-width="100%" name="campaign_info['.$gid.'][group_members][]" multiple data-placeholder="Phòng ban" class="form-control required iso-select2">';

											if(!empty($list_departments)){

												foreach($list_departments as $key => $val){

													$html.= '<option'.(in_array($val[$clsProperty->pkey],$group_members)?' selected':'').' value="'.$val[$clsProperty->pkey].'">'.$val['title'].'</option>';

												}

											}

										$html.= '</select>';

									} else if($selector=='group_staff'){

										$html.= '<select name="campaign_info['.$gid.'][group_members][]" data-width="100%" data-placeholder="Nhân viên" multiple class="form-control slb_group_members_'.$gid.' required iso-select2">';

											if(!empty($list_staffs)){

												foreach($list_staffs as $key => $val){

													$html.= '<option'.(in_array($val[$clsProfile->pkey],$group_members)?' selected':'').' value="'.$val[$clsProfile->pkey].'">'.$clsProfile->getIndentityV2($val[$clsProfile->pkey], $val, false).'</option>';

												}

											}

										$html.= '</select>';

									}

								$html.= '</td>

								<td class="text-center">

									<select class="form-control form-select" name="campaign_info['.$gid.'][group_product]">

										'.$clsCampaign->getOptProduct($group_product).'

									</select>

								</td>

								 '.($campaign_config['is_target']=='1'?'<td class="text-center">

								 	<div class="input-group">

										<input type="text" class="form-control required" value="'.$group_target.'" name="campaign_info['.$gid.'][group_target]" />

										<button type="button" onClick="$Core.campaign.open_target(this, event)" campaign_id="'.$campaign_id.'" uid="'.$gid.'" 

										class="btn btn-outline-default px-0 w-px-40">

											'.$clsISO->makeIcon('bx-cog').'

										</button>

									</div>

								</td>':'').'

								<td class="text-center">

									'.($ii>0?'<button type="button" onclick="$Core.campaign.delete_line(this, event)" uid="'.$gid.'" class="btn p-2 btn-outline-default"><i class="bx bx-trash-alt"></i></button>':'').'

								</td>

							</tr>';

							++$ii;

						}

					} else {

						$html.= '<tr class="tr_campaign_'.$uid.'">

							<td class="text-center">1</td>

							<td class="text-left">

								<input type="text" name="campaign_info['.$uid.'][group_name]" placeholder="Tên nhóm" class="form-control" maxlength="255" />

							</td>

							<td class="text-left">';

								if($selector=='group_department'){

									$field = "{$clsProperty->pkey},title";

									$list_departments = $clsProperty->getAll("is_trash=0 and property_type='_DEPARTMENT' and parent_id='"._DEPARTMENT_SALE_ID."' order by order_no ASC", $field);

									$html.= '<select name="campaign_info['.$uid.'][group_members][]" multiple data-width="100%" data-placeholder="Phòng ban" class="form-control required iso-select2">';

										if(!empty($list_departments)){

											foreach($list_departments as $key => $val){

												$html.= '<option value="'.$val[$clsProperty->pkey].'">'.$val['title'].'</option>';

											}

										}

									$html.= '</select>';

								} else if($selector=='group_staff'){

									$field = "{$clsProfile->pkey},full_name,last_name,first_name,code,department_id,role_id";

									$list_staffs = $clsProfile->getAll("`is_trash`=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."' order by code ASC");

									$html.= '<select name="campaign_info['.$uid.'][group_members][]" data-width="100%" data-placeholder="Nhân viên" multiple class="form-control slb_group_members_'.$uid.' required iso-select2">';

										if(!empty($list_staffs)){

											foreach($list_staffs as $key => $val){

												$html.= '<option value="'.$val[$clsProfile->pkey].'">

													'.$clsProfile->getIndentityV2($val[$clsProfile->pkey], $val, false).'

												</option>';

											}

										}

									$html.= '</select>';

								}

							$html.= '</td>

							<td class="text-center">

								<select class="form-control form-select" name="campaign_info['.$uid.'][group_product]">

									'.$clsCampaign->getOptProduct(0).'

								</select>

							</td>

							'.($campaign_config['is_target']=='1'?'<td class="text-center">

								<input type="text" class="form-control required" name="campaign_info['.$uid.'][group_target]" />

							</td>':'').'

							<td class="text-center"></td>

						</tr>';

					}

				$html.= '</tbody>

			</table>

		</div>

		<div class="d-flex">

			<button type="button" uid="'.$uid.'" selector="'.$selector.'" onClick="$Core.campaign.add_line(this, event)" class="btn btn-outline-default">Thêm nhóm</button>

		</div>';

	}

	// Return

	echo $html; die();

}

function default_open_target(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,

	$description_page,$keyword_page,$extLang,$clsISO;

	$clsProperty = new Property();

	$clsProfile = new Profile();

	$clsCampaign = new Campaign();

	#

	$list_targets = array(

		'billing' => 'Giao dịch',

		'tranning' => 'Học tâp'

	);

	$smarty->assign('list_targets', $list_targets);

	

	$selector = Input::post('selector');

	$campaign_id = Input::post('campaign_id');

	$campaign_target = $clsCampaign->getOneField('campaign_target', $campaign_id);

	$campaign_target = $clsISO->to_array_json($campaign_target); 

	if($selector=='staff'){

		$opt_staff = Input::post('opt_staff');

		$cond = "`is_trash`=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."'";

		$cond.= " and {$clsProfile->pkey}<>'"._PROFILE_PARTNER_ID."'";

		if($opt_staff=='_sale'){

			$cond.= " and `role_id`<>'"._ROLE_GD_PROJECT."' and `list_department_id` like '%|"._DEPARTMENT_SALE_ID."%'";

		} else if($opt_staff=='_sale2'){

			$cond.= " and `list_department_id` like '%|"._DEPARTMENT_SALE_ID."%'";

		} else if($opt_staff=='_sale3'){

			$cond.= " and (`list_department_id` like '%|"._DEPARTMENT_SALE_ID."%' or `department_id`='"._DEPARTMENT_DIRECTOR_ID."')";

		} else if($opt_staff=='_select'){

			$group_members = Input::post('group_members');

			if(!empty($group_members)){

				$cond.= " and {$clsProfile->pkey} in (".implode(',', $group_members).")";

			}

		}

		$field = "{$clsProfile->pkey},code,full_name,first_name,last_name";

		$tmp = $clsProfile->getAll($cond, $field);

		#

		$group_members = array();

		if(!empty($tmp)){

			foreach($tmp as $key => $val){

				$user_id = $val[$clsProfile->pkey];

				if(isset($campaign_target[$user_id])){

					$group_members[$user_id] = $campaign_target[$user_id];

				} else {

					$group_members[$user_id] = array();

				}

			}	

		}

	} else {

		$uid = Input::post('uid');

		$smarty->assign('uid', $uid);

		$group_members = Input::post('group_members');

		if(!empty($group_members) && !empty($campaign_target) 

		   && !empty($uid) && isset($campaign_target[$uid]) ){

			$tmp = $group_members;

			$group_members = array();

			$group_targets = $campaign_target[$uid];

			foreach($tmp as $user_id){

				if(isset($group_targets[$user_id])){

					$group_members[$user_id] = $group_targets[$user_id];

				} else {

					$group_members[$user_id] = 0;

				}

			}

		} else {

			$tmp = $group_members;

			$group_members = array();

			foreach($tmp as $user_id){

				$group_members[$user_id] = 0;

			}

		}

	}

	//$clsISO->print_pre($group_members); die();

	$smarty->assign('selector', $selector);

	$smarty->assign('campaign_id', $campaign_id);

	$smarty->assign('group_members', $group_members);

	// Return

	$uid = $clsISO->getUniqid();

	$html = $core->build('_ajax.target.tpl');

	echo json_encode(array(

		'uid' => $uid,

		'html' => $html

	)); die();

}

function default_save_target(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,

	$description_page,$keyword_page,$extLang,$clsISO;

	#

	$clsProperty = new Property();

	$clsProfile = new Profile();

	$clsCampaign = new Campaign();

	#

	$msg = "_error";

	$selector = Input::post('selector');

	$campaign_id = (int) Input::post('campaign_id', 0);

	$campaign_target = Input::post('campaign_target', array());

	//$clsISO->print_pre($campaign_target); die();

	if($selector=='staff'){

		if($campaign_id > 0 && !empty($campaign_target)){

			if($clsCampaign->updateOne($campaign_id, array(

				'campaign_target' => json_encode($campaign_target, JSON_UNESCAPED_UNICODE)

			))){

				$msg = "_success";

			}

		} else {

			$msg = "_invalid";

		}

	} else {

		$uid = Input::post('uid');

		// $clsISO->print_pre($campaign_target); die();

		if($campaign_id > 0 && !empty($campaign_target)){

			$campaign_target_old = $clsCampaign->getOneField('campaign_target', $campaign_id);

			$campaign_target_old = !empty($campaign_target_old) 

				? json_decode(html_entity_decode($campaign_target_old), true) 

				: array();

			$campaign_target_old[$uid] = $campaign_target;

			//$clsISO->print_pre($campaign_target_old); die();

			if($clsCampaign->updateOne($campaign_id, array(

				'campaign_target' => json_encode($campaign_target_old, JSON_UNESCAPED_UNICODE)

			))){

				$msg = "_success";

			}

		} else {

			$msg = "_invalid";

		}

	}

	// Return

	echo $msg; die();

}

function default_addline(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,

	$description_page,$keyword_page,$extLang,$clsISO;

	// ini_set('display_errors',1);

	// error_reporting(E_ALL);

	$clsProperty = new Property();

	$clsProfile = new Profile();

	$clsCampaign = new Campaign();

	###

	$toId = Input::post('uid');

	$uid = $clsISO->getUniqid();

	$selector = Input::post('selector');

    $is_target = (int) Input::post('is_target',1);

	$total_line = (int) Input::post('total_line',1);

	$campaign_id = (int) Input::post('campaign_id', 0);

	###

	$html = '<tr class="tr_campaign_'.$uid.' tr_campaign_'.$toId.'">

		<td class="text-center">'.($total_line+1).'</td>

		<td class="text-left">

			<input placeholder="Tên nhóm" type="text" name="campaign_info['.$uid.'][group_name]" class="form-control" maxlength="255" />

		</td>

		<td class="text-left">';

			if($selector=='group_department'){

				$field = "{$clsProperty->pkey},title";

				$list_departments = $clsProperty->getAll("is_trash=0 and property_type='_DEPARTMENT' and parent_id='"._DEPARTMENT_SALE_ID."' order by order_no ASC", $field);

				$html.= '<select name="campaign_info['.$uid.'][group_members][]" multiple data-width="100%" 

                data-placeholder="Phòng ban" class="form-control iso-select2">';

					if(!empty($list_departments)){

						foreach($list_departments as $key => $val){

							$html.= '<option value="'.$val[$clsProperty->pkey].'">'.$val['title'].'</option>';

						}

					}

				$html.= '</select>';

			} else if($selector=='group_staff'){

				$field = "{$clsProfile->pkey},full_name,last_name,first_name,code,department_id,role_id";

				$list_staffs = $clsProfile->getAll("`is_trash`=0 and `status_id`<>'"._STATUS_STAFF_OFF_ID."' order by code ASC");

				$html.= '<select name="campaign_info['.$uid.'][group_members][]" data-width="100%" data-placeholder="Nhân viên" 

                multiple class="form-control iso-select2">';

					if(!empty($list_staffs)){

						foreach($list_staffs as $key => $val){

							$html.= '<option value="'.$val[$clsProfile->pkey].'">'.$clsProfile->getIndentityV2($val[$clsProfile->pkey], $val, false).'</option>';

						}

					}

				$html.= '</select>';

			}

		$html.= '</td>

		<td class="text-center">

			<select class="form-control form-select" name="campaign_info['.$uid.'][group_product]">

				'.$clsCampaign->getOptProduct(0).'

			</select>

		</td>

        '.($is_target?'<td class="text-center">

            <input type="text" class="form-control required" name="campaign_info['.$uid.'][group_target]" />

        </td>':'').'

		<td width="45px" class="text-center">

			<button type="button" onClick="$Core.campaign.delete_line(this, event)" uid="'.$uid.'" 

			class="btn p-2 btn-outline-default">'.$clsISO->makeIcon('bx-trash-alt').'</button>

		</td>

	</tr>';

	// Return

	echo $html; die();

}

function default_save_campain(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,

	$description_page,$keyword_page,$extLang,$clsISO,$profile_id;

	// ini_set('display_errors',1);

	// error_reporting(E_ALL);

	$clsProperty = new Property();

	$clsProfile = new Profile();

	$clsCampaign = new Campaign();

	

	$campaign_id = (int) Input::post('campaign_id', 0);

	$title = Input::post('title');

	$start_date = Input::post('start_date');

	$end_date = Input::post('end_date');

	$start_time = !empty($start_date) ? $clsISO->toTime($start_date) : 0;

	$end_time = !empty($end_date) ? $clsISO->toTime($end_date) : 0;

	$selector = Input::post('selector',"group_sale");

    //$is_target = (int) Input::post('is_target',"0");

	$campaign_info = Input::post('campaign_info');

	$campaign_config = Input::post('campaign_config', array());

	

	$is_terms = (int) Input::post('is_terms',0);

	if($is_terms == 1){

		$campaign_terms = Input::post('campaign_terms');

		// $clsISO->print_pre($campaign_terms); die();

	} else {

		$campaign_terms = array();

	}

	// $clsISO->print_pre($campaign_info); die();

	if($start_time >= $end_time){

		echo "_error|||Ngày bắt đầu phải nhỏ hơn ngày kết thúc";

		die();

	} else if(empty($campaign_info)){

		echo "_error|||Đối tượng tham gia không được trống";

		die();

	} else {

		if($selector=='staff'){

        } else if($selector=='group_sale'){

			$opt_department = $campaign_info['opt_department'];

			$department = isset($campaign_info['department']) 

				? $campaign_info['department'] : array();

			if($opt_department=='_select'){

				if(!empty($department)){

					// Next

				} else {

					echo "_error|||Đối tượng tham gia không được trống";

					die();

				}

			}

		} else if($selector=='group_department') {

			$total_invalids = 0;

			$list_group_members = array();

			foreach($campaign_info as $key => $val){

				$group_members = $val['group_members'];

				if(!empty($group_members)){

					foreach($group_members as $oval){

						if(in_array($oval, $list_group_members)){

							$total_invalids += 1;

						} else {

							$list_group_members[] = $oval;

						}

					}

				}

			}

			if($total_invalids > 0){

				echo "_error|||Có ít nhất {$total_invalids} phòng ban bị trùng lặp";

				die();

			}

		} else if($selector=='group_staff') {

			$total_invalids = 0;

			$list_group_staffs = array();

			foreach($campaign_info as $key => $val){

				$group_members = $val['group_members'];

				if(!empty($group_members)){

					foreach($group_members as $oval){

						if(in_array($oval, $list_group_staffs)){

							$total_invalids += 1;

						} else {

							$list_group_staffs[] = $oval;

						}

					}

				}

			}

			if($total_invalids > 0){

				echo "_error|||Có ít nhất {$total_invalids} nhân viên bị trùng lặp";

				die();

			}

		}

	}

	$msg = "_error";

	// Validated

	if($campaign_id==0){

		$campaign_id = $clsCampaign->getMaxId();

		// $clsCampaign->setDebug(true);

		if($clsCampaign->insert(array(

			$clsCampaign->pkey => $campaign_id,

			'title' => $title,

			'slug' => $core->replaceSpace($title),

			'intro' => Input::post('intro'),

			'start_date' => $start_time,

			'end_date' => $end_time,

			'selector' => Input::post('selector'),

            'template' => Input::post('template'),

			'is_terms' => $is_terms,

            //'is_target' => $is_target,

			'campaign_terms' => json_encode($campaign_terms, JSON_UNESCAPED_UNICODE),

			'campaign_info' => json_encode($campaign_info, JSON_UNESCAPED_UNICODE),

			'campaign_config' => json_encode($campaign_config, JSON_UNESCAPED_UNICODE),

			'reg_date' => time(),

			'upd_date' => time(),

			'user_id' => $profile_id,

			'user_id_update' => $profile_id

		))){

			$msg = "_success";

		}

	} else {

		// $clsCampaign->setDebug(true);

		if($clsCampaign->updateOne($campaign_id, array(

			'title' => $title,

			'slug' => $core->replaceSpace($title),

			'intro' => Input::post('intro'),

			'start_date' => $start_time,

			'end_date' => $end_time,

			'selector' => Input::post('selector'),

            'template' => Input::post('template'),

			'is_terms' => $is_terms,

            //'is_target' => $is_target,

			'campaign_terms' => json_encode($campaign_terms, JSON_UNESCAPED_UNICODE),

			'campaign_info' => json_encode($campaign_info, JSON_UNESCAPED_UNICODE),

			'campaign_config' => json_encode($campaign_config, JSON_UNESCAPED_UNICODE),

			'upd_date' => time(),

			'user_id_update' => $profile_id

		))){

			$msg = "_success";

		}

	}

	// Return

	echo $msg; die();	

}

function default_delete(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,

	$description_page,$keyword_page,$extLang,$clsISO,$profile_id;

	// ini_set('display_errors',1);

	// error_reporting(E_ALL);

	$clsProperty = new Property();

	$clsProfile = new Profile();

	$clsCampaign = new Campaign();

	

	$msg = "_error";

	$campaign_id = (int) Input::post('campaign_id', 0);

	if($clsCampaign->deleteOne($campaign_id)){

		$msg = "_success";

	}

	// Return

	echo $msg; die();

}

function default_view(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,

	$description_page,$keyword_page,$extLang,$clsISO,$profile_id;

	$clsStock = new Stock();

	$clsProperty = new Property();

	$clsProfile = new Profile();

	$clsCampaign = new Campaign();

    $clsBilling = new Billing();

	##

	$msg = "_error";

	$string = Input::get('campaign_id');

    $campaign_id = !empty($string) ? (int) $core->decryptID($string) : 0;

    $oneCampaign = $clsCampaign->getOne($campaign_id);

	##

	$prev_month = date('n')-1;

	$current_month = date('n');

	$smarty->assign('prev_month', $prev_month);

	$smarty->assign('current_month', $current_month);

	$start_date = $oneCampaign['start_date'];

	$end_date = $oneCampaign['end_date'];

	// $start_date = strtotime('01-07-2024');

	// $end_date = strtotime('30-09-2024');

	// $oneCampaign['start_date'] = $start_date;

	// $oneCampaign['end_date'] = $end_date;

	$campaign_info = $oneCampaign['campaign_info'];

	$campaign_config = $oneCampaign['campaign_config'];

	$campaign_target = $oneCampaign['campaign_target'];

	$campaign_info = $clsISO->to_array_json($campaign_info);

	$campaign_config = $clsISO->to_array_json($campaign_config);

	$campaign_target = $clsISO->to_array_json($campaign_target);

	$smarty->assign('uid', $uid);

	$smarty->assign('string', $string);

	$smarty->assign('campaign_id', $campaign_id);

	$smarty->assign('oneCampaign', $oneCampaign);

	$smarty->assign('clsProperty', $clsProperty);

	$smarty->assign('clsProperty', $clsProperty);

	$smarty->assign('campaign_config', $campaign_config);

	if(!empty($campaign_info)){

		if($oneCampaign['is_terms']==1){

			$campaign_terms = $oneCampaign['campaign_terms'];

			$campaign_terms = $clsISO->to_array_json($campaign_terms);

			// $clsISO->print_pre($campaign_terms); die();

			$smarty->assign('campaign_terms', $campaign_terms);

			if($oneCampaign['selector'] == 'staff'){

                $cond = "`is_trash`=0 and `status_id`='"._STATUS_STAFF_ON_ID."'"; //  and profile_id='204'

                $cond.= " and {$clsProfile->pkey}<>'"._PROFILE_PARTNER_ID."'";

                if($campaign_info['opt_staff']=='_sale'){

                    $cond.= " and `role_id`<>'"._ROLE_GD_PROJECT."' and `list_department_id` like '%|"._DEPARTMENT_SALE_ID."%'";

                } else if($campaign_info['opt_staff']=='_sale2'){

                    $cond.= " and `list_department_id` like '%|"._DEPARTMENT_SALE_ID."%'";

                } else if($campaign_info['opt_staff']=='_sale3'){

                    $cond.= " and (`list_department_id` like '%|"._DEPARTMENT_SALE_ID."%')";

                } else if($campaign_info['opt_staff']=='_select'){

                    $ids = isset($campaign_info['staff']) && !empty($campaign_info['staff']) ? $campaign_info['staff'] : array();

                    if(!empty($ids)){

                        $cond.= " and {$clsProfile->pkey} in (".implode(',', $ids).")";

                    }

                }

                $field = "{$clsProfile->pkey},full_name,last_name,first_name,code,department_id,role_id";

                $list_staffs = $clsProfile->getAll($cond, $field);

                $total_all_expenses = 0;	

				if(!empty($list_staffs)){

                    foreach($list_staffs as $key => $val){

                        $profile_id = $val[$clsProfile->pkey];

                        #- Điểm

                        $total_scores = 0;

                        $field = "{$clsBilling->pkey},`billing_type`,`stock_code`,`billing_source_id`,`is_fullscore`,`deposit_date`";

                        $list_billings = $clsBilling->getAll("`is_trash`=0 and `is_cancel`=0 and `is_alliance`=0 and 

						`staff_id`='{$profile_id}' and (`deposit_date` between '{$start_date}' AND '{$end_date}')", $field);	

						// $clsISO->print_pre($list_billings); die();

                        if(!empty($list_billings)){

                            foreach($list_billings as $okey => $oval){

								$stock_code = $oval['stock_code'];

								$is_fullscore = $oval['is_fullscore'];

								$billing_type = (int) $oval['billing_type'];

								$billing_source_id = (int) $oval['billing_source_id'];

								$tp = ($billing_source_id == _BILLING_RESOURCE_F1_ID) ? 'f1' : 'cross';

								$list_course[] = $campaign_terms[$billing_type][$tp];

								if($billing_source_id == _BILLING_RESOURCE_F1_ID 

									&& in_array($billing_type, _BILLING_TYPE_MASTERI_GROUP_ID)){

									$total_scores += floatval($campaign_terms[$billing_type][$tp]);

								} else {

									$total_scores += floatval($campaign_terms[$billing_type][$tp]);

								}

                            }

                        }

						$total_expenses = 0;

						if($total_scores >= 60){

							$total_expenses = 20000000;

						} else if($total_scores >= 30 && $total_scores < 60){

							$total_expenses = ($total_scores * 20000000) / 60;

						}

						$total_all_expenses += $total_expenses;

                        $list_staffs[$key]['full_name'] = $clsProfile->getIndentityV2($profile_id, $val, false);

                        $list_staffs[$key]['total_scores'] = $total_scores;

						$list_staffs[$key]['total_expenses'] = $total_expenses;

                    }

                    $total_scores_arrs = @array_column($list_staffs, 'total_scores');

                    @array_multisort($total_scores_arrs, SORT_DESC, $list_staffs);

                }

                $smarty->assign('list_staffs', $list_staffs);

				$smarty->assign('total_all_expenses', $total_all_expenses);

            } else {

                 $list_groups = array();

                 foreach($campaign_info as $key => $val){

                    $group_members = $val['group_members'];

                    #- Điểm

                    $total_scores = 0;

                    $field = "{$clsBilling->pkey},billing_type";

                    $list_billings = $clsBilling->getAll("`is_trash`=0 and `is_cancel`=0 and `staff_id` in (".implode(',',$group_members).") 

                    and (`deposit_date` between '{$start_date}' AND '{$end_date}')", $field);

                    if(!empty($list_billings)){

                        foreach($list_billings as $okey => $oval){

                            $total_scores += floatval($campaign_terms[$oval['billing_type']]);

                        }

                    }

                    $list_groups[] = array(

                        'name' => $val['group_name'],

                        'group_members' => $clsProfile->getNameArray($group_members),

                        'total_scores' => $total_scores

                    );

                }

                $total_scores_arrs = @array_column($list_groups, 'total_scores');

                @array_multisort($total_scores_arrs, SORT_DESC, $list_groups);

                $smarty->assign('list_groups', $list_groups);

            }

		} else {

            $list_groups = array();

			if(!empty($campaign_info)){

				foreach($campaign_info as $key => $val){

					$group_members = $val['group_members'];

					$group_product = $val['group_product'];

					#- Builder cond

					$cond = ""; $more = array();

					if($group_product=='CAO_TANG') $cond.= " and `billing_type`='"._BILLING_TYPE_CT_ID."'";

					if($group_product=='THAP_TANG') $cond.= " and `billing_type`='"._BILLING_TYPE_TT_ID."'";

					if($group_product=='MWF') $cond.= " and `billing_type` in (".implode(',', _BILLING_TYPE_MASTERI_GROUP_ID).")";

					if($group_product=='CHO_THUE') $cond.= " and `billing_type` in (".implode(',', _BILLING_TYPE_CHOTHUE_GROUP_ID).")";

					#- End Builder cond

					$total_scores = $clsBilling->countItem("`is_trash`=0 and `is_cancel`=0 and `staff_id` in (".implode(',',$group_members).") 

                    and (`deposit_date` between '{$start_date}' AND '{$end_date}')".$cond);

					#

					$percent_complete = 0;

					if($total_scores > 0){

						$percent_complete = round(($total_scores/$val['group_target'])*100,2);

					}

					if($oneCampaign['template'] == 'template_1'){

						$list_groups[] = array(

							'name' => $val['group_name'],

							'group_members' => $clsProfile->getNameArray($group_members),

							'total_scores' => $total_scores,

							'group_target' => $val['group_target'],

							'percent_complete' => $percent_complete

						);

					} else if($oneCampaign['template'] == 'template_3'){

						$prev_date = sprintf('01-%s-%s', $clsISO->parseNumber($prev_month), date('Y'));

						$curr_date = sprintf('01-%s-%s', $clsISO->parseNumber($current_month), date('Y'));

						// $clsISO->print_pre($prev_date); die();

						$total_prev_group_trans = $clsBilling->countItem("`is_trash`=0 and `is_cancel`=0 and `staff_id` in (".implode(',',$group_members).") 

                    	and FROM_UNIXTIME(`deposit_date`,'%m/%Y')='".date('m/Y', strtotime('-1 month'))."'".$cond);

						$total_group_trans = $clsBilling->countItem("`is_trash`=0 and `is_cancel`=0 and `staff_id` in (".implode(',',$group_members).") 

                    	and FROM_UNIXTIME(`deposit_date`,'%m/%Y')='".date('m/Y')."'".$cond);

						#

						$total_group_trans_before_month = $clsBilling->countItem("`is_trash`=0 and `is_cancel`=0 and `staff_id` in (".implode(',',$group_members).") and FROM_UNIXTIME(`deposit_date`,'%Y')='".date('Y')."' and `deposit_date`<'".strtotime($prev_date)."'".$cond);

						$total_group_trans_curr_month = $clsBilling->countItem("`is_trash`=0 and `is_cancel`=0 and `staff_id` in (".implode(',',$group_members).") and FROM_UNIXTIME(`deposit_date`,'%Y')='".date('Y')."' and `deposit_date`<'".strtotime($curr_date)."'".$cond);

						#

						$group_targets = isset($campaign_target[$key]) ? $campaign_target[$key] : array();

						// $clsISO->print_pre($group_targets); die();

						$list_members = $clsProfile->getAll("profile_id in (".implode(',', $group_members).")", "{$clsProfile->pkey}, code,full_name,first_name,last_name");

						// $dbconn->debug = true;

						if(!empty($list_members)){

							foreach($list_members as $okey => $oval){

								$staff_id = $oval[$clsProfile->pkey];

								$num_prev_trans = $clsBilling->countItem("`is_trash`=0 and `is_cancel`=0 and `staff_id`='{$staff_id}' 

                    			and FROM_UNIXTIME(`deposit_date`,'%m/%Y')='".date('m/Y', strtotime('-1 month'))."'".$cond);

								$num_trans = $clsBilling->countItem("`is_trash`=0 and `is_cancel`=0 and `staff_id`='{$staff_id}' 

                    			and FROM_UNIXTIME(`deposit_date`,'%m/%Y')='".date('m/Y')."'".$cond);

								##

								$total_trans_before_month = $clsBilling->countItem("`is_trash`=0 and `is_cancel`=0 and `staff_id`='{$staff_id}' and FROM_UNIXTIME(`deposit_date`,'%Y')='".date('Y')."' and `deposit_date`<'".strtotime($prev_date)."'".$cond);

								$total_trans_curr_month = $clsBilling->countItem("`is_trash`=0 and `is_cancel`=0 and `staff_id`='{$staff_id}' and FROM_UNIXTIME(`deposit_date`,'%Y')='".date('Y')."' and `deposit_date`<'".strtotime($curr_date)."'".$cond);

								##

								$list_members[$okey]['num_prev_trans'] = $num_prev_trans;

								$list_members[$okey]['num_trans'] = $num_trans;

								$usr_total_scores = $clsBilling->countItem("`is_trash`=0 and `is_cancel`=0 and `staff_id`='{$staff_id}' 

                    			and (`deposit_date` between '{$start_date}' AND '{$end_date}')".$cond);

								$list_members[$okey]['total_scores'] = $usr_total_scores;

								if(isset($group_targets[$staff_id])){

									$num_target = (int) $group_targets[$staff_id];

									$num_trans_before_month = $num_target - $total_trans_before_month;

									$num_trans_curr_month = $num_target - $total_trans_curr_month;

								} else {

									$num_target = 0;

									$num_trans_curr_month = 0;

									$num_trans_before_month = 0;

								}

								$usr_percent_complete = 0;

								if($total_scores > 0 && $num_target > 0){

									$usr_percent_complete = round(($usr_total_scores/$num_target)*100,2);

								}

								$list_members[$okey]['num_trans_before_month'] = $num_trans_before_month;

								$list_members[$okey]['num_trans_curr_month'] = $num_trans_curr_month;

								$list_members[$okey]['num_target'] = $num_target;

								$list_members[$okey]['usr_percent_complete'] = $usr_percent_complete;

							}

							$total_scores_arrs = @array_column($list_members, 'total_scores');

							@array_multisort($total_scores_arrs, SORT_DESC, $list_members);

						}

						if(!empty($val['group_target'])){

							$group_target = $val['group_target'];

							$num_group_trans_before_month = $group_target - $total_group_trans_before_month;

							$num_group_trans_curr_month = $group_target - $total_group_trans_curr_month;

						} else {

							$group_target = 0;

							$num_group_trans_before_month = 0;

							$num_group_trans_curr_month = 0;

						}

						$list_groups[] = array(

							'name' => $val['group_name'],

							'list_members' => $list_members,

							'total_prev_group_trans' => $total_prev_group_trans,

							'total_group_trans' => $total_group_trans,

							'num_group_trans_before_month' => $num_group_trans_before_month,

							'num_group_trans_curr_month' => $num_group_trans_curr_month,

							'total_scores' => $total_scores,

							'group_target' => $group_target,

							'percent_complete' => $percent_complete

						);

					}

				}

			}

			$total_scores_arrs = @array_column($list_groups, 'total_scores');

			@array_multisort($total_scores_arrs, SORT_DESC, $list_groups);

			$smarty->assign('list_groups', $list_groups);

		}

	}

	/*=============Title & Description Page==================*/

	$title_page = 'Chiến dịch ['.$oneCampaign['title'].'] - '.PAGE_NAME;

	$assign_list["title_page"] = $title_page;

}

function default_race(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,

	$description_page,$keyword_page,$extLang,$clsISO,$profile_id,$deviceType;

	$clsCache = new Cache();

	$clsStock = new Stock();

	$clsProfile = new Profile();

	$clsProperty = new Property();

	$clsCampaign = new Campaign();

    $clsBilling = new Billing();

	#

	$current_year = date('Y');

	$start_date = strtotime(sprintf('01-01-%s', $current_year));

	$end_days = cal_days_in_month(CAL_GREGORIAN, 12, $current_year);

	$end_date = strtotime(sprintf('%s-12-%s', $end_days, $current_year));

	$smarty->assign('current_year', $current_year);

	$smarty->assign('start_date', $start_date);

	$smarty->assign('end_date', $end_date);

	#

	$html_table = "";

	$list_staffs = $clsProfile->getProfileDep(_DEPARTMENT_SALE_ID, 1, "active");

	if(!empty($list_staffs)){

		$arr_staffs_ids = array_keys($list_staffs);

		$arr_staffs_ids = !empty($arr_staffs_ids) ? @array_diff($arr_staffs_ids, array(_PROFILE_PARTNER_ID)) : [];

		$field = "{$clsBilling->pkey},`billing_type`,`stock_code`,`billing_source_id`,`deposit_date`,`totalgrand`,`staff_id`";

		$list_billings = $clsBilling->getAll("`is_trash`=0 AND `is_cancel`=0 AND FROM_UNIXTIME(`deposit_date`,'%Y')='".$current_year."' AND `staff_id` IN ('".implode('\',\'',$arr_staffs_ids)."')", $field);

		if(!empty($list_billings)){

			foreach($list_billings as $key => $val){

				$staff_id = $val['staff_id'];

				if(isset($list_staffs[$staff_id]['list_billings'])){

					$list_staffs[$staff_id]['list_billings'][] = $val;

				} else {

					$list_staffs[$staff_id]['list_billings'][] = $val;

				}

			}

		}

		foreach($list_staffs as $key => $val){

			if(isset($val['list_billings'])){ $ii = 1;

				$more_information = $val['more_information'];

				$department_name = $core->get_field($more_information, "department_name", "");

				$total_scores = 0; $html_content = '<div class="modal-dialog">

					<div class="modal-content">

						<div class="modal-header">

							<h5 class="modal-title">Chi tiết điểm thi đua '.$current_year.'<br />

								<small class="text-fs-13 text-main">

									<i class="bx bx-user" style="transform: translateY(-1px);"></i> 

									'.sprintf('%s-%s-%s', $val['code'], $val['full_name'], $department_name).'

								</small>

							</h5>

							<button class="btn-close closeEv" data-bs-dismiss="modal" aria-label="Close"></button>

						</div>

						<div class="modal-body">

							<div class="table-container no-shadow overflow-x-auto">

								<table cellspacing="0" cellpadding="0" class="table table-striped mb-0">

									<thead><tr>

										<th class="align-center text-center bg-lighter w-px-50 h-px-35">No.</th>

										<th class="align-center bg-lighter h-px-35">Mã căn</th>

										<th class="align-center text-center bg-lighter h-px-35">Loại quỹ</th>

										<th class="align-center text-center bg-lighter h-px-35">Điểm</th>

									</tr></thead>';

				foreach($val['list_billings'] as $okey => $oval){

					$billing_type = (int) $oval['billing_type'];

					$billing_source_id = (int) $oval['billing_source_id'];

					$totalgrand = $clsISO->processSmartNumber($oval['totalgrand']);

					$tp = ($billing_source_id == _BILLING_RESOURCE_F1_ID) ? 'f1' : 'cross';

					if($tp == 'f1'){

						if($totalgrand < 5*_BILLION){

							$score = 5;

							$total_scores += $score;

						} else if($totalgrand >= 5*_BILLION && $totalgrand < 10*_BILLION){

							$score = 7.5;

							$total_scores += $score;

						} else if($totalgrand >= 10*_BILLION && $totalgrand < 20*_BILLION){

							$score = 10;

							$total_scores += $score;

						} else if($totalgrand >= 20*_BILLION && $totalgrand < 35*_BILLION){

							$score = 15;

							$total_scores += $score;

						} else if($totalgrand >= 35*_BILLION && $totalgrand < 50*_BILLION){

							$score = 20;

							$total_scores += $score;

						} else if($totalgrand >= 50*_BILLION){

							$score = 30;

							$total_scores += $score;

						} 

					} else if($tp == 'cross'){

						if($totalgrand < 5*_BILLION){

							$score = 2.5;

							$total_scores += $score;

						} else if($totalgrand >= 5*_BILLION && $totalgrand < 10*_BILLION){

							$score = 3.5;

							$total_scores += $score;

						} else if($totalgrand >= 10*_BILLION && $totalgrand < 20*_BILLION){

							$score = 5;

							$total_scores += $score;

						} else if($totalgrand >= 20*_BILLION && $totalgrand < 35*_BILLION){

							$score = 7.5;

							$total_scores += $score;

						} else if($totalgrand >= 35*_BILLION && $totalgrand < 50*_BILLION){

							$score = 12.5;

							$total_scores += $score;

						} else if($totalgrand >= 50*_BILLION){

							$score = 20;

							$total_scores += 20;

						}

					}

					$html_content.= '<tr>

						<td class="text-center">'.$ii.'</td>

						<td>'.$oval['stock_code'].'</td>

						<td class="text-center">'.($tp == 'f1' ? '<span class="badge bg-label-danger">Độc quyền</span>' : '<span class="badge bg-label-warning">Quỹ chéo</span>').'</td>

						<td class="text-center">'.$score.'</td>

					</tr>';

					++$ii;

				}

				$html_content.= '<tfoot class="text-upper text-center fw-bold">

									<td class="h-px-35 bg-lighter" colspan="3">Tổng điểm</td>

									<td class="h-px-35 bg-lighter">'.$total_scores.'</td>

								</tfoot>

							</table>

						</div></div>

					</div>

				<div>';

				// $clsISO->print_pre($total_scores); die();

				$total_expenses = 0;

				if($total_scores >= 60){

					$total_expenses = 20000000;

				} else if($total_scores >= 30 && $total_scores < 60){

					$total_expenses = ($total_scores * 20000000) / 60;

				}

				$list_staffs[$key]['total_scores'] = $total_scores;

				$list_staffs[$key]['total_expenses'] = $total_expenses;

				$list_staffs[$key]['department_name'] = $department_name;

				$list_staffs[$key]['content'] = $html_content;

				$list_staffs[$key]['is_content'] = 1;

			} else {

				$list_staffs[$key]['total_scores'] = 0;

				$list_staffs[$key]['total_expenses'] = 0;

				$list_staffs[$key]['department_name'] = $department_name;

				$list_staffs[$key]['content'] = "";

				$list_staffs[$key]['is_content'] = 0;

			}

		}

		$total_scores_arrs = @array_column($list_staffs, 'total_scores');

		@array_multisort($total_scores_arrs, SORT_DESC, $list_staffs);

	}

	

	if(!empty($list_staffs)){ $ii = 1;

		foreach($list_staffs as $key => $val){

			$stt = $ii;

			if($ii == 1) $stt = '<img class="w-px-25" src="'.URL_IMAGES.'/top-1.png"';

			if($ii == 2) $stt = '<img class="w-px-25" src="'.URL_IMAGES.'/top-2f.png"';

			if($ii == 3) $stt = '<img class="w-px-25" src="'.URL_IMAGES.'/top-3f.png"';

			$trip_name = "--";

			if($val['total_scores'] >= 200) $trip_name = "Legend";

			if($val['total_scores'] >= 100 && $val['total_scores'] < 200) $trip_name= '<span title="Elite">Elite</span>';

			if($val['total_scores'] >= 50 && $val['total_scores'] < 100) $trip_name= '<span title="Profession">Profession</span>';

			$pro_id = $val[$clsProfile->pkey];

			$html_table.= '<tr class="nohover text-white">

				<td class="algin-center text-center text-fs-15">'.$stt.'</td>

				<td class="algin-center">

					<div class="d-flex gap-1 gap-lg-2 align-items-center">

						<img class="avatar avatar-xs rounded-pill object-fit-cover" src="'.$clsProfile->getAvatar($pro_id, $val, 60,0).'" onerror="this.src=\''.URL_IMAGES.'/no-avatar.jpg\'" >

						<div class="d-flex text-white flex-column gap-0" bis_skin_checked="1">

							<h4 class="text-fs-13 text-nowrap mb-0">'.$clsProfile->getFullName($pro_id, $val).'</h4>

							<div class="d-flex align-items-center gap-2 text-fs-10">'.$val['department_name'].'</div>

						</div>

					</div>

				</td>

				<td class="align-center text-center">

					<a'.($val['is_content'] == 1 ? ' title="Xem chi tiết" onClick="$Core.global.billing.open_score(this, event)"':'').' data-content=\''.$val['content'].'\' staff_id="'.$val[$clsProfile->pkey].'" class="cursor-pointer text-white">'.$val['total_scores'].'</a>

				</td>

				'.($deviceType=='phone'?'':'<td class="align-center text-center lg:d-none">'.$trip_name.'</td>').'

			</tr>';

			++$ii;

		}

	}

	$smarty->assign('html_table', $html_table);

	/*=============Title & Description Page==================*/

	$title_page = sprintf('Thi đua Sale %s - %s', $current_year, PAGE_NAME);

	$assign_list["title_page"] = $title_page;

	$description_page = sprintf('Thi đua Sale %s - %s', $current_year, PAGE_NAME);

	$assign_list["description_page"] = $description_page;

}

function default_top_10_ranker(){

	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,

	$description_page,$keyword_page,$extLang,$clsISO,$profile_id,$deviceType;

	$clsCache = new Cache();

	$clsStock = new Stock();

	$clsProfile = new Profile();

	$clsProperty = new Property();

	$clsCampaign = new Campaign();

    $clsBilling = new Billing();

	

	$current_year = (int) date('Y');

	if($current_year == 2026){

		$department_id = (int) Input::post("department_id", 0);

		$department_id = ($department_id > 0) ? $department_id : _DEPARTMENT_SALE_ID;

		$list_staffs = $clsProfile->getProfileDep($department_id, 1, "active");

		if(!empty($list_staffs)){

			$arr_staffs_ids = array_keys($list_staffs);

			$arr_staffs_ids = !empty($arr_staffs_ids) ? @array_diff($arr_staffs_ids, array(_PROFILE_PARTNER_ID)) : [];

			$field = "{$clsBilling->pkey},`billing_type`,`stock_code`,`billing_source_id`,`deposit_date`,`totalgrand`,`staff_id`";

			$list_billings = $clsBilling->getAll("`is_trash`=0 AND `is_cancel`=0 AND FROM_UNIXTIME(`deposit_date`,'%Y')='".$current_year."' AND `staff_id` IN ('".implode('\',\'',$arr_staffs_ids)."')", $field);

			if(!empty($list_billings)){

				foreach($list_billings as $key => $val){

					$staff_id = $val['staff_id'];

					if(isset($list_staffs[$staff_id]['list_billings'])){

						$list_staffs[$staff_id]['list_billings'][] = $val;

					} else {

						$list_staffs[$staff_id]['list_billings'][] = $val;

					}

				}

			}

			foreach($list_staffs as $key => $val){

				if(isset($val['list_billings'])){ $ii = 1;

					$more_information = $val['more_information'];

					$department_name = $core->get_field($more_information, "department_name", "");

					$total_scores = 0; $html_content = '<div class="modal-dialog">

						<div class="modal-content">

							<div class="modal-header">

								<h5 class="modal-title">Chi tiết điểm thi đua '.$current_year.'<br />

									<small class="text-fs-13 text-main">

										<i class="bx bx-user" style="transform: translateY(-1px);"></i> 

										'.sprintf('%s-%s-%s', $val['code'], $val['full_name'], $department_name).'

									</small>

								</h5>

								<button class="btn-close closeEv" data-bs-dismiss="modal" aria-label="Close"></button>

							</div>

							<div class="modal-body">

								<div class="table-container no-shadow overflow-x-auto">

									<table cellspacing="0" cellpadding="0" class="table table-striped mb-0">

										<thead><tr>

											<th class="align-center text-center bg-lighter w-px-50 h-px-35">No.</th>

											<th class="align-center bg-lighter h-px-35">Mã căn</th>

											<th class="align-center text-center bg-lighter h-px-35">Loại quỹ</th>

											<th class="align-center text-center bg-lighter h-px-35">Điểm</th>

										</tr></thead>';

					foreach($val['list_billings'] as $okey => $oval){

						$billing_type = (int) $oval['billing_type'];

						$billing_source_id = (int) $oval['billing_source_id'];

						$totalgrand = $clsISO->processSmartNumber($oval['totalgrand']);

						$tp = ($billing_source_id == _BILLING_RESOURCE_F1_ID) ? 'f1' : 'cross';

						if($tp == 'f1'){

							if($totalgrand < 5*_BILLION){

								$score = 5;

								$total_scores += $score;

							} else if($totalgrand >= 5*_BILLION && $totalgrand < 10*_BILLION){

								$score = 7.5;

								$total_scores += $score;

							} else if($totalgrand >= 10*_BILLION && $totalgrand < 20*_BILLION){

								$score = 10;

								$total_scores += $score;

							} else if($totalgrand >= 20*_BILLION && $totalgrand < 35*_BILLION){

								$score = 15;

								$total_scores += $score;

							} else if($totalgrand >= 35*_BILLION && $totalgrand < 50*_BILLION){

								$score = 20;

								$total_scores += $score;

							} else if($totalgrand >= 50*_BILLION){

								$score = 30;

								$total_scores += $score;

							} 

						} else if($tp == 'cross'){

							if($totalgrand < 5*_BILLION){

								$score = 2.5;

								$total_scores += $score;

							} else if($totalgrand >= 5*_BILLION && $totalgrand < 10*_BILLION){

								$score = 3.5;

								$total_scores += $score;

							} else if($totalgrand >= 10*_BILLION && $totalgrand < 20*_BILLION){

								$score = 5;

								$total_scores += $score;

							} else if($totalgrand >= 20*_BILLION && $totalgrand < 35*_BILLION){

								$score = 7.5;

								$total_scores += $score;

							} else if($totalgrand >= 35*_BILLION && $totalgrand < 50*_BILLION){

								$score = 12.5;

								$total_scores += $score;

							} else if($totalgrand >= 50*_BILLION){

								$score = 20;

								$total_scores += 20;

							}

						}

						$html_content.= '<tr>

							<td class="text-center">'.$ii.'</td>

							<td>'.$oval['stock_code'].'</td>

							<td class="text-center">'.($tp == 'f1' ? '<span class="badge bg-label-danger">Độc quyền</span>' : '<span class="badge bg-label-warning">Quỹ chéo</span>').'</td>

							<td class="text-center">'.$score.'</td>

						</tr>';

						++$ii;

					}

					$html_content.= '<tfoot class="text-upper text-center fw-bold">

										<td class="h-px-35 bg-lighter" colspan="3">Tổng điểm</td>

										<td class="h-px-35 bg-lighter">'.$total_scores.'</td>

									</tfoot>

								</table>

							</div></div>

						</div>

					<div>';

					// $clsISO->print_pre($total_scores); die();

					$total_expenses = 0;

					if($total_scores >= 60){

						$total_expenses = 20000000;

					} else if($total_scores >= 30 && $total_scores < 60){

						$total_expenses = ($total_scores * 20000000) / 60;

					}

					$list_staffs[$key]['total_scores'] = $total_scores;

					$list_staffs[$key]['total_expenses'] = $total_expenses;

					$list_staffs[$key]['department_name'] = $department_name;

					$list_staffs[$key]['content'] = $html_content;

				} else {

					unset($list_staffs[$key]);

				}

			}

			$total_scores_arrs = @array_column($list_staffs, 'total_scores');

			@array_multisort($total_scores_arrs, SORT_DESC, $list_staffs);

		}

	} else {

		$CAMPAIGN_ID = 25;

		$department_id = (int)Input::post("department_id",0);

		$oneCampaign = $clsCampaign->getOne($CAMPAIGN_ID);

		$start_date = $oneCampaign['start_date'];

		$end_date = $oneCampaign['end_date'];

		$campaign_info = $oneCampaign['campaign_info'];

		$campaign_config = $oneCampaign['campaign_config'];

		$campaign_target = $oneCampaign['campaign_target'];

		$campaign_terms = $oneCampaign['campaign_terms'];

		$campaign_info = $clsISO->to_array_json($campaign_info);

		$campaign_config = $clsISO->to_array_json($campaign_config);

		$campaign_target = $clsISO->to_array_json($campaign_target);

		$campaign_terms = $clsISO->to_array_json($campaign_terms);

		#

		$list_staffs = array();

		if(!empty($campaign_info)){

			$cond = "`is_trash`=0 AND `status_id`='"._STATUS_STAFF_ON_ID."' AND `{$clsProfile->pkey}`<>'"._PROFILE_PARTNER_ID."'";

			if($campaign_info['opt_staff']=='_sale'){

				$cond.= " AND `role_id`<>'"._ROLE_GD_PROJECT."' AND `list_department_id` like '%|"._DEPARTMENT_SALE_ID."%'";

			} else if($campaign_info['opt_staff']=='_sale2'){

				$cond.= " AND `list_department_id` like '%|"._DEPARTMENT_SALE_ID."%'";

			} else if($campaign_info['opt_staff']=='_sale3'){

				$cond.= " AND (`list_department_id` like '%|"._DEPARTMENT_SALE_ID."%')";

			} else if($campaign_info['opt_staff']=='_select'){

				$ids = $core->get_field($campaign_info, "staff", []);

				if(!empty($ids)){

					$cond.= " AND `{$clsProfile->pkey}` in (".implode(',', $ids).")";

				}

			}

			if(!empty($department_id)) {

				$cond .= " AND `department_id`='{$department_id}' ";

			}

			$total_all_expenses = 0;

			$cached_name = sprintf('campaign_staff_cached_%s', $department_id);

			if($clsCache->has($cached_name)){

				$list_staffs = $clsCache->get($cached_name);

				// $clsCache->delete($cached_name);

			} else {

				$field = "{$clsProfile->pkey},`full_name`,`avatar`,`more_information`";

				$list_staffs = $clsProfile->getAll($cond, $field);

				$clsCache->put($cached_name, json_encode($list_staffs, JSON_UNESCAPED_UNICODE), 60*60);

			}

			if(!empty($list_staffs)){

				foreach($list_staffs as $key => $val){

					$pro_id = $val[$clsProfile->pkey];

					$prof_information = $val['more_information'];

					$prof_information = $clsISO->to_array_json($prof_information);

					$department_name = $core->get_field($prof_information, "department_name", "");

					#- Điểm

					$total_scores = 0; $html_content = "";

					$field = "{$clsBilling->pkey},`billing_type`,`stock_code`,`billing_source_id`,`is_fullscore`,`deposit_date`";

					$list_billings = $clsBilling->getAll("`is_trash`=0 and `is_cancel`=0 and `is_alliance`=0 and 

					`staff_id`='{$pro_id}' and (`deposit_date` between '{$start_date}' AND '{$end_date}')", $field);

					if(!empty($list_billings)){ $ii = 1;

						foreach($list_billings as $okey => $oval){

							$is_fullscore = $oval['is_fullscore'];

							$billing_type = (int) $oval['billing_type'];

							$billing_source_id = (int) $oval['billing_source_id'];

							$tp = ($billing_source_id == _BILLING_RESOURCE_F1_ID) ? 'f1' : 'cross';

							if($billing_source_id == _BILLING_RESOURCE_F1_ID 

								&& in_array($billing_type, _BILLING_TYPE_MASTERI_GROUP_ID)){

								$total_scores += floatval($campaign_terms[$billing_type][$tp]);

							} else {

								$total_scores += floatval($campaign_terms[$billing_type][$tp]);

							}

							++$ii;

						}

					}

					$total_expenses = 0;

					if($total_scores >= 60){

						$total_expenses = 20000000;

					} else if($total_scores >= 30 && $total_scores < 60){

						$total_expenses = ($total_scores * 20000000) / 60;

					}

					$total_all_expenses += $total_expenses;

					$list_staffs[$key]['total_scores'] = $total_scores;

					$list_staffs[$key]['total_expenses'] = $total_expenses;

					$list_staffs[$key]['department_name'] = $department_name;

				}

				$total_scores_arrs = @array_column($list_staffs, 'total_scores');

				@array_multisort($total_scores_arrs, SORT_DESC, $list_staffs);

			}

		}

	}

	$html = "";

	if(!empty($list_staffs)){ $ii = 1;

		foreach($list_staffs as $key => $val){

			if($ii <= 10){

				$stt = $ii;

				if($ii == 1) $stt = '<img class="w-px-25" src="'.URL_IMAGES.'/top-1.png"';

				if($ii == 2) $stt = '<img class="w-px-25" src="'.URL_IMAGES.'/top-2f.png"';

				if($ii == 3) $stt = '<img class="w-px-25" src="'.URL_IMAGES.'/top-3f.png"';

				$trip_name = "--";

				if($val['total_scores'] >= 200) $trip_name = "Legend";

				if($val['total_scores'] >= 100 && $val['total_scores'] < 200) $trip_name= '<span title="Elite">Elite</span>';

				if($val['total_scores'] >= 50 && $val['total_scores'] < 100) $trip_name= '<span title="Profession">Profession</span>';

				$pro_id = $val[$clsProfile->pkey];

				$html.= '<tr class="nohover text-white">

					<td class="algin-center text-center text-fs-16">'.$stt.'</td>

					<td class="algin-center">

						<div class="d-flex gap-1 gap-lg-2 align-items-center">

							<img class="avatar avatar-xs rounded-pill" src="'.$clsProfile->getAvatar($pro_id, $val, 30, 30).'">

							<div class="d-flex text-white flex-column gap-0" bis_skin_checked="1">

								<h4 class="text-fs-13 text-nowrap mb-0">'.$clsProfile->getFullName($pro_id, $val).'</h4>

								<div class="d-flex align-items-center gap-2 text-fs-10">'.$val['department_name'].'</div>

							</div>

						</div>

					</td>

					<td class="align-center text-center">

						<a onClick="$Core.global.billing.open_score(this, event)" data-content=\''.$val['content'].'\' 

							staff_id="'.$val[$clsProfile->pkey].'" class="text-white">'.$val['total_scores'].'</a>

					</td>

					'.($deviceType=='phone'?'':'<td class="align-center text-center lg:d-none">'.$trip_name.'</td>').'

				</tr>';

			}

			++$ii;

		}

	}

	// Return

	echo json_encode(array(

		'html' => $html

	)); die();

}