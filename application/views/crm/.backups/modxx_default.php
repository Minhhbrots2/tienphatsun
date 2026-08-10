<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # ISOCMS 4.1.0 By Luong Tien Dung (luongtiendung@gmail.com)
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2013 VietISO JSC.             # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||
|| #################################################################### ||
\*======================================================================*/
function default_page_customer(){
	global $adminid,$core,$clsISO;
	$clsProperty = new Property();
	$status_id = Input::post('status_id',0);
	$holderG = Input::post('holderG','_tablist');
	$props = 'customer_id="0" holderG="'.$holderG.'" status_id="'.$status_id.'"';
	$titlePage = ($holderG=='_trash') ? 'Lưu trữ' : $clsProperty->getTitle($status_id);
	#
	$html = '<div class="mg-wrapper">
		<div class="card">
			<div class="card-header p-3 mb-3 border-bottom">
				<div class="w-100 d-flex flex-wrap justify-content-between align-items-center">
					<div class="p__left d-flex align-items-center">
						'.CRM::renderHTMLButtonBack(array('page'=>'customer','action'=>'page')).'
						<span class="ml-2 text-upper">'.$titlePage.'</span>
					</div>
					<div class="p__right">
						<input type="hidden" data-field="status_id" class="search_field" value="'.$status_id.'" />
						<div class="input-group input-group-merge w-px-200 mr-2">
							<span class="input-group-text"><i class="bx bx-search"></i></span>
							<input class="form-control search_field" onChange="$Core.crm.do_search(this,event)" holderG="'.$holderG.'" data-field="keySearch" placeholder="Tìm kiếm">
						</div>
					</div>
				</div>
			</div>
			<div class="card-body min-vh-100">
				<div id="holder_customer" class="holder_customer overflow-x-auto">
					'.CRM::renderHTMLLoading().'
				</div>
			</div>
		</div>
	</div>';
	// Return
	echo($html);die();
}
function default_load_customers(){
	global $profile_id, $core, $clsISO, $clsUser, $_LANG_ID, $dbconn,$clsProfile, $deviceType;
	$clsStock = new Stock();
	$clsCountry = new Country();
	$clsArchived = new Archived();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsFollowUp = new FollowUp();
	$clsCampaign = new Campaign();
	/* Global cond */
	$now = time();
	$is_checkbox = false;
	$cond = $cnd = "`is_trash`=0";
	$has_search = $has_group = false;
	$holderG = Input::post('holderG','_tablist');
	$keysearch = Input::post('keysearch', "");
	$status_id = (int) Input::post('status_id');
	$priority_id = (int) Input::post('priority_id', 0);
	$resource_id = (int) Input::post('resource_id', 0);
	$typeHolder = Input::post('typeHolder',"_all");
	$from_notify = 0;
	if(!empty($keysearch)){
		$has_search = true;
		$arr_ids = @explode(',', $keysearch);
		$slug = $core->replaceSpace($keysearch);
		if(!empty($arr_ids)){
			$from_notify = 1;
			$cond.= " and (`name` like '%{$keysearch}%' 
				or `name_slug` like '%{$slug}%' 
				or `email` like '%{$keysearch}%' 
				or `phone` like '%{$keysearch}%'
				or `address` like '%{$keysearch}%' 
				or `{$clsCustomer->pkey}` in (".implode(',', $arr_ids).")
			)";
		} else {
			$cond.= " and (`name` like '%{$keysearch}%' 
				or `name_slug` like '%{$slug}%' 
				or `email` like '%{$keysearch}%' 
				or `phone` like '%{$keysearch}%'
				or `address` like '%{$keysearch}%'
				
			)";
		}
	}
	if(intval($priority_id) > 0){
		$has_search = true;
		$cnd.= " and `priority_id`='{$priority_id}'";
		$cond.= " and `priority_id`='{$priority_id}'";
	}
	if(!empty($status_id)){
		$has_search = true;
		$cond.= " and `status_id`='{$status_id}'";
	}
	if($resource_id > 0){
		$has_search = true;
		$cnd.= " and `resource_id`='{$resource_id}'";
		$cond.= " and `resource_id`='{$resource_id}'";
	}
	$is_all = (int) Input::post('is_all', 0);
	$reg_date = Input::post('reg_date', "");
	$sort_by = Input::post('sort_by','manage');
	$view_by = Input::post("view_by", "table");
	$reg_date_range = Input::post('reg_date_range');
	$group_id = (int) Input::post('group_id',0, true);
	$admin_id = (int) Input::post('admin_id',0, true);
	$blocktype_id = (int) Input::post('blocktype_id',0, true);
	$campaign_id = (int) Input::post('campaign_id',0, true);
	if(!empty($reg_date)){
		$cnd.= " and FROM_UNIXTIME(`reg_date`,'%Y-%m-%d')='{$reg_date}'";
		$cond.= " and FROM_UNIXTIME(`reg_date`,'%Y-%m-%d')='{$reg_date}'";
	}
	if($campaign_id > 0){
		$cnd.= " and `list_campaign_id` like '%|{$campaign_id}|%'";
		$cond.= " and `list_campaign_id` like '%|{$campaign_id}|%'";
	}
	$orderBy = ""; // Set Order Default
	if(!empty($reg_date_range)){
		$tmp = explode('-', $reg_date_range);
		$start_day 	= (int) $tmp[0];
		$due_day 	= (int) $tmp[1];
		$start_date = strtotime("-{$due_day} days");
		if($start_day == 0){
			$due_date = $now;
		} else {
			$orderBy = " order by `upd_date` ASC";
			$due_date = strtotime("-{$start_day} days");
		}
		$cnd.= " and (`reg_date` between {$start_date} and {$due_date})";
		$cond.= " and (`reg_date` between {$start_date} and {$due_date})";
	}
	$sql_string = $sql_cond = $cond;
	if($group_id > 0){
		$clsGroupProfile = new GroupProfile();
		$list_profile_id = $clsGroupProfile->getOneField('list_profile_id', $group_id);
		$list_profile_arrs = !empty($list_profile_id) 
			? $clsISO->getArrayByTextSlash($list_profile_id) : array(); 
		if(!empty($list_profile_arrs)){
			$has_group = true;
			$cnd.= " and (`admin_id` in (".implode(',', $list_profile_arrs)."))";
			$cond.= " and (`admin_id` in (".implode(',', $list_profile_arrs)."))";
		}
	}
	if($admin_id > 0){
		$is_checkbox = false; // Ẩn checkbox vì mình không phải là người quản lý.
		$sql_cond.= " and (`admin_id`='{$admin_id}')";
		$cnd.= " and (`admin_id`='{$admin_id}' and `list_share_id` like '%|{$profile_id}|%')";
		$cond.= " and (`admin_id`='{$admin_id}' and `list_share_id` like '%|{$profile_id}|%')";
	}
	if($blocktype_id > 0){
		$cnd.= " and `blocktype_id`='{$blocktype_id}'";
		$cond.= " and `blocktype_id`='{$blocktype_id}'";
	}
	if($is_all == 0){
		$cnd.= " and `customer_id` not in (select `customer_id` 
			from `{$clsArchived->tbl}` where `profile_id`='{$profile_id}'
		)";
		$cond.= " and `customer_id` not in (select `customer_id` 
			from `{$clsArchived->tbl}` where `profile_id`='{$profile_id}'
		)";
	}
	#- Order by
	if($sort_by == 'manage' && $has_group == false){
		if($admin_id == 0){
			if($has_search == false){
				$cnd.= " and `admin_id`='{$profile_id}'";
				$cond.= " and `admin_id`='{$profile_id}'";
			} else {
				$cnd.= " and (`admin_id`='{$profile_id}' or `list_share_id` like '%|{$profile_id}|%')";
				$cond.= " and (`admin_id`='{$profile_id}' or `list_share_id` like '%|{$profile_id}|%')";
			}
		}
		$is_checkbox = true;
		$orderBy = !empty($orderBy) ? $orderBy : " order by `upd_date` DESC";
	} else if($sort_by=='assign' && $has_group == false) {
		if($admin_id == 0 ){
			$cnd.= " and `admin_id`<>'{$profile_id}' and (`user_id`='{$profile_id}' 
				or `list_share_id` like '%|{$profile_id}|%')";
			$cond.= " and `admin_id`<>'{$profile_id}' and (`user_id`='{$profile_id}' 
				or `list_share_id` like '%|{$profile_id}|%')";
		}
		$orderBy = " order by `use_globe` DESC, `upd_date` DESC";
	}
	//echo $cond; die;
	$more = array();
	$html = ($typeHolder=='_calendar'?'
	<input type="hidden" data_field="date_id" value="'.$date_id.'" />
	<input type="hidden" data_field="typeHolder" value="'.$typeHolder.'" />	':'').'
	<table border="0" width="100%" class="table radius-4 overflow-hidden'.($deviceType=='phone'?' table-fixed':'').'">
		<thead><tr>
			'.($deviceType=='phone'?'
			<th class="align-center bg-grayter h-px-40 border-0">Họ và tên</th>
			<th class="align-center bg-grayter h-px-40 border-0" width="100px">Điện thoại</th>
			<th class="text-center bg-grayter align-center h-px-40 border-0" width="80px">FU</th>' : '
			<th class="align-center bg-grayter text-center h-px-40 border-0" width="40px">
				<input type="checkbox"'.($is_checkbox==false ? ' disabled' : '').' 
					class="form-check-input" tp="all" onChange="$Core.crm.check_item(this, event)" />
			</th>'. ($view_by=='table' ? '
			<th class="align-center bg-grayter h-px-40 border-0">Họ và tên</th>
			<th class="align-center bg-grayter h-px-40 border-0" width="15%">Điện thoại</th>
			<th class="align-center text-left bg-grayter h-px-40 border-0" width="120px">Nguồn khách</th>
			<th class="align-center text-left bg-grayter h-px-40 border-0" width="120px">Chiến dịch</th>
			<th class="align-center text-left bg-grayter h-px-40 border-0" width="120px">Loại hình</th>
			<th class="align-center text-left bg-grayter h-px-40 border-0" width="120px">Tình trạng</th>
			<th class="align-center text-left bg-grayter h-px-40 border-0" width="50px">Q.Lý</th>
			<th class="align-center text-left bg-grayter h-px-40 border-0" colspan="2">Follow-Ups</th>
			<th class="align-center text-center bg-grayter h-px-40 border-0" width="60px"></th>' : '
			<th class="align-center text-left bg-grayter h-px-40 border-0" width="130px">Ngày</th>
			<th class="align-center text-left bg-grayter h-px-40 border-0">Họ và tên</th>
			<th class="align-center text-left bg-grayter h-px-40 border-0" width="120px">Điện thoại</th>
			<th class="align-center text-center bg-grayter h-px-40 border-0" width="50px">Q.Lý</th>
			<th class="align-center text-left bg-grayter h-px-40 border-0" width="120px">Tình trạng</th>
			<th class="align-center text-left bg-grayter h-px-40 border-0" width="120px">Nguồn khách</th>
			<th class="align-center text-left bg-grayter h-px-40 border-0" width="120px">Loại hình</th>
			<th class="align-center text-left bg-grayter h-px-40 border-0" width="120px">Chiến dịch</th>
			<th class="align-center text-left bg-grayter h-px-40 border-0" width="20%">Mô tả</th>
			<th class="align-center text-center bg-grayter h-px-40 border-0" width="60px"></th>')).'
		</tr></thead>';
		#- beign pagination
		$current_page = (int) Input::post('page', 1);
		$per_page = (int) Input::post('per_page',50);
		$total_record = $clsCustomer->countItem($cond);
		$total_page = ceil($total_record/$per_page);
		$offset = ($current_page-1)*$per_page;
		$limitCond = " limit {$offset},{$per_page}";
		#- end pagination
		if($has_group == false){
			$total_manage = $clsCustomer->countItem("{$sql_string} and (`admin_id`='{$profile_id}')");
			$total_assign = $clsCustomer->countItem("{$sql_cond} and (`admin_id`<>'{$profile_id}' 
			and list_share_id like '%|{$profile_id}|%')");
		} else {
			$total_manage = $total_record;
			$total_assign = 0;
		}
		$field = "{$clsCustomer->pkey},CONVERT(BINARY(CONVERT(`phone` USING latin1)) USING utf8mb4) as `phone`";
		$list_customers = $clsCustomer->getAll("{$cond} {$orderBy}".$limitCond);
		if(!empty($list_customers)){ $ii=0;// Init
			$arr_profile_cached = $arr_property_cached = $arr_status_cached = array();
			foreach($list_customers as $cus){
				$admin_id = $cus['admin_id'];
				$status_id = (int) $cus['status_id'];
				$customer_id = $cus[$clsCustomer->pkey];
				$resource_id = (int) $cus['resource_id'];
				$blocktype_id = (int) $cus['blocktype_id'];
				$list_tags_id = $cus['list_tags_id'];
				$list_stock_id = $cus['list_stock_id'];
				$list_campaign_id = $cus['list_campaign_id'];
				$campaign_arrs = !empty($list_campaign_id) 
					? $clsISO->getArrayByTextSlash($list_campaign_id) : array(); 
				$list_stock_arrs = !empty($list_stock_id) 
					? $clsISO->getArrayByTextSlash($list_stock_id) : array(); 
				$list_tags_arrs = !empty($list_tags_id) 
					? $clsISO->getArrayByTextSlash($list_tags_id) : array(); 
				$icon = ($admin_id==$profile_id)?'plus':'gavel';
				#- Mục đích
				$title_purpose = "";
				$list_archived = $cus['list_archived'];
				$list_purpose_id = $cus['list_purpose_id'];
				if(!empty($list_purpose_id)){
					$tmp = $clsISO->getArrayByTextSlash($list_purpose_id);
					$title_purpose = $clsProperty->getTitleArray($tmp, true);
				}
				$is_archived = 0;
				$tmp = $clsArchived->getByCond("`profile_id`='{$profile_id}' and `customer_id`='{$customer_id}'");
				if(!empty($tmp)){ $is_archived = 1; }
				###
				$html_follow_ups = "";
				$props = 'customer_id="'.$customer_id.'"';
				$total_followups = $total_followups_next = 0;
				$list_followups = $clsFollowUp->getAll("`customer_id`='{$customer_id}'", "{$clsFollowUp->pkey},`date_id`");
				if(!empty($list_followups)){
					$total_followups = count($list_followups);
					foreach($list_followups as $mkey => $mval){
						if($mval['date_id'] >= time()){
							$total_followups_next+= 1;
						}
					}
				}
				$html_tags = $clsCustomer->getHTMLTags($customer_id, $cus);
				$html_stocks = !empty($list_stock_arrs) 
					? sprintf('<div class="d-flex gap-1 mb-1">%s</div>', $clsStock->getTitleArray($list_stock_arrs, "_list")) : "";
				$list_follows_ups = $clsFollowUp->getAll("`customer_id`='{$customer_id}' order by `reg_date` DESC limit 0,2");
				if(!empty($list_follows_ups)){ $kk = 1;
					$html_follow_ups.= '<ul class="mb-0 list-unstyled lh-xs">';
					foreach($list_follows_ups as $nkey => $nval){
						$html_follow_ups.= '<li class="fs-12 my-0"><span class="text-'.($nval['date_id'] > $now ? 'main' : 'muted').'">'.($nval['date_id'] > $now ? $clsISO->getTimeMore($nval['date_id']) : $clsISO->getTimeAgo($nval['date_id']) ) .'</span> - '.ucfirst($nval['intro']).'</li>';
						++$kk; 
					}
					$html_follow_ups.= '</ul>';
				}
				if($deviceType=='phone'){
					if(!isset($arr_property_cached[$status_id])){
						$arr_property_cached[$status_id] = $clsProperty->getLabel($status_id, " mr-1");
					}
					$html .= '<tr class="pointer-event trCustomer'.(!empty($html_follow_ups) || !empty($html_tags) || !empty($html_stocks) ? " tr-follow-ups" : "").'" onDblClick="$Core.crm.view_activity(this, event);" customer_id="'.$customer_id.'">
						<td class="position-relative pointer-event px-0">
							<div class="d-block text-nowrap mr-1 overflow-hidden">'.$arr_property_cached[$status_id].'<a href="javascript:void(0);" onClick="$Core.crm.open_customer(this,event)" class="link goLink font-bold view_customer" route="/customer/'.$customer_id.'/overview" '.$props.'>'.ucfirst($cus['name']).'</a></div>
							<div class="d-flex align-items-center gap-1">
								<a href="javascript:void(0);" onClick="$Core.crm.change_assigned(this, event)" customer_id="'.$customer_id.'" class="user-avatar">
									<img class="avatar avatar-xxs rounded-pill" src="'.$clsProfile->getAvatar($admin_id).'">
									<span class="cre">'.$core->makeIcon($icon).'</span>
								</a> 
								<span class="text-muted text-nowrap fs-12">
									<!-- <i class="material-icons-outlined">update</i> -->
									'.$clsISO->getTimeAgo($cus['reg_date']).'
								</span>
							</div>'.$title_purpose.'
						</td>
						<td class="text-left px-1 align-top">
							<div class="d-flex align-items-center">
								'.(!empty($cus['phone'])?'<a href="https://zalo.me/'.$cus['phone'].'" target="_blank" class="zalo_chat mr-1"></a>
								<div class="clearfix"></div>
								<a href="tel:'.$cus['phone'].'" class="js_clicktocall text-nowrap">
									<img src="'.URL_IMAGES.'/phone-icon.png">
									'.$clsCustomer->truncate($cus['phone'], true).'
								</a>':'').'
							</div>
						</td>
						<td class="text-center align-top px-1 pr-0">
							<div class="btn-group">
								<span class="btn btn-icon btn-sm btn-outline-default text-main">'.$total_followups.'</span>
								<a href="javascript:void(0);" title="Lưu trữ" class="btn btn-icon btn-sm btn-outline-default" onClick="$Core.crm.set_archived(this, event);" customer_id="'.$customer_id.'">'.$clsISO->makeIcon('bx bx-archive-in text-'.($is_archived?'yellow':'blank')).'</a>
								<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-outline-default text-muted" title="Follow-ups" onClick="$Core.crm.view_activity(this, event);" customer_id="'.$customer_id.'"><i class="bx bx-bell"></i></a>
							</div>
						</td>
					</tr>
					'.(!empty($html_follow_ups) || !empty($html_tags) || !empty($html_stocks) ? '<tr class="nohover">
						<td onDblClick="$Core.crm.view_activity(this, event);" customer_id="'.$customer_id.'" class="pl-0 pt-0" colspan="3">'.$html_stocks.$html_follow_ups.$html_tags.'</td>
					</tr>' : '');	
				} else {
					if(isset($arr_status_cached[$status_id])){
						$oProperty = $arr_status_cached[$status_id];
					}  else {
						$oProperty = $clsProperty->getOne($status_id, "`title`,`bgcolor`");
						$arr_status_cached[$status_id] = $oProperty;
					}
					if(!isset($arr_property_cached[$status_id])){
						if($view_by == 'table'){
							$arr_property_cached[$status_id] = $clsProperty->getTextColor($status_id);
						} else {
							$arr_property_cached[$status_id] = $clsProperty->getTitle($status_id, $oProperty);
						}
					}
					if($resource_id > 0 && !isset($arr_profile_cached[$resource_id])){
						$arr_profile_cached[$resource_id] = $clsProperty->getTitle($resource_id);
					}
					$blocktype_name = "--";
					if($blocktype_id > 0){
						if(isset($arr_property_cached[$blocktype_id])){
							$blocktype_name = $arr_property_cached[$blocktype_id];
						} else {
							$arr_property_cached[$blocktype_id] = $clsProperty->getTitleCache('_BLOCK_TYPE', $blocktype_id);
							$blocktype_name = $arr_property_cached[$blocktype_id];
						}
					}	
					if($view_by == 'table'){
						$html .= '<tr class="pointer-event" onDblClick="$Core.crm.view_activity(this, event);" customer_id="'.$customer_id.'" class="trCustomer">
							<td class="position-relative text-center">
								<input'.($is_checkbox==false ? ' disabled' : '').' type="checkbox" tp="item" onChange="$Core.crm.check_item(this, event)" 
								value="'.$customer_id.'" class="form-check-input chk_customer" />
							</td>
							<td class="position-relative">
								<div class="d-block text-nowrap"><a href="javascript:void(0);" onClick="$Core.crm.open_customer(this,event)" class="link goLink font-bold view_customer fs-6" route="/customer/'.$customer_id.'/overview" '.$props.'>'.ucfirst($cus['name']).'</a>'.$title_purpose.'</div>
								<div class="d-flex align-items-center gap-1 fs-11">
									<i class="material-icons-outlined fs-13 no-translate">more_time</i>
									'.$clsISO->getTimeAgo($cus['reg_date']).'
								</div>
								<div class="d-flex align-items-center text-warning gap-1 fs-11">
									<i class="material-icons-outlined fs-13 no-translate">update</i>
									'.$clsISO->getTimeAgo($cus['upd_date']).'
								</div>
								'.(!empty($cus['begin_need']) ? '<div class="begin_need fs-12 mb-n1 lh-sm">'.htmlspecialchars($cus['begin_need']).'</div>': '').'
							</td>
							<td class="text-left">
								<div class="d-flex gap-1 align-items-center">
									'.(!empty($cus['phone'])?'<a href="https://zalo.me/'.$cus['phone'].'" target="_blank" class="zalo_chat"></a>
									<a href="tel:'.$cus['phone'].'" class="js_clicktocall text-nowrap text-body fs-13">
										<img src="'.URL_IMAGES.'/phone-icon.png">
										'.$clsCustomer->mask($cus['phone'], true).'</a>':'---').'
								</div>
							</td>
							<td class="text-left">'.$arr_profile_cached[$resource_id].'</td>
							<td class="text-left">'.(!empty($campaign_arrs) ? $clsCampaign->getTitleArray($campaign_arrs) : "--").'</td>
							<td class="text-left">'.$blocktype_name.'</td>
							<td class="text-left">'.$arr_property_cached[$status_id].'</td>
							<td data-label="Gán cho" class="text-center">
								<a href="javascript:void(0);"'.($admin_id==$profile_id?' onClick="$Core.crm.change_assigned(this, event)"':'').' customer_id="'.$customer_id.'" class="user-avatar" data-url="/index.php?mod=home&amp;act=load_profile_popover&user_id='.$admin_id.'" data-toggle="webui-popover" data-trigger="hover" data-width="400">
									<img class="avatar avatar-xs mr-2 rounded-pill" src="'.$clsProfile->getAvatar($admin_id).'">
									<span class="cre">'.$core->makeIcon($icon).'</span>
								</a>
							</td>
							<td class="text-center">
								<a onClick="$Core.crm.view_activity(this, event);" customer_id="'.$customer_id.'" class="btn btn-sm btn-icon btn-outline-default text-main">'.$total_followups.'</a>
							</td>
							<td class="text-left">
								'.$html_stocks.$html_follow_ups.$html_tags.'
								<!--'.(!empty($oneFollowup) ? '<i class="material-icons-outlined">notifications_active</i> <a href="javascript:void(0);" onClick="$Core.crm.open_followups(this, event)" customer_id="'.$customer_id.'" followup_id="'.$oneFollowup['followup_id'].'">'.$clsISO->convertTimeToText($oneFollowup['date_id'], true).'</a>' : '<span class="text-muted"><i class=\'bx bx-bell\'></i> Chưa có</span>').' -->
							</td>
							<td width="60px" class="text-center">
								<div class="btn-group">
									<a href="javascript:void(0);" title="Lưu trữ" class="btn btn-icon btn-sm btn-outline-default" onClick="$Core.crm.set_archived(this, event);" customer_id="'.$customer_id.'">'.$clsISO->makeIcon('bx bx-archive-in text-'.($is_archived?'yellow':'blank')).'</a>
									<a href="javascript:void(0);" title="Follow-ups" class="btn btn-icon btn-sm btn-outline-default" onClick="$Core.crm.view_activity(this, event);" customer_id="'.$customer_id.'"><i class="bx bx-bell"></i></a>
									<a href="javascript:void(0);" title="Follow-ups" onClick="$Core.crm.view_activity(this, event);" customer_id="'.$customer_id.'" class="btn btn-icon btn-sm btn-outline-default text-main">'.$total_followups_next.'</a>
								</div>
							</td>
						</tr>';
					} else {
						$html.= '<tr class="pointer-event" onDblClick="$Core.crm.view_activity(this, event);" 
							customer_id="'.$customer_id.'" class="trCustomer">
							<td class="position-relative text-center">
								<input'.($is_checkbox==false ? ' disabled' : '').' type="checkbox" tp="item" onChange="$Core.crm.check_item(this, event)" value="'.$customer_id.'" class="form-check-input chk_customer" /> 
							</td>
							<td class="text-left align-center text-nowrap">
								<i class="material-icons-outlined">more_time</i>
								'.$clsISO->getTimeAgo($cus['reg_date']).'
							</td>
							<td class="text-left align-center text-nowrap" style="background:'.$oProperty['bgcolor'].';">
								<a href="javascript:void(0);" onClick="$Core.crm.open_customer(this,event)" class="font-bold text-white view_customer" route="/customer/'.$customer_id.'/overview" '.$props.'>'.ucfirst($cus['name']).'</a>
							</td>
							<td class="text-left align-center">
								<a href="tel:'.$cus['phone'].'" class="js_clicktocall x-large text-nowrap fs-13">
									<img src="'.URL_IMAGES.'/phone-icon.png"> 
									'.$clsCustomer->mask($cus['phone'], true).'
								</a>
							</td>
							<td class="text-center align-center">
								<a href="javascript:void(0);"'.($admin_id==$profile_id?' onClick="$Core.crm.change_assigned(this, event)"':'').' customer_id="'.$customer_id.'" class="user-avatar" data-url="/index.php?mod=home&amp;act=load_profile_popover&user_id='.$admin_id.'" data-toggle="webui-popover" data-trigger="hover" data-width="400">
									<img class="avatar avatar-xs mr-2 rounded-pill" src="'.$clsProfile->getAvatar($admin_id).'">
									<span class="cre">'.$core->makeIcon($icon).'</span>
								</a>
							</td>
							<td class="text-left align-center">
								'.$arr_property_cached[$status_id].'
							</td>
							<td class="text-left align-center">
								'.$arr_profile_cached[$resource_id].'
							</td>
							<td class="text-left align-center">
								'.$blocktype_name.'
							</td>
							<td class="text-left text-nowrap align-center">
								'.(!empty($campaign_arrs) ? $clsCampaign->getTitleArray($campaign_arrs) : "--").'
							</td>
							<td class="align-center">'.(!empty($cus['begin_need']) ? $cus['begin_need']: '').'</td>
							<td width="60px" class="align-center text-center">
								<div class="btn-group">
									<a href="javascript:void(0);" title="Lưu trữ" class="btn btn-icon btn-sm btn-outline-default" onClick="$Core.crm.set_archived(this, event);" customer_id="'.$customer_id.'">'.$clsISO->makeIcon('bx bx-archive-in text-'.($is_archived?'yellow':'blank')).'</a>
									<a href="javascript:void(0);" title="Follow-ups" class="btn btn-icon btn-sm btn-outline-default" onClick="$Core.crm.view_activity(this, event);" customer_id="'.$customer_id.'"><i class="bx bx-bell"></i></a>
									<a href="javascript:void(0);" title="Follow-ups" onClick="$Core.crm.view_activity(this, event);" customer_id="'.$customer_id.'" class="btn btn-icon btn-sm btn-outline-default">'.$total_followups_next.'</a>
								</div>
							</td>
						</tr>';
					}
				}
				++$ii;
			}
		} else {
			$html .= '<tr>
				<td colspan="10">'.CRM::renderHTMLNoDocument('Not any record(s)').'</td>
			</tr>';
		}
	$html .= '</table>
	<input type="hidden" class="PageCustomer_Length" value="'.$per_page.'" />
	<input type="hidden" class="PageCustomer_Page" value="'.$current_page.'" />';
	if($total_page > 1){
		$html .= '<div id="PagerCustomer" class="easyui-pagination" pageNumber="'.$current_page.'" 
		pageList="['.$per_page.',30,50,100]" ></div>';
	}
	$html_slick = $html_briefs = "";
	$field = "{$clsProperty->pkey},title,bgcolor";
	$limitCond = ($deviceType=='phone') ? " limit 0,4" : "";
	$list_status_array = $clsProperty->getAllCache("property_type='CUSTOMER_STATUS' and {$clsProperty->pkey}<>'"._CRM_STATUS_DONTCARE_ID."' order by order_no asc".$limitCond, $field);
	if(!empty($list_status_array)){
		foreach($list_status_array as $key => $val){
			$property_id = $val[$clsProperty->pkey];
			$total_customers = $clsCustomer->countItem($cnd." and `status_id`='{$property_id}'");
			$html_briefs.= '<div class="brief-item cursor-pointer m-0 overflow-hidden" onClick="$Core.crm.set_status(this, event)" status_id="'.$property_id.'" style="background:'.$val['bgcolor'].'">
				<p class="fs-14 text-white mb-2">'.$val['title'].'</p>
				<h3 class="fs-32 mb-0 text-white">'.$total_customers.'</h3>
			</div>';
		}
	}
	$list_date_ranges = array(
		array('id' => '_all', 'title' => 'Tất cả'),
		array('id' => '0-3', 'title' => '3 ngày trước'),
		array('id' => '4-7', 'title' => '4-7 ngày trước'),
		array('id' => '8-15', 'title' => '8-15 ngày trước'),
		array('id' => '16-30', 'title' => '16-30 ngày trước'),
		array('id' => '31-60', 'title' => '31-60 ngày trước'),
		array('id' => '61-120', 'title' => '61-120 ngày trước'),	
	);
	foreach($list_date_ranges as $key => $val){
		if($val['id'] == '_all'){
			$where = $cond;
		} else {
			$tmp = @explode('-', $val['id']);
			$end_day 	= (int) $tmp[1];
			$start_day 	= (int) $tmp[0];
			$start_date = strtotime("-{$end_day} days");
			if($start_day == 0){
				$end_date = $now;
			} else {
				$end_date = strtotime("-{$start_day} days");
			}
			$where = "{$cond} and (`reg_date` between {$start_date} and {$end_date})";
		}
		$total_customers = $clsCustomer->countItem($where);
		$list_date_ranges[$key]['total']= $total_customers;
	}
	$html_slick .= '<div class="slick">';
	foreach($list_date_ranges as $key => $_oR){
		$html_slick.= '<div class="px-1"><a data-toggle="ripple" rId="'.$_oR['id'].'" onClick="$Core.crm.set_date_range_search(this, event)" class="btn xs:fs-12 fs-14 px-lg-3 px-3 rounded-pill uqozsZBxSY btn-outline-default">'.$_oR['title'].'(<span class="text-main">'.$_oR['total'].'</span>)</a></div>';
	}
	$html_slick.= '</div>';
	// Output
	echo json_encode(array_merge($more, array(
		'html' => $html,
		// 'cond' => $cond,
		'html_slick' => $html_slick,
		'html_briefs' => $html_briefs,
		'from_notify' => $from_notify,
		'current_page'	=> $current_page,
		'total_assign' => $total_assign,
		'total_manage' => $total_manage,
		'total_page'	=> $total_page,
		'total_record'	=> $total_record,
		'per_page'	=> $per_page
	)), JSON_UNESCAPED_UNICODE); die();
}
function default_load_pop_action(){
	global $smarty,$core,$profile_id, $clsISO, $_LANG_ID;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$html = '<form class="p-2">
		<div class="form-group mb-2">
			<label class="form-label mb-1 text-nowrap">Tình trạng</label>
			<select name="status_id" class="form-control upd_field form-select">
				<option value="0">Tình trạng</option>
				'.$clsProperty->getSelectByProperty('CUSTOMER_STATUS', 0).'
			</select>
		</div>
		<div class="form-group form-row mb-2">
			<div class="col-6">
				<label class="form-label mb-1 text-nowrap">Loại hình</label>
				<select name="blocktype_id" class="form-control upd_field form-select">
					<option value="0">Loại hình</option>
					'.$clsProperty->getSelectByProperty('_BLOCK_TYPE', 0).'
				</select>
			</div>
			<div class="col-6">
				<label class="form-label mb-1 text-nowrap">Nguồn khách</label>
				<select name="resource_id" class="form-control upd_field form-select">
					<option value="0">Nguồn khách</option>
					'.$clsProperty->getSelectByProperty('_CUSTOMER_RESOURCES', 0).'
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-form-label">Người quản lý</label>
			<div class="clearfix"></div>
			<select class="iso-selectizeNotSearch upd_field" name="admin_id" data-width="100%" data-placeholder="Người quản lý" data-url="'.PCMS_URL.'/index.php?mod=home&act=list_staff" data-width="100%"></select>
		</div>
		<hr class="my-2" />
		<div class="alert alert-warning fs-12">
			<u>Lưu ý</u>: Khi nhấp áp dụng sẽ cập nhật những khách hàng đã chọn với field bên trên!
		</div>
		<button type="button" onClick="$Core.crm.do_action(this, event)" 
			class="btn btn-block btn-outline-primary">
			<span>Áp dụng</span>
		</button>
	</form>';
	// Return
	echo $html; die();
}
function default_do_action(){
	global $smarty, $core, $profile_id, $dbconn, $clsISO, $_LANG_ID;
	$clsStock = new Stock();
	$clsProfile = new Profile();
	$clsCountry = new Country();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsCampaign = new Campaign();
	$clsFcmToken = new FcmToken();
	
	$msg = "_error";
	$list_ids = Input::post('list_ids');
	$list_upd_field = Input::post('list_upd_field');
	if(!empty($list_ids) && !empty($list_upd_field)){
		foreach($list_ids as $customer_id){
			$s_field = 'admin_id';
			if(@array_key_exists($s_field, $list_upd_field) && !empty($list_upd_field[$s_field])){
				$admin_id = $list_upd_field[$s_field];
				$field = "admin_id,user_id,full_name,first_name,last_name,status_id";
				$field.= ",list_share_id,more_information";
				$oCustomer = $clsCustomer->getOne($customer_id, $field);
				$list_share_id = $oCustomer['list_share_id'];
				$list_share_arrs = !empty($list_share_id) 
					? $clsISO->getArrayByTextSlash($list_share_id) : array();
				$more_information = $oCustomer['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$list_logs = isset($more_information['logs']) && !empty($more_information['logs']) 
					? $more_information['logs'] : array();
				$list_logs[$clsISO->getUniqid()] = array(
					'_type' => 'assign',
					'to_id' => $admin_id,
					'from_id' => $oCustomer[$s_field],
					'status_id' => $oCustomer['status_id'],
					'reg_date' => time()
				);
				$more_information['logs'] = $list_logs;	
				$list_share_arrs[] = $oCustomer[$s_field];
				$use_globe = ($oCustomer['user_id'] == $admin_id) ? 0 : 1;
				$list_upd_field['use_globe'] = $use_globe;
				$list_upd_field['list_share_id'] = $clsISO->makeSlashListFromArray($list_share_arrs);
				$list_upd_field['more_information'] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
			}
			if($clsCustomer->updateOne($customer_id, $list_upd_field)){
				$msg = "_success";
				if(@array_key_exists($s_field, $list_upd_field)){
					$admin_id = $list_upd_field[$s_field];
					$titleNoty = sprintf('<strong>%s</strong> giao bạn phụ trách khách hàng <strong>%s</strong>', 
						$clsProfile->getFullName($profile_id, $oneProfile), 
						$clsCustomer->getName($customer_id, $oCustomer)
					);
					$clsNotify = new Notify();
					$clsNotify->insertNotify('Customer',$clsCustomer->pkey, $customer_id, $titleNoty, time(),"|".$admin_id."|");
					/** Push notification */
					$subscribers = array();
					$tmp = $clsFcmToken->getAll("`push_type`='_pushalert' 
						and `user_id`='{$admin_id}' and `token`<>''", "token");		
					if(!empty($tmp)){
						foreach($tmp as $key => $val){
							if(!in_array($val['token'], $subscribers)){
								$subscribers[] = $val['token'];
							}
						}
						$titleNoty = sprintf('Bạn được giao bạn giao <strong>%s</strong> khách hàng mới', count($list_ids));
						$clsNotify->send_subscriber_notification(array(
							'title' => "CRM - Khách hàng mới",
							'message' => strip_tags($titleNoty),
							'url' => PCMS_URL . '/crm/?keysearch='.implode(',',$list_ids)
						), $subscribers);
					}
				}
			}
		}
	}
	// Return
	// var_dump($list_ids); die();
	echo $msg; die();
}
function default_open_customer(){
	global $smarty,$core,$profile_id, $clsISO, $_LANG_ID;
	$clsStock = new Stock();
	$clsProfile = new Profile();
	$clsCountry = new Country();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	$clsCampaign = new Campaign();
	// $clsBusinessCampaign = new BusinessCampaign();
	$rollback = (int) Input::post('rollback',1);
	$customer_id = Input::post('customer_id');
	$oneCustomer = $clsCustomer->getOne($customer_id);
	$list_share_id = $oneCustomer['list_share_id'];
	$list_campaign_id  = $oneCustomer['list_campaign_id'];
	$more_information = $oneCustomer['more_information'];
	$arr_share_ids = !empty($list_share_id) 
		? $clsISO->getArrayByTextSlash($list_share_id) : "";
	$list_campaign_id = !empty($list_campaign_id) 
		? $clsISO->getArrayByTextSlash($list_campaign_id) : array();
	$oneCustomer['list_campaign_id'] = $list_campaign_id;
	$more_information = !empty($more_information) 
		? json_decode(html_entity_decode($more_information), true) : array();
	$list_type_id = $oneCustomer['list_type_id'];
	$list_need_id = $oneCustomer['list_need_id'];
	$list_stock_id = $oneCustomer['list_stock_id'];
	$list_purpose_id = $oneCustomer['list_purpose_id'];
	$list_bedroom_id = $oneCustomer['list_bedroom_id'];
	$list_block_id = $oneCustomer['list_block_id'];
	if(!empty($list_need_id)){
		$tmp = $clsISO->getArrayByTextSlash($list_need_id);
		$oneCustomer['list_need_id'] = $tmp;
	}
	if(!empty($list_purpose_id)){
		$tmp = $clsISO->getArrayByTextSlash($list_purpose_id);
		$oneCustomer['list_purpose_id'] = $tmp;
	}
	if(!empty($list_stock_id)){
		$tmp = $clsISO->getArrayByTextSlash($list_stock_id);
		$oneCustomer['list_stock_id'] = $tmp;
	}
	if(!empty($list_type_id)){
		$tmp = $clsISO->getArrayByTextSlash($list_type_id);
		$oneCustomer['list_type_id'] = $tmp;
	}
	if(!empty($list_bedroom_id)){
		$tmp = $clsISO->getArrayByTextSlash($list_bedroom_id);
		$oneCustomer['list_bedroom_id'] = $tmp;
	}
	if(!empty($list_block_id)){
		$tmp = $clsISO->getArrayByTextSlash($list_block_id);
		$oneCustomer['list_block_id'] = $tmp;
	}
	$country_id = $onePotential['country_id'];
	$props = 'customer_id="'.$customer_id.'" disp="item"';
	/** Permission */
	$permiss = $clsISO->checkPermission('full_permissions_crm') ? 1 : 0;
	if(!$permiss) $permiss = (in_array($profile_id, array($oneCustomer['user_id'],$oneCustomer['admin_id']))) ? 1 : 0;
	/** End Permission */
	$smarty->assign('customer_id', $customer_id);
	$smarty->assign('oneCustomer', $oneCustomer);
	$smarty->assign('more_information', $more_information);
	$smarty->assign('clsStock', $clsStock);
	$smarty->assign('clsCustomer', $clsCustomer);
	$smarty->assign('clsCampaign', $clsCampaign);
	###
	$permiss_action = $permiss_edit = $permiss_notes = 0;
	if($oneCustomer['admin_id']==$profile_id){
		$permiss_action = $permiss_edit = 1;
	}
	if(@in_array($profile_id, $arr_share_ids)){
		$permiss_notes = 1;
	}
	$smarty->assign('permiss_edit', $permiss_edit);
	$smarty->assign('permiss_notes', $permiss_notes);
	$smarty->assign('permiss_action', $permiss_action);
	#
	$uid = $clsISO->getUniqid();
	$smarty->assign('uid', $uid);
	$html = $core->build('_ajax.customer.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_edit_inline_field(){
	//ini_set('display_errors',1);
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$clsISO,$profile_id,$oneProfile;
	$clsCampaign = new Campaign();
	$clsCustomer = new Customer();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$clsStock = new Stock();
	###
	$html = $html_input = "";
	$p_id = (int) Input::post('p_id',0);
	$p_field = Input::post('p_field', "");
	$p_action = Input::post('p_action','_open');
	if($p_action=='_save'){
		$p_value = Input::post('p_value');
		//$clsISO->print_pre($p_value); die();
		if(in_array($p_field, array('twitter','facebook', 'linkedin','instagram','finance'))){
			$more_information = $clsCustomer->getOneField('more_information', $p_id);
			$more_information = !empty($more_information) 
				? json_decode(html_entity_decode($more_information), true) : array();
			$more_information[$p_field] = $p_value;
			$clsCustomer->updateOne($p_id, array(
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			));
		} else if(in_array($p_field, array(
			'list_purpose_id',
			'list_need_id',
			'list_stock_id',
			'list_type_id',
			'list_bedroom_id',
			'list_block_id',
			'list_campaign_id'
		))){
			$p_value = $clsISO->makeSlashListFromArray($p_value);
			$clsCustomer->updateOne($p_id, array(
				$p_field => $p_value,
				'upd_date' => time()
			));
		} else if($p_field=='birthday'){
			$p_value = !empty($p_value) 
				? $clsISO->convertTextToTime($p_value) : 0;
			$clsCustomer->updateOne($p_id, array(
				$p_field => $p_value,
				'upd_date' => time()
			));
		} else if($p_field=='admin_id'){
			$oCustomer = $clsCustomer->getOne($p_id, "{$p_field},name,user_id,status_id,more_information,list_share_id");
			$admin_old_id = $oCustomer[$p_field];
			$list_share_id = $oCustomer['list_share_id'];
			$more_information = $oCustomer['more_information']; 
			$more_information = !empty($more_information) 
				? json_decode(html_entity_decode($more_information), true) : array();
			$list_logs = isset($more_information['logs']) && !empty($more_information['logs']) 
				? $more_information['logs'] : array();
			$list_logs[$clsISO->getUniqid()] = array(
				'_type' => 'assign',
				'from_id' => $admin_old_id,
				'to_id' => $p_value,
				'status_id' => $oCustomer['status_id'],
				'reg_date' => time()
			);
			$more_information['logs'] = $list_logs;
			$list_share_id.= "|{$admin_old_id}|";
			$use_globe = ($oCustomer['user_id'] == $p_value) ? 0 : 1;
			if($clsCustomer->updateOne($p_id, array(
				$p_field => $p_value,
				'upd_date' => time(),
				'use_globe' => $use_globe,
				'list_share_id' => $list_share_id,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			))){
				$clsNotify = new Notify();
				$titleNoty = sprintf('<strong>%s</strong> giao bạn phụ trách khách hàng <strong>%s</strong>', 
					$clsProfile->getFullName($profile_id, $oneProfile), 
					$clsCustomer->getName($p_id, $oCustomer)
				);
				$clsNotify->insertNotify('Customer',$clsCustomer->pkey, $p_id, $titleNoty, time(),"|".$p_value."|");
			}
		} else if($p_field=='status_id'){
			$oCustomer = $clsCustomer->getOne($p_id, "{$p_field},status_id,more_information");
			$more_information = $oCustomer['more_information']; 
			$more_information = !empty($more_information) 
				? json_decode(html_entity_decode($more_information), true) : array();
			$list_logs = isset($more_information['logs']) && !empty($more_information['logs']) 
				? $more_information['logs'] : array();
			$list_logs[$clsISO->getUniqid()] = array(
				'from_id' => $profile_id,
				'_type' => 'upd_status',
				'from_status_id' => $oCustomer['status_id'],
				'status_id' => $p_value,
				'reg_date' => time()
			);
			$more_information['logs'] = $list_logs;
			$clsCustomer->updateOne($p_id, array(
				'upd_date' => time(),
				$p_field => $p_value,
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			));
		} else {
			$clsCustomer->updateOne($p_id, array(
				$p_field => $p_value,
				'upd_date' => time()
			));
		}
	}
	if(in_array($p_field, array('name','email','phone','address','CCID'))){
		$oCustomer = $clsCustomer->getOne($p_id, $p_field);
		// $clsISO->print_pre($oCustomer); die();
		if($p_action=='_cancel' || $p_action=='_save'){
			$html.='<div class="metadata-row-editable-triggerArea">'.$oCustomer[$p_field].'</div>
			<a class="metadata-row-editable-button editInlineField" onClick="$Core.crm.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="full_name" p_id="{$_profile_id}">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$html_input = '<input name="'.$p_field.'" class="form-control form-control-sm edit_customer_field_'.$p_field.'_'.$p_id.'" value="'.$oCustomer[$p_field].'" name="edit_profile_field_'.$p_field.'_'.$p_id.'" />';
		}
	} else if(in_array($p_field, array('admin_id'))){
		$oCustomer = $clsCustomer->getOne($p_id, $p_field);
		$p_value = $oCustomer[$p_field];
		// $clsISO->print_pre($oCustomer); die();
		if($p_action=='_cancel' || $p_action=='_save'){
			$html.='<div class="metadata-row-editable-triggerArea">
				'.$clsProfile->getIndentity($p_value, false).'
			</div>
			<a class="metadata-row-editable-button editInlineField" onClick="$Core.crm.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="full_name" p_id="{$_profile_id}">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$html_input = '<select name="'.$p_field.'" data-url="'.PCMS_URL.'/index.php?mod=home&act=list_staff" class="iso-selectizeNotSearch w-px-200 edit_customer_field_'.$p_field.'_'.$p_id.'" name="edit_profile_field_'.$p_field.'_'.$p_id.'">
				<option value="'.$p_value.'" selected="selected">
					'.$clsProfile->getIndentity($p_value, false).'
				</option>
			</select>';
		}
	} else if(in_array($p_field, array('birthday'))){
		$oCustomer = $clsCustomer->getOne($p_id, $p_field);
		// $clsISO->print_pre($oCustomer); die();
		if($p_action=='_cancel' || $p_action=='_save'){
			$p_value = $oCustomer[$p_field];
			$p_date = !empty($p_value) ? $clsISO->convertTimeToText($p_value) : "";
			$html.='<div class="metadata-row-editable-triggerArea">'.$p_date.'</div>
			<a class="metadata-row-editable-button editInlineField" onClick="$Core.crm.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="full_name" p_id="{$_profile_id}">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$p_value = $oCustomer[$p_field];
			$p_date = !empty($p_value) ? $clsISO->convertTimeToText($p_value) : "";
			$html_input = '<input name="'.$p_field.'" class="form-control datepicker form-control-sm edit_customer_field_'.$p_field.'_'.$p_id.'" placeholder="dd/mm/yy" autocomplete="off" value="'.$p_date.'" name="edit_profile_field_'.$p_field.'_'.$p_id.'" />
			<style type="text/css">.ui-datepicker{ z-index:9999 !important}</style>';
		}
	} else if($p_field=='begin_need'){
		$oCustomer = $clsCustomer->getOne($p_id, $p_field);
		// $clsISO->print_pre($oCustomer); die();
		if($p_action=='_cancel' || $p_action=='_save'){
			$html.='<div class="metadata-row-editable-triggerArea">'.$oCustomer[$p_field].'</div>
			<a class="metadata-row-editable-button editInlineField" onClick="$Core.crm.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="full_name" p_id="{$_profile_id}">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$html_input = '<textarea class="form-control form-control-sm edit_customer_field_'.$p_field.'_'.$p_id.'" name="edit_profile_field_'.$p_field.'_'.$p_id.'" />'.$oCustomer[$p_field].'</textarea>';
		}
	} else if(in_array($p_field, array('facebook', 'twitter', 'linkedin','instagram'))){
		$more_information = $clsCustomer->getOneField('more_information', $p_id);
		$more_information = !empty($more_information) 
			? json_decode(html_entity_decode($more_information), true) : array();
		if($p_action=='_cancel' || $p_action=='_save'){
			$html.= $more_information[$p_field];
			$html.='<div class="metadata-row-editable-triggerArea">'.$oCustomer[$p_field].'</div>
			<a class="metadata-row-editable-button editInlineField" onClick="$Core.crm.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="full_name" p_id="{$_profile_id}">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$html_input = '<input class="form-control form-control-sm edit_customer_field_'.$p_field.'_'.$p_id.'" value="'.$more_information[$p_field].'" name="edit_customer_field_'.$p_field.'_'.$p_id.'" />';
		}
	} else if(in_array($p_field, array('status_id','finance_id','resource_id','blocktype_id'))){
		$oCustomer = $clsCustomer->getOne($p_id, $p_field);
		if($p_action=='_cancel' || $p_action=='_save'){
			if(isset($oCustomer[$p_field]) && (int) $oCustomer[$p_field] > 0){
				$p_text = $clsProperty->getTitle($oCustomer[$p_field]);
			} else {
				$p_text = "--";
			}
			$html.='<div class="metadata-row-editable-triggerArea">'.$p_text.'</div>
			<a class="metadata-row-editable-button editInlineField" onClick="$Core.crm.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$label = 'Tình trạng';
			$property_type = 'CUSTOMER_STATUS';
			if($p_field=='type_id'){
				$label = 'Loại';
				$property_type = 'CUSTOMER_TYPE';
			} else if($p_field=='finance_id'){
				$label = 'Tài chính';
				$property_type = 'FINANCE';
			} else if($p_field=='resource_id'){
				$label = 'Nguồn gốc';
				$property_type = '_CUSTOMER_RESOURCES';
			} else if($p_field=='blocktype_id'){
				$label = 'Loại hình';
				$property_type = '_BLOCK_TYPE';
			}
			$html_input = '<select class="form-control form-select form-control-sm edit_customer_field_'.$p_field.'_'.$p_id.'" name="edit_profile_field_'.$p_field.'_'.$p_id.'" />
				'.$clsISO->getSelectByPropertyTypeTitle($property_type,$oCustomer['status_id'],$label).'
			</select>';
		}
	} else if(in_array($p_field, array('list_need_id','list_purpose_id','list_type_id'
	,'list_bedroom_id','list_block_id'))){
		$oProfile = $clsCustomer->getOne($p_id, $p_field);
		if($p_action=='_cancel' || $p_action=='_save'){
			$p_text = "";
			if(isset($oProfile[$p_field]) && !empty($oProfile[$p_field])){
				$p_data = $oProfile[$p_field];
				$p_array = $clsISO->getArrayByTextSlash($p_data);
				$p_text.= $clsProperty->getTitleArray($p_array);
			} else {
				$p_text.= "--";
			}
			$html.='<div class="metadata-row-editable-triggerArea">'.$p_text.'</div>
			<a class="metadata-row-editable-button editInlineField" onClick="$Core.crm.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			if($p_field=='list_purpose_id'){
				$label = 'Mục đích';
				$property_type = 'PURPOSE';
			} else if($p_field=='list_need_id'){
				$label = 'Nhu cầu';
				$property_type = 'NEED';
			} else if($p_field=='list_type_id'){
				$label = 'Loại khách hàng';
				$property_type = 'CUSTOMER_TYPE';
			} else if($p_field=='list_bedroom_id'){
				$label = 'Phòng ngủ';
				$property_type = '_BEDROOM';
			} else if($p_field=='list_block_id'){
				$label = 'Phân khu';
				$property_type = '_BLOCK';
			}
			$p_data = $clsCustomer->getOneField($p_field, $p_id);
			$p_array = $clsISO->getArrayByTextSlash($p_data);
			$html_input = '<select data-placeholder="'.$label.'" data-allow-clear="true" multiple="multiple" class="form-control iso-select2 edit_customer_field_'.$p_field.'_'.$p_id.'" name="edit_profile_field_'.$p_field.'_'.$p_id.'[]" />
				'.$clsProperty->getSelectByPropertyV2($property_type,$p_array,'Phòng ban').'
			</select>';
		}
	} else if($p_field == 'list_campaign_id'){
		$uid = $clsISO->getUniqid();
		$oCustomer = $clsCustomer->getOne($p_id, $p_field);
		if($p_action=='_cancel' || $p_action=='_save'){
			$p_text = "";
			if(!empty($oCustomer[$p_field])){
				$p_data = $oCustomer[$p_field];
				$p_array = $clsISO->getArrayByTextSlash($p_data);
				$p_text = $clsCampaign->getTitleArray($p_array);
			}
			$html.='<div class="metadata-row-editable-triggerArea">'.$p_text.'</div>
			<a class="metadata-row-editable-button editInlineField"  onClick="$Core.crm.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$p_array = array();
			if(!empty($oCustomer[$p_field])){
				$p_data = $oCustomer[$p_field];
				$p_array = $clsISO->getArrayByTextSlash($p_data);
			}
			$html_input = '<div class="input-group align-items-center" style="width:calc(100% - 62px)">
				<div class="select2-container no-border-right" style="width:calc(100% - 40px)">
					<select multiple="multiple" id="slb_Campaign_'.$uid.'" data-width="100%" data-placeholder="Chọn chiến dịch" class="form-control iso-select2 edit_customer_field_'.$p_field.'_'.$p_id.'" name="edit_profile_field_'.$p_field.'_'.$p_id.'[]">';
					$field = "{$clsCampaign->pkey},title";
					$list_campaigns = $clsCampaign->getAll("`campaign_type`='_campaign' 
						and `user_id`='{$profile_id}' order by `reg_date` DESC", $field);
					if(!empty($list_campaigns)){
						foreach($list_campaigns as $key => $val){
							$html_input.= '<option'.(in_array($val[$clsCampaign->pkey],$p_array)?' selected':'').' value="'.$val[$clsCampaign->pkey].'">'.$val['title'].'</option>';
						}
					}
					$html_input .= '</select>
				</div>
				<button type="button" toId="'.$uid.'" onClick="$Core.crm.open_campaign(this, event)" 
				class="btn btn-icon btn-outline-default">'.$clsISO->makeIcon('bx-plus').'</button>
			</div>';
		}
	} else if($p_field=='list_stock_id'){
		$oProfile = $clsCustomer->getOne($p_id, $p_field);
		if($p_action=='_cancel' || $p_action=='_save'){
			$p_text = "";
			if(isset($oProfile[$p_field]) && !empty($oProfile[$p_field])){
				$p_data = $oProfile[$p_field];
				$p_array = $clsISO->getArrayByTextSlash($p_data);
				$p_text.= $clsStock->getTitleArray($p_array);
			} else {
				$p_text.= "--";
			}
			$html.='<div class="metadata-row-editable-triggerArea">'.$p_text.'</div>
			<a class="metadata-row-editable-button editInlineField" onClick="$Core.crm.editInlineField(this,{\'p_field\':\''.$p_field.'\', \'p_id\':\''.$p_id.'\'})" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$clsISO->makeIcon('bx-pencil').'</a>';
		} else {
			$p_data = $clsCustomer->getOneField($p_field, $p_id);
			$p_array = $clsISO->getArrayByTextSlash($p_data);
			$html_input = '<select multiple="multiple" class="form-control iso-selectizeLiveSearch edit_customer_field_'.$p_field.'_'.$p_id.'" data-url="'.PCMS_URL.'/index.php?mod=home&act=load_stock_search" data-optgroup="false" name="edit_profile_field_'.$p_field.'_'.$p_id.'[]">';
				if(!empty($p_array)){
					foreach($p_array as $stock_id){
						$html_input.= '<option value="'.$stock_id.'" selected="selected">
							'.$clsStock->getMsCode($stock_id).'</option>';
					}
				}
			$html_input .= '</select>';
		}
	}
	if($p_action=='_cancel' || $p_action=='_save'){
		echo $html; die();
	} else {
		$html = '<div class="d-flex input-group inline-editor-container">
			'.$html_input.'
			<div class="btn-group">
				<button class="btn px-2 rounded-0 btm-sm btn-outline-success" onClick="$Core.crm.save_edit_field(this, event)" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$core->makeIcon('check').'</button>
				<button class="btn px-2 btm-sm btn-outline-danger" onClick="$Core.crm.cancel_edit_field(this, event)" p_field="'.$p_field.'" p_id="'.$p_id.'">'.$core->makeIcon('undo').'</button>
			</div>
		</div>';
	}
	// Return
	echo $html; die();
}
function default_load_emailtemplate_editor(){
	global $core,$adminid,$clsISO,$clsConfiguration,$datastore_folder;
	$clsEmailTemplate = new EmailTemplate();
	$template_id = Input::post('template_id', 0, true);
	
	$emailcontent = array('subject' => "", 'msg' => "");
	if(intval($template_id) > 0){
		$emailcontent = array(
			'subject'	=> $clsEmailTemplate->getValueField('subject', $template_id),
			'msg'	=> $clsEmailTemplate->getValueField('msg', $template_id)
		);
	}
	// Output
	echo json_encode($emailcontent);
	die();
}
function default_doSendCompanyEmail(){
	global $core,$adminid,$clsISO,$clsConfiguration,$datastore_folder;
	$clsVS_Admin = new VS_Admin();
	$clsPotential = new Potential();
	$clsCRMEmailLog = new CRMEmailLog();
	$clsEmailTemplate = new EmailTemplate();
	#
	$from = Input::post('from');
	$to = Input::post('to');
	$template = (int) Input::post('template');
	$potential_id = Input::post('potential_id',0);
	$schedule = Input::post('schedule',0,true);
	$scheduled_date = Input::post('scheduled_date', date('d/m,Y'), true);
	$scheduled_time = Input::post('scheduled_time', date('H:i A'), true);
	$scheduled_date = $clsISO->convertTextToTime($scheduled_date, $scheduled_time);
	$track_clicks = Input::post('track_clicks',0);
	if($from=='_system' || $from=='_admin_'.$adminid){
		if($from=='_system'){
			$fromemail = $clsConfiguration->getValue('email_from_email');
			$fromName = $clsConfiguration->getValue('mail_from_name');
		}else{
			$fromemail = $core->_USER['email'];
			$fromname = $clsVS_Admin->getFullName($adminid);
		}
	}
	if(empty($fromName)) $fromName = PAGE_NAME;
	#
	$subject = Input::post('subject');
	$content = Input::post('content');
	$attachments = '';
	if(is_uploaded_file($_FILES['attachments']['tmp_name'])){
		$clsUploadFile = new UploadFile();
		$attachments = ABSPATH.$clsUploadFile->uploadItem($_FILES["attachments"],"/attachments",EXTENSION_FILE_UPLOAD);
	}
	#
	$msg = '_error';
	$email_log_id = $clsCRMEmailLog->getMaxId();
	if($a = $clsCRMEmailLog->insert(array(
		'id'	=> $email_log_id,
		'potential_id'	=> $potential_id,
		'from_type'	=> $from,
		'fromemail'	=> $fromemail,
		'toemail'	=> $to,
		'template'	=> $template,
		'subject'	=> $subject,
		'content'	=> html_entity_decode($content),
		'attachments'	=> str_replace(ABSPATH,'',$attachments),
		'schedule' => $schedule,
		'scheduled_date' => $scheduled_date,
		'scheduled_time' => $scheduled_time,
		'track_clicks' => $track_clicks,
		'reg_date'	=> time(),
		'upd_date'	=> time(),
		'user_id'	=> $adminid,
		'user_id_update'	=> $adminid
	))){
		$msg = '_success';
		// Logs
		$clsISO->logs($clsPotential->tbl, $clsPotential->pkey, $potential_id, "New sent email has been sended:".$subject);
		// End Logs
		if($schedule==0){
			if($from=='_system' || $from=='_admin_'.$adminid){
				$oneMsg = $clsISO->getMessageGolbal();
				$header_email = '<img style="max-width:100%" src="https://marketing-image-production.s3.amazonaws.com/uploads/b3635423c2a11a45ae3e254b36b33bdf037af686bc53d27d914927736d4dbd16256f199befb7c430cad6a63493d4721b4100857d7c9e73529aba63e7427e59bd.png" />';
				$footer_email = '<table style="padding: 40px 5px 30px 5px; background-color: #ededed; box-sizing: border-box;" border="0" cellspacing="0" cellpadding="0" width="100%" align="center" bgcolor="#ededed">
					<tbody>
					  <tr>
						<td height="100%" valign="top"><div style="font-size: 12px; line-height: 150%; margin: 0; text-align: center;"><span style="background-color: #ededed;"><a href="https://www.facebook.com/vietiso"><img style="color: #5f6368; font-family: arial, helvetica, sans-serif; font-size: 14px; background-color: #ffffff; border-width: medium; border-style: none; width: 25px; height: 25px; max-width: 25px; max-height: 25px;" src="https://ci5.googleusercontent.com/proxy/JJbnBzceHGWKcWR0-_fVi_eJSjDU_c0iCZpm086ne7ey07RZk_mrNYYvr4qGRivlBAPrgBibymxWI-_rXsWogX0o7Nyx4LDz0_MARFJP9Jqr3r8p3VtmifGszER_pinXMA=s0-d-e1-ft#http://cdn2.hubspot.net/hubfs/184235/dev_images/signature_app/facebook_sig.png" alt="" width="25" height="25" /></a><span style="color: #5f6368; font-family: arial, helvetica, sans-serif; font-size: 14px;">&nbsp; &nbsp;&nbsp;</span></span><strong style="font-size: 12px; color: #8189a9;"><a style="color: #1da1db; font-family: arial, helvetica, sans-serif; font-weight: 400; background-color: #f4f5fb; font-size: x-small; border: 0px none;" href="https://twitter.com/vietiso" target="_blank"><img  style="border-width: medium; border-style: none; width: 25px; height: 25px; max-width: 25px; max-height: 25px;" src="https://ci4.googleusercontent.com/proxy/ejXA88S96Jew3TBeZGRD6r_bTlw-Hx7GK74wwTnU5ACOpZGf7pGtKPEpAuQeu5p-vALo6lQwujV8iLFBJCZ77eGxAngQOH2JuJm_9UdoB7wqy70KxBt8_WwP0drAFO-e=s0-d-e1-ft#http://cdn2.hubspot.net/hubfs/184235/dev_images/signature_app/twitter_sig.png" alt="" width="25" height="25" /></a><span style="color: #5f6368; font-family: arial, helvetica, sans-serif; font-size: 14px; font-weight: 400;">&nbsp; &nbsp;&nbsp;</span><span style="color: #1da1db; font-family: arial, helvetica, sans-serif; font-size: xx-small;"><span style="font-weight: 400; background-color: #f4f5fb; border-width: medium; border-color: initial; border-image: initial;"><a href="https://www.linkedin.com/company/vietiso/"><img style="border-width: medium; border-style: none; width: 25px; height: 25px; max-width: 25px; max-height: 25px;" src="https://ci4.googleusercontent.com/proxy/fjaHy7uY9R0XNUH2_bP0W5xTjh_4uej3SHXEMXAcPgr4Vk1NpXbyL8onqdpx2_uANo_5HSzsvNsuhnUyxWjMCnwPZXbtfPEUDTsOnQG_aXE-HLS_ulCw8WuRT-pOT84K6w=s0-d-e1-ft#http://cdn2.hubspot.net/hubfs/184235/dev_images/signature_app/linkedin_sig.png" alt="" width="25" height="25" /></a></span></span>&nbsp;</strong></div>
						  <div style="font-size: 12px; line-height: 150%; margin: 0; text-align: center;"><span style="color: #8189a9;"><span style="font-size: 12px;"><strong> </strong></span></span></div>
						  <div style="font-size: 12px; line-height: 150%; margin: 0; text-align: center;"><span style="color: #8189a9;"><span style="font-size: 12px;"><strong>Công ty Cổ phần VietISO</strong></span></span></div>
						  <div style="font-size: 12px; line-height: 150%; margin: 0; text-align: center;"><span style="color: #8189a9;">18th Floor, VTC Online Tower, 18 Tam Trinh Str., Hai Ba Trung Dist, Hanoi, Vietnam</span></div>
						  <div style="font-size: 12px; line-height: 150%; margin: 0; text-align: center;"><span style="color: #8189a9;"><span style="color: #8189a9;"><span style="font-size: 12px;"><span style="color: #0066cc;"><a href="https://www.vietiso.com/contact">Liên hệ</a></span>&nbsp;|&nbsp;<span style="color: #0066cc;"><a href="https://www.vietiso.com/ho-tro/dieu-khoan-huong-dan/chinh-sach-bao-mat.html">Chính sách</a></span>&nbsp;|&nbsp;<span style="color: #0066cc;"><a href="https://www.vietiso.com/ho-tro">Hỗ trợ</a></span></span></span></span></div></td>
					  </tr>
					</tbody>
				  </table>';
				$merge_fields = array(
					'[%brand_header_email%]' => $header_email,
					'[%tem_msg%]' => $content,
					'[%brand_footer_email%]' => $footer_email
				);
				foreach($merge_fields as $k => $v){
					$oneMsg = str_replace($k, $v, $oneMsg);
				}
				$clsISO->sendEmail($fromemail, $to, $subject, $oneMsg, $fromName, $attachments);
			}else {
				$clsCRMMailbox = new CRMMailbox();
				$clsCRMMailbox->sendEmail($from, $to, "", $subject, $content, $fromname, $attachments);
			}
		}
	}
	// Output
	echo '0|||'.$msg; die();
}
function form_follow_ups($potential_id, $crm_followup_id=0, $permiss=1, $holderG="potential"){
	global $core, $dbconn, $clsISO, $clsConfiguration, $adminid;
	$clsPotential = new Potential();
	$clsFollowUp = new FollowUp();
	
	$action = '_add';
	$oneItem = array(
		'admin_id'	=> $adminid,
		'date_id'	=> time(),
		'time'		=> date('h:i A'),
		'time_unit' => '_minute',
		'type_id'	=> $clsConfiguration->getValue('CRM_FollowUpTypeDefault'),
		'content'	=> '',
		'upd_reminder' => 0,
		'is_upsale'	=> 0,
		'accounting_notes' => ""
	);
	if($crm_followup_id > 0){
		$action = '_edit';
		$oneItem = $clsFollowUp->getOne($crm_followup_id);
	}
	$props = $clsISO->make_attrs_builder(array(
		'action' => $action,
		'holderG' => $holderG,
		'resource_id' => $potential_id,
		'crm_followup_id' => $crm_followup_id
	));
	return '<form class="form-horizontal" method="post" action="" id="frmFollowUp__'.$potential_id.'">
		<div class="box-body">
			<div class="form-group">
				<div class="col-md-6">
					<label class="col-md-4 col-form-label text-right">'.__('Admin').'</label>
					<div class="col-md-8">
						<select class="form-control required iso-selectbox '.($permiss==0?' disabled':'').'" name="admin_id" data-width="100%">
							'.CRM::getSelectUserOptionsInCRM($oneItem['admin_id']).'
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<label class="col-md-4 col-form-label text-right">'.__('Date').'</label>
					<div class="col-md-8">
						<input type="text" class="form-control datepicker pull-left mr10" value="'.$clsISO->convertTimeToText($oneItem['date_id']).'" name="date_id" readonly />
						<input type="text" class="form-control timepicker" name="time" value="'.date('H:s', $oneItem['date_id']).'" />
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-6">
					<label class="col-md-4 col-form-label text-right">'.__('Type').'</label>
					<div class="col-md-8">
						<select class="form-control iso-selectbox" name="type_id" data-width="100%">
							'.$clsISO->getSelectByPropertyTypeNotTitle('_FOLLOWUP_TYPE',$oneItem['type_id']).'
						</select>
						'.($action=='_edit'?'<div class="mt-ten">
							<label class="switch small pull-left">
								<input type="checkbox" name="upd_reminder" value="1" '.($oneItem['upd_reminder']?'checked':'').'>
								<span class="slider round"></span>
							</label>
							<span class="help-block">&nbsp;'.__('Update Reminders').'</span>
						</div>':'').'
						'.($oneItem['is_transfer']?'<div class="mt-ten">
							<label class="switch small pull-left">
								<input type="checkbox" class="chk_followups_status_transfer_'.$crm_followup_id.'" onChange="ejs_followups_changed_status_transfer(this);" value="1" '.($oneItem['is_transfer']?'checked':'').'>
								<span class="slider round"></span>
							</label>
							<span class="help-block">&nbsp;'.__('TransferAccountant').'</span>
							<input type="hidden" value="'.$oneItem['is_transfer'].'" name="upd_transfer_status" />
						</div>':'').'
					</div>
				</div>
				<div class="col-md-6">
					<div class="col-md-4 text-right"><h5>'.__('Description').'</h5></div>
					<div class="col-md-8">
						<textarea class="form-control required" name="content" rows="'.($action=='_add'?2:3).'" placeholder="'.__('Some notes').'">'.$oneItem['content'].'</textarea>
					</div>
				</div>
			</div>
			'.($oneItem['is_upsale']?'
			<div class="form-group">
				<div class="col-md-6">
					<label class="col-md-4 col-form-label text-right">'.__('Type').'</label>
					<div class="col-md-8">
						<label class="label-checkbox-standard pull-left">
							<input type="checkbox" name="is_upsale" onChange="ejs_change_upsale(this)" checked="checked" disabled value="1" /> '.__("TransferAccountant").'
						</label>
					</div>
				</div>
				<div class="col-md-6">
					<div class="col-md-4 text-right"><h5>'.__('AccountingNotes').'</h5></div>
					<div class="col-md-8">
						<textarea class="form-control required" name="accounting_notes" rows="3" placeholder="'.__('AccountingNotes').'">'.$oneItem['accounting_notes'].'</textarea>
					</div>
				</div>
			</div>':'').'
		</div>
		<div class="box-end text-center">
			<button class="btn btn-success" onclick="ejs_save_followups(this); return false;" '.$props.'>
				<span>'.$core->makeIcon('check',__($tp=='_add'?'AddFollowUp':'Update')).'</span>
			</button>
		</div>
	</form>';
}
function default_aj_save_followup_crm(){
	global $core,$adminid,$clsISO,$clsConfiguration;
	$clsProperty = new Property();
	$clsVS_Admin = new VS_Admin();
	$clsPotential = new Potential();
	$clsFollowUp = new FollowUp();
	$clsEmailTemplate = new EmailTemplate();
	$clsReminder = new Reminder();
	$clsVS_ServiceStatus = new VS_ServiceStatus();
	
	$holderG = Input::post('holderG','potential');
	$pval_id = (int) Input::post('pval_id',0); /** Service_ID */
	$resource_id = (int) Input::post('resource_id',0);
	$crm_followup_id = intval(Input::post('crm_followup_id',0));
	/** Delete */
	if(Input::exists('action','GET') && Input::get('action')=='_delete'){
		// List User Share
		if($holderG=='potential' || 1){
			$list_user_share_id = $clsPotential->getOneField('list_user_share_id', $resource_id);
			$list_user_share_array = !empty($list_user_share_id) ? $clsISO->getArrayByTextSlash($list_user_share_id) : array();
			$old_admin_id = $clsFollowUp->getOneField('admin_id', $crm_followup_id);
			$user_create_id = $clsFollowUp->getOneField('user_id', $crm_followup_id);
			if($old_admin_id != $user_create_id){
				if(!empty($list_user_share_array) && in_array($old_admin_id, $list_user_share_array)){
					$list_user_share_array = array_diff($list_user_share_array, array($old_admin_id));
					$list_user_share_array = !empty($list_user_share_array) ? array_values($list_user_share_array) : array();
				}
				$clsPotential->updateOne($resource_id, array(
					'list_user_share_id'	=> $clsISO->makeSlashListFromArray($list_user_share_array)
				));
			}
		}
		// Logs
		$log_message = "Follow-up #".$crm_followup_id." has been deleted '".$clsFollowUp->getOneField('content',$crm_followup_id)."'";
		$clsISO->logs($clsFollowUp->tbl, $clsFollowUp->pkey, $crm_followup_id, $log_message,'CRM');
		// End logs
		$service_id = $clsFollowUp->getOneField('service_id', $crm_followup_id);
		$clsReminder->deleteByCond("crm_followup_id={$crm_followup_id}");
		$clsFollowUp->deleteOne($crm_followup_id);
		//echo(1); die();
		echo json_encode(array(
			"msg"	=> "ok",
			"service_id"	=> $service_id
		));die;
		/** End Delete */
	} else {
		$admin_id = Input::post('admin_id',$adminid,true);
		$date_id = Input::post('date_id');
		$time = Input::post('time');
		if(empty($time)) $time = "00:00";
		$datetime = $clsISO->convertTextToTime($date_id, $time);
		$upd_reminder = Input::post('upd_reminder',0);
		
		$is_transfer = Input::post('is_transfer',0);
		$is_upsale = Input::post('is_upsale',0);
		$mod_page = Input::post('mod_page', 'crm');
		$act_page = Input::post('act_page', 'crm');
		$accounting_notes = Input::post('accounting_notes');
		if($holderG=='potential' || 1){
			/** List User Share */
			$list_user_share_id = $clsPotential->getOneField('list_user_share_id', $resource_id);
			$list_user_share_array = !empty($list_user_share_id) 
				? $clsISO->getArrayByTextSlash($list_user_share_id) 
				: array();
		}
		/** Config Reminder */
		$reminders_config = $clsPotential->GetOneField('reminders_config',$resource_id);
		$reminders_config_array = !empty($reminders_config) ? json_decode($reminders_config, true) : array();
		/** End */
		if($crm_followup_id > 0){
			$datetime_old = (int) $clsFollowUp->getOneField('date_id',$crm_followup_id);
			if($clsFollowUp->updateOne($crm_followup_id, array(
				'admin_id'	=> $admin_id,
				'date_id'	=> $datetime,
				'time'		=> $time,
				'type_id'	=> Input::post('type_id'),
				'content'	=> Input::post('content'),
				'accounting_notes' => $accounting_notes,
				'upd_reminder'	=> $upd_reminder,
				'upd_date'	=> time(),
				'user_id_update'	=> $adminid
			))){
				if($admin_id != $adminid){
					if($holderG=='potential' || 1){
						$old_admin_id = $clsFollowUp->getOneField('admin_id', $crm_followup_id);
						$totalFollowUpAssignAdmin = $clsFollowUp->countItem("admin_id='{$old_admin_id}' and crm_followup_id<>'{$crm_followup_id}'");
						if(!empty($list_user_share_array) && in_array($old_admin_id, $list_user_share_array) && $totalFollowUpAssignAdmin==0){
							$list_user_share_array = array_diff($list_user_share_array, array($old_admin_id));
							$list_user_share_array = !empty($list_user_share_array) ? array_values($list_user_share_array) : array();
						}
						if(!in_array($admin_id, $list_user_share_array)){
							$list_user_share_array[] = $admin_id;
						}
						$clsPotential->updateOne($resource_id, array(
							'list_user_share_id'	=> $clsISO->makeSlashListFromArray($list_user_share_array)
						));
					}
				}
				// Logs
				$log_message = "Follow-up #".$crm_followup_id." has been updated to '".Input::post('content')."'";
				$clsISO->logs($clsFollowUp->tbl, $clsFollowUp->pkey, $crm_followup_id, $log_message,'CRM');
				// End
				if($upd_reminder==1){// Caulator range_date
					$timerange = 0;
					if($datetime_old > $datetime){
						$operator  = '-';
						$timerange = $datetime_old - $datetime;
					}else{
						$operator = '+';
						$timerange = $datetime - $datetime_old;
					}
					if($timerange >= 0){ // Less than 1 day
						$lstReminder = $clsReminder->GetAll("crm_followup_id='{$crm_followup_id}' order by date_id ASC");
						if(!empty($lstReminder)){
							foreach($lstReminder as $reminder){
								$date_in = $reminder['date_id'];
								if($operator=='+'){
									$date_in += $timerange;
								}else{
									$date_in -= $timerange;
								}
								$time = date('H:i A', $date_in);
								$clsReminder->updateOne( $reminder[$Reminder->pkey], array(
									'date_id' => $date_in,
									'time'	=> $time
								));
							}
						}
						unset($lstReminder);
					}
				}
			}
			if(Input::exists('upd_transfer_status', 'POST')){
				$upd_transfer_status = (int) Input::post('upd_transfer_status',1);
				if(!$upd_transfer_status){
					$clsFollowUp->updateOne($crm_followup_id, array('is_transfer'	=> 0));
					$clsVS_ServiceStatus->deleteByCond("tp='{$holderG}' and status='transfer' and pval_id='{$pval_id}'");	
				}
			}
		}else{
			$crm_followup_id = $clsFollowUp->getMaxId();
			if($clsFollowUp->insert(array(
				'crm_followup_id'	=> $crm_followup_id,
				'holderG' => $holderG,
				'resource_id'	=> $resource_id,
				'service_id'	=> $pval_id,
				'admin_id'	=> $admin_id,
				'date_id'	=> $datetime,
				'time'		=> $time,
				'mod_page' => $mod_page,
				'act_page' => $act_page,
				'type_id'	=> Input::post('type_id'),
				'content'	=> Input::post('content'),
				'is_transfer' => $is_transfer,
				'is_upsale'	=> $is_upsale,
				'accounting_notes' => $accounting_notes,
				'reg_date'	=> time(),
				'upd_date'	=> time(),
				'user_id'	=> $adminid,
				'user_id_update'	=> $adminid
			))){
				if($adminid != $admin_id){
					if($holderG=='potential' || 1){
						if(!in_array($admin_id, $list_user_share_array)){
							$list_user_share_array[] = $admin_id;
						}
						$clsPotential->updateOne($resource_id, array(
							'list_user_share_id'	=> $clsISO->makeSlashListFromArray($list_user_share_array)
						));
					}
				}
				/** Send emai if reminder config setup */
				$getMessageGolbal = $clsISO->getMessageGolbal();
				if($holderG=='potential' || 1){
					if(CRM::getValueFieldInArray($reminders_config_array,'admin_create_status',0)==1 
					   && CRM::getValueFieldInArray($reminders_config_array,'admin_create_status_email',0)==1){
						/** Template */
						$admin_create_template = CRM::getValueFieldInArray($reminders_config_array,'admin_create_template',0);
						$subject = $clsEmailTemplate->getValueField('subject', $admin_create_template);
						$message = $clsEmailTemplate->getValueField('message', $admin_create_template);
						$c_header = $clsEmailTemplate->getValueField('c_header', $admin_create_template);
						$c_footer = $clsEmailTemplate->getValueField('c_footer', $admin_create_template);
						/** From Sender */
						$fromemail = $clsEmailTemplate->getValueField('fromemail', $admin_create_template);
						if(!$fromemail) $fromemail = $clsConfiguration->getValue('mail_from_name');
						$fromname = $clsEmailTemplate->getValueField('fromname', $admin_create_template);
						if(!$fromname) $fromname = $clsConfiguration->getValue('email_from_email');
						/** EmailTo */
						$admin_create_admin_id = CRM::getValueFieldInArray($reminders_config_array,'admin_create_admin_id',0);
						$toemail = $clsVS_Admin->getEmail($admin_create_admin_id);
						
						/** Replace Field */
						$replaceField = array(
							'|user_create_name|' => $clsVS_Admin->getFullName($adminid),
							'|followup_admin|' => $clsVS_Admin->getFullName($admin_create_admin_id),
							'|followup_type|' => $clsProperty->getTitle(Input::post('type_id')),
							'|followup_time|' => $clsISO->convertTimeToText($datetime, true),
							'|contact_name|' => $clsPotential->getName($resource_id),
							'|followup_content|' => Input::post('content'),
							'|PCMS_URL|' => PAGE_NAME,
						);
						foreach($replaceField as $search => $replace){
							$subject = str_replace($search, $replace, $subject);
							$message = str_replace($search, $replace, $message);
						}
						$merge_fields = array(
							'[%brand_header_email%]' => $c_header,
							'[%tem_msg%]' => $message,
							'[%brand_footer_email%]' => $c_footer
						);
						$oneMsg = $getMessageGolbal;
						foreach($merge_fields as $k => $v){
							$oneMsg = str_replace($k, $v, $oneMsg);
						}
						// CC
						$cc = null;
						$admin_create_cc = CRM::getValueFieldInArray($reminders_config_array,'admin_create_cc',array());
						if(!empty($admin_create_cc)){
							$cc = array();
							foreach($admin_create_cc as $adm){
								$cc[] = $clsVS_Admin->getEmail($adm);
							}
						}
						// Send
						$is_sent_email = $clsISO->sendEmail($fromemail, $toemail, $subject, $oneMsg, $fromname,"", false, $cc);
					}
				}
				if(CRM::getValueFieldInArray($reminders_config_array,'client_create_status',0)==1 
				   && CRM::getValueFieldInArray($reminders_config_array,'client_create_status_email',0)){
				}
				if($is_transfer){
					$info_service = Input::post('info_service');
					$info_domain = Input::post('info_domain');
					$info_identity = Input::post('info_identity');
					$message = '<p>Có một dịch vụ bảo trì được chuyển kế toán</p>';
					if(!empty($info_service))
						$message .= '<p>'.$info_service.'</p>';
					if(!empty($info_domain))
						$message .= '<p>'.$info_domain.'</p>';
					if(!empty($info_identity))
						$message .= '<p>'.$info_identity.'</p>';
					#
					$merge_fields = array(
						'[%brand_header_email%]' => "",
						'[%tem_msg%]' => $message,
						'[%brand_footer_email%]' => ""
					);
					$oneMsg = $getMessageGolbal;
					foreach($merge_fields as $k => $v){
						$oneMsg = str_replace($k, $v, $oneMsg);
					}
					$is_sent_email = $clsISO->sendEmail($clsVS_Admin->getEmail($adminid),'acc@vietiso.com','Thông báo thu công nợ dịch vụ bảo trì',$oneMsg,'okrs.vietiso.com','',false);//,array('huytech@vietiso.com')
				}
				// Logs
				$log_message = "New follow-up has been added: '".Input::post('content')."'";
				$clsISO->logs($clsFollowUp->tbl, $clsFollowUp->pkey, $crm_followup_id, $log_message,'CRM');
			}
		}
		//echo($crm_followup_id); die();
		echo json_encode(array(
			"msg"	=> "ok",
			"service_id"	=> $pval_id,
			"crm_followup_id"	=> $crm_followup_id
		));die;
	}
}
function default_ajLoadListReminderCRM(){
	global $core,$adminid, $clsISO, $_LANG_ID;
	if($clsISO->validateLoginInformation()==0){echo('');die();}
	$clsReminder = new Reminder();
	
	$action = Input::post('action','_edit');
	$resource_id = Input::post('resource_id');
	$crm_followup_id = Input::post('crm_followup_id');
	$sortby = Input::post('sortby','date_id');
	$sorttype = Input::post('sorttype','desc');
	#
	$html = '<table class="table table-hover table-striped table-responsive" cellpadding="0" cellspacing="0" width="100%">
	<thead><tr>
		'.($action=='_edit'?'<th width="5%">No.</th>':'').'
		<th class="sorthandler '.($sortby=='date_id'?'bs-sort-'.$sorttype:'').'" column="date_id"">'.__('Date').'</th>
		<th class="sorthandler '.($sortby=='method'?'bs-sort-'.$sorttype:'').'" column="method" width="10%">'.__('Method').'</th>
		<th class="sorthandler '.($sortby=='status'?'bs-sort-'.$sorttype:'').'" column="status" width="10%">'.__('Status').'</th>
		'.($action=='_edit'?'<th>'.__('Template').'</th>':'').'
		<th>'.__('Remind').'</th>
		'.($action=='_edit'?'<th class="text-center" width="8%">'.__('_Actions').'</th>':'').'
	</tr></thead>';
	$fields = "id,type,method,date_id,template_id,status,admin_id";
	$lstReminder = $clsReminder->GetAll("crm_followup_id='{$crm_followup_id}' order by {$sortby} {$sorttype}", $fields);
	if(!empty($lstReminder)){ $ii=0; //Init
		foreach($lstReminder as $reminder){
			$id = $reminder[$clsReminder->pkey];
			$props = sprintf('resource_id="%s" crm_followup_id="%s" id="%s"',$resource_id, $crm_followup_id, $id);
			$editAction = '<button type="button" class="btn btn-default ajOpenCRMReminder" '.$props.'><i class="fa fa-pencil"></i></button>';
			$deleteAction = '<button type="button" class="btn btn-danger ajDeleteCRMReminder" '.$props.'><i class="fa fa-trash"></i></button>';
			$html .= '<tr>
				'.($action=='_edit'?'<td data-label="No." class="text-center">'.($ii+1).'</td>':'').'
				<td data-label="'.__('Date').'">'.$clsISO->convertTimeToText($reminder['date_id'],true).'</td>
				<td data-label="'.__('Method').'">'.$clsReminder->getMethod($id, $reminder).'</td>
				<td data-label="'.__('Status').'">'.$clsReminder->getStatus($id, $reminder).'</td>
				'.($action=='_edit'?'<td data-label="'.__('Template').'">'.$clsReminder->getTemplate($id, $reminder).'</td>':'').'
				<td data-label="'.__('Remind').'">'.$clsReminder->getReminder($id, $reminder).'</td>
				'.($action=='_edit'?'<td data-label="'.__('_Actions').'" class="text-center"><div class="btn-group btn-group-xs">'.$editAction.$deleteAction.'</div></td>':'').'
			</tr>';
			++$ii;
		}
		unset($lstReminder);
	}else{
		$html .= '<tr>
			<td colspan="7">'.CRM::renderHTMLNoDocument("Not any reminder this").'</td>
		</tr>';
	}
	$html .= '</table>';
	// output
	echo($html); die();
}
function default_ajOpenReminderCRM(){
	global $core,$adminid, $clsISO, $_LANG_ID;
	$resource_id = (int) Input::post('resource_id',0);
	$crm_followup_id = (int) Input::post('crm_followup_id',0);
	$crm_followup_reminder_id = (int) Input::post('id',0);
	// Follow-ups
	echo form_reminer($crm_followup_reminder_id, $resource_id, $crm_followup_id); 
	die();
}
function default_aj_open_followup(){
	global $core, $adminid, $clsISO, $clsUser, $_LANG_ID;
	if($clsISO->validateLoginInformation()==0){echo('');die();}
	$clsPotential = new Potential();
	$clsFollowUp = new FollowUp();
	$is_view = Input::post('is_view', 0);
	$holderG = Input::post('holderG','potential');
	$resource_id = (int) Input::post('resource_id',0);
	$crm_followup_id = (int) Input::post('crm_followup_id',0);
	
	/* HTML */
	$html = '<div class="modal-dialog modal-lg"><div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close close_pop"><span>×</span></a> 
			<h3 class="modal-title"><strong>'.__('FollowUp').' #'.$crm_followup_id.'</strong></h3>
		</div>
		<div class="modal-body">';
	if($is_view){ // View
		$oneFollowUp = $clsFollowUp->getOne($crm_followup_id);
		$html .= '<div class="mg-wrapper mt5">
			<div class="form-horizontal">
				<div class="form-group">
					<div class="col-md-2 text-right">'.__('AssignedTo').'</div>
					<div class="col-md-10">'.$clsPotential->getPotential($resource_id,true).'</div>
				</div>
				<div class="form-group">
					<div class="col-md-2 text-right">'.__('Date').'</div>
					<div class="col-md-10"><i class="fa fa-clock-o"></i> '.$clsISO->convertTimeToText($oneFollowUp['date_id'], true).'</div>
				</div>
				<div class="form-group">
					<div class="col-md-2 text-right">'.__('Description').'</div>
					<div class="col-md-10">'.$oneFollowUp['content'].'</div>
				</div>
				<div class="form-group">
					<div class="col-md-2 text-right">'.__('Admin').'</div>
					<div class="col-md-10">'.$clsUser->getFullName($oneFollowUp['admin_id']).'</div>
				</div>
			</div>
			<h3>'.__('Reminders').'</h3>
			<div class="holderReminderCRM__'.$resource_id.'"></div>
		</div>';
	}else{
		$html.= '<div class="mg-wrapper mt5">
		<div class="box light box__'.$resource_id.' bordered">
			<div class="box-title">
				<span class="caption uppercase"><strong>'.__('Edit FollowUp').' #'.$crm_followup_id.'</strong></span>
				<div class="pull-right">
					<a href="javascript:void(0);" class="btn-outline pull-right hidebox"><i class="fa fa-compress"></i></a>
				</div>
			</div>
			'.form_follow_ups($resource_id, $crm_followup_id, true, $holderG).'
		</div>
		<div class="box hide light bordered box__'.$resource_id.'">
			<div class="box-title">
				<span class="caption uppercase green">
					<strong>'.__('ReminderOfFollowUp').' #'.$crm_followup_id.'</strong>
				</span>
				<div class="pull-right">
					<a href="javascript:void(0);" class="btn-outline pull-right hidebox"><i class="fa fa-compress"></i></a>
				</div>
			</div>
			<form id="frmReminder__'.$resource_id.'" class="form-horizontal formReminder_'.$resource_id.'_'.$crm_followup_id.'" method="post">
				'.form_reminer(0, $resource_id, $crm_followup_id).'
			</form>
		</div>
		<div class="box light bordered box__'.$resource_id.'">
			<div class="box-title">
				<span class="caption uppercase green mr10"><strong>'.__('ListOfReminders').'</strong></span>
				<div class="input-group pull-left" style="max-width:250px">
					<span class="input-group-addon">'.$core->makeIcon('search').'</span>
					<input type="text" class="form-control txtSearchReminder_'.$resource_id.'" placeholder="'.__('Search').'" />
				</div>
				<div class="pull-right">
					<button class="iso-button-standard" onClick="reload_list_reminder(this);" resource_id="'.$resource_id.'" crm_followup_id="'.$crm_followup_id.'">
						<i class="fa fa-refresh"></i>
					</button>
				</div>
			</div>
			<div class="box-body holderReminderCRM__'.$resource_id.'"></div>
		</div></div>';
	}
	$html .= '</div>
	</div></div>';
	// Output
	echo '0$$$'.$html; die();
}
function default_aj_open_followup_reschedue(){
	global $smarty, $core, $adminid, $clsISO, $_LANG_ID;
	$clsFollowUp = new FollowUp();
	$holderG = Input::post('holderG',"potential");
	$resource_id = Input::post('resource_id',0);
	$crm_followup_id = Input::post('crm_followup_id',0);
	#
	$date_id = $clsFollowUp->getOneField('date_id',$crm_followup_id);
	$props = $clsISO->make_attrs_builder(array(
		'holderG' => $holderG,
		'resource_id'=>$resource_id,
		'crm_followup_id'=>$crm_followup_id
	));
	$smarty->assign('resource_id', $resource_id);
	$smarty->assign('date_id', $date_id);
	$smarty->assign('props', $props);
	
	$tooltip_a = makeTooltip(__('When enabled, each reminder date will be adjusted according to the changed'));
	$tooltip_b = makeTooltip(($_LANG_ID=='vn'?'Lý do không để trống':'Reason is required'));
	$smarty->assign('tooltip_a', $tooltip_a);
	$smarty->assign('tooltip_b', $tooltip_b);
	#
	$html = $core->build('open.followup.reschedue.tpl');
	echo $html; die();
}
function default_ejs_save_followup_reschedue(){
	global $core, $adminid, $_LANG_ID, $clsISO, $clsUser;
	$clsEmail = new Email();
	$CFG = new Configuration();
	$clsEmailTemplate = new EmailTemplate();
	$clsFollowUp = new FollowUp();
	$clsReminder = new Reminder();
	$resource_id = Input::post('resource_id');
	$crm_followup_id = Input::post('crm_followup_id');
	/** Old */
	$oneFollowUp = $clsFollowUp->getOne($crm_followup_id,"admin_id,date_id,content,more_information,service_id");
	$datetime = (int) $oneFollowUp['date_id'];
	$description = $oneFollowUp['content'];
	$more_information = $oneFollowUp['more_information'];
	$more_information = !empty($more_information) ? json_decode($more_information, true) : array();
	/** New */
	$date_id = Input::post('date_id');
	$time = Input::post('time',"00:00:00");
	$newdatetime = $clsISO->convertTextToTime($date_id, $time);
	$is_sendmail = (int) Input::post('is_sendmail',0);
	$upd_reminder = (int) Input::post('upd_reminder',0);
	/** Update */
	$more_information[] = array(
		'id' => $clsISO->getUniqid(),
		'date_id'	=> $date_id,
		'time'		=> $time,
		'message'	=> Input::post('message'),
		'adminid'	=> $adminid,
		'reg_date'	=> time()
	);
	if($clsFollowUp->updateOne($crm_followup_id, array(
		'date_id'	=> $newdatetime,
		'more_information' => json_encode($more_information)
	))){
		if($upd_reminder==1){// Caulator range_date
			if($datetime > $newdatetime){
				$operator  = '-';
				$timerange = $datetime - $newdatetime;
			}else{
				$operator = '+';
				$timerange = $newdatetime - $datetime;
			}
			if($timerange >= 0){ // Less than 1 day
				$lstReminder = $clsReminder->GetAll("crm_followup_id='{$crm_followup_id}' order by date_id ASC");
				if(!empty($lstReminder)){
					foreach($lstReminder as $reminder){
						$date_in = (int) $reminder['date_id'];
						if($operator=='+'){
							$date_in += $timerange;
						}else{
							$date_in -= $timerange;
						}
						$time = date('H:i A', $date_in);
						$clsReminder->updateOne($reminder[$clsReminder->pkey], array(
							'date_id' => $date_in,
							'time'	=> $time
						));
					}
				}
				unset($lstReminder);
			}
		}
		/** Send Email */
		if($is_sendmail==1){
			$getMessageGolbal = $clsISO->getMessageGolbal();
			$admin_id = $oneFollowUp['admin_id'];
			$toname = $clsUser->getFullName($admin_id);
			$toemail = $clsUser->getEmail($admin_id);
			$template_id = $CFG->getValue('CRM_FollowUpRescheduleTemplate');
			
			if(intval($template_id) > 0){
				$oneTemplate = $clsEmailTemplate->getOne($template_id,"subject,msg,fromname,fromemail,c_header,c_footer");
				$subject = html_entity_decode($oneTemplate['subject']);
				$message = html_entity_decode($oneTemplate['msg']);
				$c_header = html_entity_decode($oneTemplate['c_header']);
				$c_footer = html_entity_decode($oneTemplate['c_footer']);
				$fromname = $oneTemplate['fromname'];
				$fromemail = $oneTemplate['fromemail'];
			}else{
				$subject = $clsEmailTemplate->getSubject("_RESCHEDUE");
				$message = $clsEmailTemplate->getMsg("_RESCHEDUE");
				$fromname = $clsEmailTemplate->getFieldValue('fromname', '_RESCHEDUE');
				$fromemail = $clsEmailTemplate->getFieldValue('fromemail', '_RESCHEDUE');
				$c_header = $clsEmailTemplate->getFieldValue('c_header', '_RESCHEDUE');
				$c_footer = $clsEmailTemplate->getFieldValue('c_footer', '_RESCHEDUE');
			}
			// From
			if(!$fromname){
				$fromname = $CFG->getValue('mail_from_name');
				if(!$fromname){ $fromname = PAGE_NAME;}
			}
			if(!$fromemail){
				$fromemail = $CFG->getValue('email_from_email');
			}
			// Valid toemail
			if($clsISO->is_valid_email($toemail)){
				$mapField = array(
					'{$id}'	=> $crm_followup_id,
					'{$toname}'	=> $toname,
					'{$datetime}'	=> $clsISO->convertTimeToText($datetime, true),
					'{$newdatetime}'	=> $clsISO->convertTimeToText($newdatetime, true),
					'{$reason}'	=> Input::post('message'),
					'{$description}'	=> $description
				);
				foreach($mapField as $key => $val){
					$subject = @str_replace($key, $val, $subject);
					$message = @str_replace($key, $val, $message);
				}
				$merge_fields = array(
					'[%brand_header_email%]' => $c_header,
					'[%tem_msg%]' => $message,
					'[%brand_footer_email%]' => $c_footer
				);
				$oneMsg = $getMessageGolbal;
				foreach($merge_fields as $k => $v){
					$oneMsg = str_replace($k, $v, $oneMsg);
				}
				//$clsEmail->sendEmail($fromemail,$toemail,$subject,$message,$fromname,"");
				$is_sent_email = $clsISO->sendEmail($fromemail,$toemail,$subject,$oneMsg,$fromname);
			}
		}
	}
	//echo(1); die();
	echo json_encode(array(
		"msg"	=> "ok",
		"service_id"	=> $oneFollowUp['service_id']
	));die;
}
function default_ajSaveReminderCRM(){
	global $core,$adminid, $clsISO, $_LANG_ID;
	if($clsISO->validateLoginInformation()==0){echo('');die();}
	$clsFollowUp = new FollowUp();
	$clsReminder = new Reminder();
	$id = Input::post('id',0);
	$resource_id = Input::post('resource_id');
	$crm_followup_id = Input::post('crm_followup_id');
	#---
	if(Input::exists('action','GET') && Input::get('action')=='_delete'){
		$oneReminder = $clsReminder->getOne($id);
		if($clsReminder->deleteOne($id)){
			/* Update Number CRM Reminder */
			$clsFollowUp->updateOne($crm_followup_id, array(
				'number_reminder'	=> $clsReminder->countItem("crm_followup_id='{$crm_followup_id}'")
			));
			/* Logs */ 
			$log_message = "Delete reminder #".$id." for '".$oneReminder['type']."'";
			$clsISO->logs($clsReminder->tbl, $clsReminder->pkey, $id, $log_message,'CRM');
			/* End Logs */
		}
		echo(1); die();
	}else{
		$date_id = Input::post('date_id');
		$time = Input::post('time');
		$datetime = $clsISO->convertTextToTime($date_id, $time);
		if(intval($id) > 0){
			$clsReminder->updateOne($id, array(
				'date_id'	=> $datetime,
				'time'	=>	$time,
				'template_id' => Input::post('template_id'),
				'type' => Input::post('type'),
				'method' => Input::post('method'),
				'admin_id' => Input::post('admin_id'),
				'cc' => $clsISO->makeSlashListFromArray(Input::post('cc')),
				'status'	=> 'pending',
				'user_id_update'	=> $adminid,
				'upd_date'	=> time()
			));
			/* Logs */
			$log_message = "Updated reminder #".$id." has been updated for '".$oneReminder['type']."'";
			$clsISO->logs($clsReminder->tbl, $clsReminder->pkey, $id, $log_message,'CRM');
		}else{
			$id = $clsReminder->getMaxId();
			$clsReminder->insert(array(
				'id'	=> $id,
				'crm_followup_id'	=> $crm_followup_id,
				'date_id'	=> $datetime,
				'time'	=>	$time,
				'template_id' => Input::post('template_id'),
				'type' => Input::post('type'),
				'method' => Input::post('method'),
				'admin_id' => Input::post('admin_id'),
				'cc' => $clsISO->makeSlashListFromArray(Input::post('cc')),
				'status'	=> 'pending',
				'user_id'	=> $adminid,
				'user_id_update'	=> $adminid,
				'reg_date'	=> time(),
				'upd_date'	=> time()
			));
			/* Update Number CRM Reminder */
			$clsFollowUp->updateOne($crm_followup_id, array(
				'number_reminder'	=> $clsReminder->countItem("crm_followup_id='{$crm_followup_id}'")
			));
			// Logs
			$log_message = "New reminder #".$id." has been added for '".$oneReminder['type']."'";
			$clsISO->logs($clsReminder->tbl, $clsReminder->pkey, $id, $log_message,'CRM');
		}
	}
	// output
	echo($id); die();
}
function default_done_followup(){
	global $smarty,$adminid,$core,$clsISO;
	$clsFollowUp = new FollowUp();
	$customer_id = (int) Input::post('customer_id',0);
	$followup_id = (int) Input::post('followup_id',0);
	/** Update */
	$msg = '_error';
	if($clsFollowUp->updateOne($followup_id, array(
		'status_id'	=> _FOLLOWUP_STATUS_DONE_ID,
		'upd_date'	=> time()
	))){
		$msg = '_success';
	}
	// Return
	echo json_encode(array(
		"msg"	=> $msg
	));die;
}
function default_open_import(){
	global $core,$adminid,$clsISO,$profile_id,$oneProfile;
	$uid = $clsISO->getUniqid();
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCampaign = new Campaign();
	###
	$html_campaigns = "";
	$cond = "`is_trash`=0";
	$role_id = (int) $oneProfile['role_id'];
	$department_id = (int) $oneProfile['department_id'];
	if($department_id == _DEPARTMENT_MKT_ID){
		$cond.= " and `user_id` in (
			select `profile_id` from {$clsProfile->tbl} 
			where `is_trash`=0 and `department_id`='"._DEPARTMENT_MKT_ID."'
		)";
	} else {
		$cond.= " and `user_id`='{$profile_id}'";
	}
	$field = "{$clsCampaign->pkey},`title`";
	$list_campaigns = $clsCampaign->getAll("{$cond} and `campaign_type`='_campaign' order by `reg_date` DESC", $field);
	if(!empty($list_campaigns)){
		foreach($list_campaigns as $key => $val){
			$html_campaigns.= sprintf('<option value="%s">%s(%s)</option>', $val[$clsCampaign->pkey], $val['title']);
		}
		unset($list_campaigns);
	}
	$html= '<div class="modal-dialog">
		<form method="post" action="" enctype="multipart/form-data" class="modal-content" id="frmIssue">
			<div class="modal-header"> 
				<h5 class="modal-title"><strong>Nhập khách hàng</strong></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<table class="form" cellpadding="0" cellspacing="2" width="100%">
					<tr>
						<td class="fieldarea" colspan="2">
							<strong>Step 1: Download Spreadsheet</strong>
						</td>
					</tr>
					<tr>
						<td class="fieldlabel" width="25%"></td>
						<td class="fieldarea">
							<a data-toggle="ripple" href="'.PCMS_URL.'/templates/Customer.xlsx" target="_blank" class="btn btn-outline-primary"><svg width="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 96 96" fill="#FFF" stroke-miterlimit="10" stroke-width="2">
								<path stroke="#979593" d="M67.1716,7H27c-1.1046,0-2,0.8954-2,2v78 c0,1.1046,0.8954,2,2,2h58c1.1046,0,2-0.8954,2-2V26.8284c0-0.5304-0.2107-1.0391-0.5858-1.4142L68.5858,7.5858 C68.2107,7.2107,67.702,7,67.1716,7z"/>
								<path fill="none" stroke="#979593" d="M67,7v18c0,1.1046,0.8954,2,2,2h18"/>
								<path fill="#C8C6C4" d="M51 61H41v-2h10c.5523 0 1 .4477 1 1l0 0C52 60.5523 51.5523 61 51 61zM51 55H41v-2h10c.5523 0 1 .4477 1 1l0 0C52 54.5523 51.5523 55 51 55zM51 49H41v-2h10c.5523 0 1 .4477 1 1l0 0C52 48.5523 51.5523 49 51 49zM51 43H41v-2h10c.5523 0 1 .4477 1 1l0 0C52 42.5523 51.5523 43 51 43zM51 67H41v-2h10c.5523 0 1 .4477 1 1l0 0C52 66.5523 51.5523 67 51 67zM79 61H69c-.5523 0-1-.4477-1-1l0 0c0-.5523.4477-1 1-1h10c.5523 0 1 .4477 1 1l0 0C80 60.5523 79.5523 61 79 61zM79 67H69c-.5523 0-1-.4477-1-1l0 0c0-.5523.4477-1 1-1h10c.5523 0 1 .4477 1 1l0 0C80 66.5523 79.5523 67 79 67zM79 55H69c-.5523 0-1-.4477-1-1l0 0c0-.5523.4477-1 1-1h10c.5523 0 1 .4477 1 1l0 0C80 54.5523 79.5523 55 79 55zM79 49H69c-.5523 0-1-.4477-1-1l0 0c0-.5523.4477-1 1-1h10c.5523 0 1 .4477 1 1l0 0C80 48.5523 79.5523 49 79 49zM79 43H69c-.5523 0-1-.4477-1-1l0 0c0-.5523.4477-1 1-1h10c.5523 0 1 .4477 1 1l0 0C80 42.5523 79.5523 43 79 43zM65 61H55c-.5523 0-1-.4477-1-1l0 0c0-.5523.4477-1 1-1h10c.5523 0 1 .4477 1 1l0 0C66 60.5523 65.5523 61 65 61zM65 67H55c-.5523 0-1-.4477-1-1l0 0c0-.5523.4477-1 1-1h10c.5523 0 1 .4477 1 1l0 0C66 66.5523 65.5523 67 65 67zM65 55H55c-.5523 0-1-.4477-1-1l0 0c0-.5523.4477-1 1-1h10c.5523 0 1 .4477 1 1l0 0C66 54.5523 65.5523 55 65 55zM65 49H55c-.5523 0-1-.4477-1-1l0 0c0-.5523.4477-1 1-1h10c.5523 0 1 .4477 1 1l0 0C66 48.5523 65.5523 49 65 49zM65 43H55c-.5523 0-1-.4477-1-1l0 0c0-.5523.4477-1 1-1h10c.5523 0 1 .4477 1 1l0 0C66 42.5523 65.5523 43 65 43z"/>
								<path fill="#107C41" d="M12,74h32c2.2091,0,4-1.7909,4-4V38c0-2.2091-1.7909-4-4-4H12c-2.2091,0-4,1.7909-4,4v32 C8,72.2091,9.7909,74,12,74z"/><path d="M16.9492,66l7.8848-12.0337L17.6123,42h5.8115l3.9424,7.6486c0.3623,0.7252,0.6113,1.2668,0.7471,1.6236 h0.0508c0.2617-0.58,0.5332-1.1436,0.8164-1.69L33.1943,42h5.335l-7.4082,11.9L38.7168,66H33.041l-4.5537-8.4017 c-0.1924-0.3116-0.374-0.6858-0.5439-1.1215H27.876c-0.0791,0.2684-0.2549,0.631-0.5264,1.0878L22.6592,66H16.9492z"/>
							</svg> Tải mẫu file Import</a>
						</td>
					</tr>
					<tr><td class="fieldarea" colspan="2">
						<strong>Step 2: '.$core->get_Lang('AttachFile').'</strong></td>
					</tr>
					<tr>
						<td class="fieldlabel">Chọn file(.xls,.xlsx)</td>
						<td class="fieldarea" colspan="3">
							<input type="file" name="fileimport" class="form-control fileimport" />
							<small class="form-text">File Import excludes Create Date & Update Date</small>
						</td>
					</tr>
					<tr>
						<td class="fieldlabel">Nguồn khách</td>
						<td class="fieldarea">
							<select name="resource_id" class="form-control form-select required">
								<option value="0">Nguồn gốc</option>
								'.$clsProperty->getSelectByProperty('_CUSTOMER_RESOURCES', 0).'
							</select>
						</td>
					</tr>
					<tr>
						<td class="fieldlabel">Chiến dịch <a onClick="$Core.crm.open_campaign(this, event)" 
						href="javascript:void(0);" toId="Import_'.$uid.'">+ Thêm<a></td>
						<td class="fieldarea">
							<select name="list_campaign_id[]" id="slb_Campaign_Import_'.$uid.'" data-placeholder="Chiến dịch" 
							multiple="true" class="form-control iso-select2" data-width="100%" data-allow-clear="true">
								<option value="0">Lựa chọn chiến dịch</option>
								'.$html_campaigns.'
							</select>
						</td>
					</tr>
					<tr>
						<td class="fieldlabel"></td>
						<td class="fieldarea" colspan="3">
							<div class="d-flex gap-1 align-items-center">
								<label class="switch">
									<input type="checkbox" name="auto_create_followups" value="1" />
									<span class="slider round"></span>
								</label>
								<span>Tạo FU tự động</span>
							</div>
						</td>
					</tr>
					<tr>
						<td class="fieldlabel">Người phụ trách</td>
						<td class="fieldarea">
							<select class="iso-selectizeNotSearch required" name="admin_id" data-placeholder="Người phụ trách" 
								data-url="'.PCMS_URL.'/index.php?mod=home&act=list_staff">
								<option value="'.$profile_id.'" selected="selected">
									'.$clsProfile->getIndentityV2($profile_id, $oneProfile, false).'
								</option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="fieldlabel">Người liên quan</td>
						<td class="fieldarea">
							<select class="iso-selectizeNotSearch" name="list_share_id[]" data-placeholder="Người liên quan" 
								data-url="'.PCMS_URL.'/index.php?mod=home&act=list_staff" multiple="true">
							</select>
						</td>
					</tr>
				</table>
			</div>
			<div class="modal-footer"> 
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
				<button type="button" class="btn btn-primary" onClick="$Core.crm.do_import(this, event)">
					'.$core->get_Lang('Upload').'
				</button>
			</div>
		</div>
	</form></div>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	));die();
}
function default_do_import_customer(){
	global $core,$profile_id,$clsISO;
	$clsProfile = new Profile();
	$clsProperty = new Property();
	$clsCustomer = new Customer();
	###
	$totalInsert = $totalDuplicate = 0;
	if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
		$resource_id = (int) Input::post('resource_id', 0);
		$admin_id = (int) Input::post('admin_id', $profile_id);
		$list_share_id  = Input::post('list_share_id');
		$list_campaign_id = Input::post('list_campaign_id');
		$list_campaign_id = !empty($list_campaign_id) 
			? $clsISO->makeSlashListFromArray($list_campaign_id) : "";
		$auto_create_followups = (int) Input::post('auto_create_followups', 0);
		
		$more_information = array();
		if($admin_id != $profile_id){
			$list_share_id[] = $profile_id;
			$more_information['logs'][$clsISO->getUniqid()] = array(
				'_type' => 'assign',
				'from_id' => $profile_id,
				'to_id' => $admin_id,
				'status_id' => _CRM_STATUS_LEAD_ID,
				'reg_date' => time()
			);
		}
		if(@is_uploaded_file($_FILES['fileimport']['tmp_name'])){
			$target_dir = PCMS_DIR."/tmp/";
			$file_ext =explode('.',basename($_FILES["fileimport"]["name"]));
			$file_ext =strtolower(end($file_ext));
			$target_file = $target_dir . time().'.'.$file_ext;
			if (move_uploaded_file($_FILES["fileimport"]["tmp_name"], $target_file)) {
				$html = '';
				$inputFileName = $target_file;
				require_once DIR_INCLUDES."/phpexcel/Classes/PHPExcel.php";
				require_once DIR_INCLUDES."/phpexcel/Classes/PHPExcel/IOFactory.php";
				$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
				try {
					$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
					$objReader = PHPExcel_IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($inputFileName);
				} catch(Exception $e) {
					die($e->getMessage());
				}
				$worksheet = $objPHPExcel->getActiveSheet();
				$worksheetTitle     = $worksheet->getTitle();
				$highestRow         = $worksheet->getHighestRow();
				$highestColumn      = $worksheet->getHighestColumn();
				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
				##
				$index = 0; $tblData =array();
				for($row=1; $row <= $highestRow; ++ $row){
					for($col=0; $col < $highestColumnIndex; ++$col){
						$cell = $worksheet->getCellByColumnAndRow($col, $row);
						$tblData[$index][] = $cell->getValue();
					}
					++$index;
				}
				// Remove file uploaded
				@unlink($inputFileName);
				if(!empty($tblData)){
					for($ii=1; $ii<count($tblData); $ii++){
						$name = $tblData[$ii][0];
						$email = $tblData[$ii][1];
						$phone = $clsCustomer->formatPhone($tblData[$ii][2]);
						$address = $tblData[$ii][3];
						$status = $tblData[$ii][4];
						$begin_need = $tblData[$ii][5];
						if(!empty($name) && !empty($phone)){
							// more cond and `admin_id`='{$profile_id}'
							$lstcheck = $clsCustomer->getAll("`admin_id`='{$profile_id}' and `phone`='{$phone}' limit 0,1", $clsCustomer->pkey);
							// $clsISO->print_pre($lstcheck); die();
							if(!empty($lstcheck)){
								$totalDuplicate ++;
							}else{
								$customer_id = $clsCustomer->getMaxId();
								$list_share_id_slash = !empty($list_share_id) 
									? $clsISO->makeSlashListFromArray($list_share_id) : "";
								if($clsCustomer->insert(array(
									$clsCustomer->pkey => $customer_id,
									'name'	=> $name,
									'name_slug'	=> $core->replaceSpace($name),
									'status_id'	=> _CRM_STATUS_LEAD_ID,
									'resource_id' => $resource_id,
									'list_campaign_id' => $list_campaign_id,
									'list_share_id' => $list_share_id_slash,
									'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
									'email'	=> $email,
									'phone'	=> $phone,
									'address'  => $address,
									'admin_id' => $admin_id,
									'begin_need' => $begin_need,
									'user_id'	 => $profile_id,
									'user_id_update' => $profile_id,
									'reg_date'	=> time(),
									'upd_date'	=> time(),
								))){
									$totalInsert++;
									if($auto_create_followups == 1){
										$clsFollowUp = new FollowUp();
										$timer = strtotime(date('d-m-Y'));
										$list_times = array(
											'1day' => strtotime('+1 day', $timer),
											'2day' => strtotime('+2 days', $timer),
											'5day' => strtotime('+5 days', $timer),
											'15day' => strtotime('+15 days', $timer),
											'30day' => strtotime('+30 days', $timer)
										);
										foreach($list_times as $key => $date_id){
											if($clsFollowUp->insert(array(
												'type_id' => _FOLLOWUP_CALL_ID,
												'status_id' => _FOLLOWUP_STATUS_PLAN_ID,
												'customer_id' => $customer_id,
												'date_id' => $date_id,
												'intro' => 'Call liên hệ lại khách',
												'admin_id' => $admin_id,
												'user_id' => $profile_id,
												'user_id_update' => $profile_id,
												'reg_date' => time(),
												'upd_date' => time()
											))){
												$clsNotify = new Notify();
												$titleNoty = sprintf('Lịch hẹn <strong>%s</strong> với khách hàng <strong>%s</strong> vào lúc <strong>%s</strong>', $clsProperty->getTitle(_FOLLOWUP_CALL_ID) .": Call liên hệ lại khách", $clsCustomer->getName($customer_id), $clsISO->convertTimeToText($date_id, true));
												$clsNotify->insertNotify('FollowUp', $clsFollowUp->pkey, $followup_id, $titleNoty, $date_id, '|'.$admin_id.'|');
											}
										}
									}
									if($admin_id != $profile_id){
										$clsNotify = new Notify();
										$oCustomer = array('name' => $name);	
										$titleNoty = sprintf('<strong>%s</strong> giao bạn phụ trách khách hàng <strong>%s</strong>', 
											$clsProfile->getFullName($profile_id, $oneProfile), 
											$clsCustomer->getName($customer_id, $oCustomer)
										);
										$clsNotify->insertNotify('Customer',$clsCustomer->pkey,$customer_id,$titleNoty,time(),"|".$admin_id."|");
									}
								}
							}
						}
					}
				}
			}
		}
	}
	// Return
	echo('0$$$'.$totalInsert.'$$$'.$totalDuplicate);die();
}
function default_ajLoadListCRMEmailLogs(){
	global $core,$adminid,$clsISO;
	if($clsISO->validateLoginInformation()==0){echo('');die();}
	$clsCRMEmailLog = new CRMEmailLog();
	$clsFollowUp = new FollowUp();
	$clsReminder = new Reminder();
	$potential_id = Input::post('potential_id',0);
	$sortby = Input::post('sortby','reg_date');
	$sorttype = Input::post('sorttype','desc');
	
	$html = '<table class="table table-striped table-hover table-responsive" width="100%" cellpadding="2" cellspacing="2" border="0">
	<thead><tr>
		<th class="sorthandler '.makeClass($sortby,'reg_date',$sorttype).'" column="reg_date" width="12%">'.__('Date').'</th>
		<th class="sorthandler '.makeClass($sortby,'fromemail',$sorttype).'" column="fromemail">'.__('From').'</th>
		<th class="sorthandler '.makeClass($sortby,'toemail',$sorttype).'" column="toemail">'.__('To').'</th>
		<th>'.__('Follow-up').'</th>
		<th>'.__('Reminder').'</th>
		<th class="sorthandler '.makeClass($sortby,'subject',$sorttype).'" column="subject">'.__('Subject').'</th>
		<th>'.__('Attachments').'</th>
	</tr></thead>';
	$currentPage = Input::post('currentPage',1);
	$number_per_page = Input::post('number_per_page',10);
	
	$cond = "potential_id='$potential_id'";
	$totalRecord = $clsCRMEmailLog->countItem($cond);
	$totalPage = ceil($totalRecord/$number_per_page);
	$offset = ($currentPage-1)*$number_per_page;
	$limitCond = " limit {$offset},{$number_per_page}";
	$lstCRMEmailLog = $clsCRMEmailLog->GetAll("{$cond} order by {$sortby} {$sorttype}".$limitCond);
	if(!empty($lstCRMEmailLog)){
		foreach($lstCRMEmailLog as $emaillog){
			$followup = 'None';
			if($emaillog['followup_id'] > 0){
				$followup = $clsFollowUp->getFollowUp($emaillog['followup_id']) ;
			}
			$reminder = 'None';
			if($emaillog['reminder_id'] > 0){
				$followup = $clsReminder->getReminder($emaillog['reminder_id']) ;
			}
			$html .= '<tr>
				<td data-label="'.__('Date').'" width="10%">'.$clsISO->convertTimeToText($emaillog['reg_date'],true).'</td>
				<td data-label="'.__('From').'">'.$emaillog['fromname'].'('.$emaillog['fromemail'].')</td>
				<td data-label="'.__('To').'">'.$emaillog['toemail'].'</td>
				<td data-label="'.__('Follow-up').'">'.$followup.'</td>
				<td data-label="'.__('Reminder').'">'.$reminder.'</td>
				<td data-label="'.__('Subject').'">'.$emaillog['subject'].'</td>
				<td data-label="'.__('Attachments').'">'.$emaillog['attachments'].'</td>
			</tr>';
		}
	}else{
		$html .= '<tr>
			<td class="text-center" colspan="7">'.$clsISO->renderHTMLNoDocument('Not any email logs').'</td>
		</tr>';
	}
	$html .= '</table>';
	if($totalPage > 1){
		$html .= '<div class="easyui-pagination" id="PageCRMEmailLogs" pageNumber="'.$currentPage.'" pageList="['.$number_per_page.']"><div>';
	}
	// output
	echo json_encode(array(
		'currentPage'	=> $currentPage,
		'number_per_page'	=> $number_per_page,
		'totalRecord'	=> $totalRecord,
		'totalPage'	=> $totalPage,
		'html'	=> $html
	)); die();
}
function default_ajOpenNewFollowUp(){
	global $core, $dbconn, $clsISO, $clsConfiguration,$adminid;
	$CFG = new Configuration();
	$clsFollowUp = new FollowUp();
	ini_set('display_errors',1);
	error_reporting(E_ALL);
	$tp = Input::post('tp','detail');//detail, popup
	$date_id = Input::post('date_id',0);
	if(!empty($date_id)){
		$date_id = strtotime($date_id);
	}else{
		$date_id = time();	
	}
	$oneFollowUp = array(
		'admin_id'	=> $adminid,
		'date_id'	=> $date_id,
		'time'		=> date('h:i A'),
		'type_id'	=> $CFG->getValue('CRM_FollowUpTypeDefault'),
		'content'	=> '',
		'upd_reminder' => 0
	);
	if($tp=='popup'){
		$holderG = Input::post('holderG','_tablist');
		$html = '<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header"> 
				<a href="javascript:void();" class="closeEv close close_pop"><span>×</span></a> 
				<h3 class="modal-title">
					<strong>'.__('Add Follow-ups To Google Calendar').'</strong>
				</h3>
			</div>
			<form method="post" action="" enctype="multipart/form-data" id="frmFollowUpCalendar">
				<div class="modal-body">
					<div class="form-group lines">
						<div class="col-md-3 text-right"><h5>'.__('Admin').' <i class="fa fa-info-circle"></i></h5></div>
						<div class="col-md-9">
							<select class="form-control iso-selectbox '.(!$clsISO->checkPermission('full_permissions_crm')?' disabled':'').'" name="admin_id">
								'.CRM::getSelectUserOptionsInCRM($oneFollowUp['admin_id']).'
							</select>
						</div>
					</div>
					<div class="form-group lines">
						<div class="col-md-3 text-right"><h5>'.__('Customer').' <i class="fa fa-user-circle-o"></i></h5></div>
						<div class="col-md-9">
							<select class="form-control iso-selectbox" name="resource_id" >
								'.CRM::getSelectOptionsPotential($oneFollowUp['admin_id']).'
							</select>
						</div>
					</div>
					<div class="form-group lines">
						<div class="col-md-3 text-right"><h5>'.__('Date').'</h5></div>
						<div class="col-md-9">
							<input type="text" class="form-control datepicker pull-left mr10" value="'.$clsISO->convertTimeToText($oneFollowUp['date_id']).'" name="date_id" readonly />
							<input type="text" class="form-control timepicker" name="time" placeholder="--:-- --" value="'.$oneFollowUp['time'].'" />
						</div>
					</div>
					<div class="form-group lines">
						<div class="col-md-3 text-right"><h5>'.__('Type').'</h5></div>
						<div class="col-md-6">
							<select class="form-control" name="type_id">
								'.$clsISO->getSelectByPropertyTypeNotTitle('_FOLLOWUP_TYPE',$oneFollowUp['type_id']).'
							</select>
						</div>
					</div>
					<div class="form-group lines">
						<div class="col-md-3 text-right"><h5>'.__('Description').'</h5></div>
						<div class="col-md-9">
							<textarea class="form-control" required name="content" rows="2">'.$oneFollowUp['content'].'</textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-warning close_pop" data-dismiss="modal" aria-hidden="true">'.__('Close').'</button>
					<button type="button" class="btn btn-success js_save-quick-followup" crm_followup_id="0" tp="'.$tp.'" holderG="'.$holderG.'">
						<span>'.$core->makeIcon('check',__('Add')).'</span>
					</button>
				</div>
			</form>
		</div></div>';
	}else{//detail
		$html = '
		<div class="box no-shadow no-border-bottom no-margin">
			<div class="box-title">
				<strong>'.__('Add Follow-up').' '.__('day').' '.$clsISO->convertTimeToText($date_id).'</strong>
			</div>
			<div class="box-body">
				<form class="form-horizontal" method="post" action="" enctype="multipart/form-data" id="frmFollowUpCalendar">
					<div class="form-group">
						<div class="col-md-3 text-right"><h5>'.__('Admin').' <i class="fa fa-info-circle"></i></h5></div>
						<div class="col-md-9">
							<select class="selectpicker" name="admin_id" '.(CRM::haveActionAssignAdmin()?'':'disabled="disabled"').'>
								'.CRM::getSelectUserOptionsInCRM($oneFollowUp['admin_id']).'
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-3 text-right"><h5>'.__('Customer').' <i class="fa fa-user-circle-o"></i></h5></div>
						<div class="col-md-9" id="div_resource_id">
							<select class="selectpicker" name="resource_id">
								'.CRM::getSelectOptionsPotential($oneFollowUp['admin_id']).'
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-3 text-right"><h5>'.__('Date').' <i class="fa fa-info-circle"></i></h5></div>
						<div class="col-md-9">
							<input type="text" class="form-control datepicker pull-left mr10" value="'.$clsISO->convertTimeToText($oneFollowUp['date_id']).'" name="date_id" readonly />
							<input type="text" class="form-control timepicker" name="time" placeholder="--:-- --" readonly value="'.$oneFollowUp['time'].'" />
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-3 text-right"><h5>'.__('Type').'</h5></div>
						<div class="col-md-9">
							<select class="selectpicker" name="type_id">
								'.$clsISO->getSelectByPropertyTypeNotTitle('_FOLLOWUP_TYPE',$oneFollowUp['type_id']).'
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-3 text-right"><h5>'.__('Description').'</h5></div>
						<div class="col-md-9">
							<textarea class="form-control" name="content" rows="2">'.$oneFollowUp['content'].'</textarea>
						</div>
					</div>
					<div class="form-group text-center">
						<button class="iso-button saveFollowUpCRMCalendar" crm_followup_id="0" tp="'.$tp.'">
							<span>'.__('AddFollowUp').'</span>
						</button>
					</div>
				</form>
			</div>
		</div>';
	}
	echo $html; die;
}
/* Campaign */
function default_ajLoadCRMManageCampaign(){
	global $adminid,$core,$clsISO;
	if($clsISO->validateLoginInformation()==0){echo('');die();}
	$clsProperty = new Property();
	$clsBusinessCampaign = new BusinessCampaign();
	$holderG = Input::post('holderG','_general');
	
	$html = '<div class="mg-wrapper">
		<div class="box light no-margin">
			<div class="box-title">
				'.CRM::renderHTMLButtonBack(array('page'=>'campaign')).'
				<div class="caption fl mr-half">
					<span class="uppercase">'.__("Campaigns").'</span>
				</div>
				<div class="input-group fl" style="max-width:250px">
					<span class="input-group-addon"><i class="fa fa-search"></i></span>
					<input type="text" class="form-control txtSearchCampaign" placeholder="'.__('Search').'" />
				</div>
				<div class="input-group fl ml-2" style="max-width:200px">
					<select name="" id="" class="form-control SearchCampaignDateEnd">
						<option value="">'.__('All').'</option>
						<option value="on">'.__('Not over').'</option>
						<option value="off">'.__('Finished').'</option>
					</select>
				</div>
				<div class="pull-right">'.($clsISO->checkPermission('create_campaign')?'<button class="iso-button createCRMCampaign" business_campaign_id="0">'.$core->makeIcon('plus-circle', __("adddnew")).'</button>':'').'
				</div>
			</div>
			<div class="box-body" style="min-height:650px">
				<div id="holderCRMCampainGlobe"></div>
			</div>
		</div>
	</div>';
	// Output
	echo($html);die();
}
function default_ajLoadListCRMCampaignGlobe(){
	global $adminid,$core,$clsISO,$dbconn;
	if($clsISO->validateLoginInformation()==0){echo('');die();}
	$clsVS_Admin = new VS_Admin();
	$clsProperty = new Property();
	$clsBusinessCampaign = new BusinessCampaign();
	$holderG = Input::post('holderG');
	#
	$html = '<table class="table table-hoder table-striped table-responsive" width="100%" id="TableListBusinessCampainGlobe">
	<thead><tr>
		<th width="3%" class="text-center">No.</th>
		<th><strong>'.__("CampaignName").'</strong></th>
		<th width="10%"><strong>'.__("Startdate").'</strong></th>
		<th width="10%"><strong>'.__("Duedate").'</strong></th>
		<th width="6%"><strong>'.__("Contacts").'</strong></th>
		<th><strong>'.__("Admin").'</strong></th>
		<th width="10%"><strong>'.__("Status").'</strong></th>
		<th class="text-center" width="5%">'.__('_Actions').'</th>
	</tr></thead>';
	$currentPage = Input::post('currentPage',1);
	$number_per_page = Input::post('number_per_page',20);
	#
	$cond = "is_trash=0 and type='CRM'";
	if($clsISO->checkPermission('full_permissions_crm')==0){
		if($core->_USER['user_group_id']==USER_GROUP_CARE){
			if(1){
				$lstCare = $dbconn->getCol("select {$clsVS_Admin->pkey} from {$clsVS_Admin->tbl} where is_trash=0 and is_active='1' and user_group_id=".USER_GROUP_CARE);
				$cond .= " and user_id in (".implode(',',$lstCare).")";
			}
		}else{
			$cond .= " and (user_id='$adminid' and admin_list like '%|{$adminid}|%')";
		}
		
	}
	$keySearch = Input::post('keySearch');
	$status_date = Input::post('status_date');
	if(!empty($keySearch) && $keySearch !='0'){
		$cond .= " and (title like '%{$keySearch}%' 
			or slug like '%".$core->replaceSpace($keySearch)."%'
		)";
	}
	if(!empty($status_date)){
		if($status_date=='on'){
			$cond .= " and end_date>='".strtotime(date("m/d/Y"))."'";
		}
		if($status_date=='off'){
			$cond .= " and end_date<'".strtotime(date("m/d/Y"))."'";
		}
	}
	$totalRecord = $clsBusinessCampaign->countItem($cond);
	$totalPage = ceil($totalRecord/$number_per_page);
	$offset = ($currentPage-1)*$number_per_page;
	$limitCond = " limit {$offset},{$number_per_page}";
	#
	$lstBusinessCampaign = $clsBusinessCampaign->GetAll($cond." order by reg_date desc".$limitCond);
	if(!empty($lstBusinessCampaign)){
		for($i=0;$i<count($lstBusinessCampaign);$i++){
			$business_campaign_id = $lstBusinessCampaign[$i][$clsBusinessCampaign->pkey];
			$admin_list = $lstBusinessCampaign[$i]['admin_list'];
			$admin_list = !empty($admin_list) && $admin_list != '|' && $admin_list != '||' ?
			$clsISO->getArrayByTextSlash($admin_list) : array();
			#
			$props = $clsISO->make_attrs_builder(array(
				'holderG'	=>	$holderG,
				'business_campaign_id'	=> $business_campaign_id
			));
			$html .= '<tr '.$props.'>
				<td data-label="No." class="text-center" '.$props.'>'.($i+1).'</td>
				<td data-label="'.__('CampaignName').'" class="fieldarea" '.$props.'><a class="text-link ajOpenStatisticCRMCampaign" '.$props.'>'.$lstBusinessCampaign[$i]['title'].'</a></td>
				<td data-label="'.__('Startdate').'" class="fieldarea" '.$props.'>'.$clsISO->formatDate($lstBusinessCampaign[$i]['start_date']).'</td>
				<td data-label="'.__('Duedate').'" class="fieldarea" '.$props.'>'.$clsISO->formatDate($lstBusinessCampaign[$i]['end_date']).'</td>
				<td data-label="'.__('Contacts').'" class="text-center" '.$props.'><span class="badge " label-danger '.(!empty($lstBusinessCampaign[$i]['color'])?'style="background-color:'.$lstBusinessCampaign[$i]['color'].'"':'').'>'.intval($lstBusinessCampaign[$i]['number_val']).'</span></td>
				<td data-label="'.__('Admin').'" class="fieldarea" '.$props.'>'.CRM::getAdminArray($admin_list).'</td>
				<td data-label="'.__('Status').'" class="fieldarea">'.CRM::getStatusCampaign($business_campaign_id,$lstBusinessCampaign[$i]).'</td>
				<td data-label="'.__('_Actions').'" class="fieldarea text-center">
					<div class="dropdown dropdown-action">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-ellipsis-v" aria-hidden="true"></i>
						</a>
						<ul class="dropdown-menu icon">
							'.($clsISO->checkPermission('view_campaign')?'<li><a class="ajOpenViewCRMCampaign" '.$props.'>'.$core->makeIcon('eye').' '.__('View').'</a></li>':'').'
							'.($clsISO->checkPermission('edit_campaign')?'<li><a class="ajOpenCRMCampaign" '.$props.'>'.$core->makeIcon('pencil').' '.__('Edit').'</a></li>':'').'
							'.($clsISO->checkPermission('add_potential_campaign')?'<li><a class="ajAddPotentialCampaign" '.$props.'>'.$core->makeIcon('user-plus').' '.__('TargetCustomers').'</a></li>':'').'
							'.($clsISO->checkPermission('delete_campaign')?'<li><a class="deleteCRMCampaign" '.$props.'>'.$core->makeIcon('trash').' '.__('Delete').'</a></li>':'').'
						</ul>
					</div>
				</td>
			</tr>';
		}
	}else{
		$html .= '<tr>
			<td colspan="8">'.CRM::renderHTMLNoDocument('Not any campain !').'</td>
		</tr>';
	}
	$html .= '</table>
	<input type="hidden" class="PageCRMCampain_currentPage" value="'.$currentPage.'" />
	<input type="hidden" class="PageCRMCampain_dataTables_length" value="'.$number_per_page.'" />
	'.($totalRecord>0?'<div class="easyui-pagination" id="PageCRMCampain" pageNumber="'.$currentPage.'" pageList="['.$number_per_page.']"></div>':'').'	';
	#
	echo @json_encode(array(
		'currentPage'	=> $currentPage,
		'number_per_page'	=> $number_per_page,
		'totalRecord'	=> $totalRecord,
		'totalPage'	=> $totalPage,
		'html'	=> $html
	));die();
}
function default_ajOpenCRMCampaign(){
	global $adminid,$core,$clsISO;
	if($clsISO->validateLoginInformation()==0){echo('');die();}
	$clsProperty = new Property();
	$clsCountry = new Country();
	$clsBusinessCampaign = new BusinessCampaign();
	#
	$business_campaign_id = (int) Input::post('business_campaign_id',0);
	$oneBusinessCampaign = array();
	$arrAdminList = array();
	$arrFilters = array();
	if($business_campaign_id > 0){
		$oneBusinessCampaign = $clsBusinessCampaign->GetOne($business_campaign_id);
		$arrAdminList = $clsISO->getArrayByTextSlash($oneBusinessCampaign['admin_list']);
		$arrFilters = @json_decode($oneBusinessCampaign['filters'], true);
	}
	$html = '<div class="mg-wrapper">
		<div class="box light">
			<div class="box-title">
				'.CRM::renderHTMLButtonBack(array('page'=>'campaign','action'=>'add')).'
				<div class="caption">
					<span class="uppercase">'.__(($business_campaign_id>0?'EditCampaign':'NewCampaign')).'</span>
				</div>
			</div>
			<div class="box-body">
				<form method="post" action="" enctype="multipart/form-data" id="frmCampain__'.$business_campaign_id.'">
					<div class="box light bordered">
						<div class="box-title">
							<div class="caption">
								<span class="uppercase bold">'.__('CampaignDetails').'</span>
							</div>
						</div>
						<div class="box-body form-horizontal">
							<div class="form-group">
								<div class="col-md-6 col-sm-6 col-xs-6">
									<div class="col-md-3 text-right"><h5>'.__('Name').'</h5></div>
									<div class="col-md-9">
										<input class="form-control required" name="title" value="'.$oneBusinessCampaign['title'].'" type="text">
										<small class="help-block">It will be used as an identifier in various dropdown menus.</small>
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<div class="col-md-3 text-right"><h5>'.__('Color').'</h5></div>
									<div class="col-md-9">
										<input class="form-control" value="'.$oneBusinessCampaign['color'].'"  name="color" type="color">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-6 col-sm-6 col-xs-6">
									<div class="col-md-3 text-right"><h5>'.__('Admin').'</h5></div>
									<div class="col-md-9">
										<select class="form-control" name="admins[]" multiple="multiple" style="height:60px">
											'.$clsISO->getSelectUserMultipleOptions($arrAdminList).'
										</select>
										<small class="help-block">Admins will be allowed to view records assigned to this campaign and manage them.</small>
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<div class="col-md-3 text-right"><h5>'.__('Description').'</h5></div>
									<div class="col-md-9">
										<textarea class="form-control" name="intro" placeholder="Describe the purpose of this campaign shortly" style="height:60px" row="3">'.$oneBusinessCampaign['intro'].'</textarea>
										<small class="help-block">Longer description of campaign, visible only on campaigns list.</small>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-6 col-sm-6 col-xs-6">
									<div class="col-md-3 text-right"><h5>'.__('Startdate').'</h5></div>
									<div class="col-md-9">
										<input class="datepicker form-control fl mr5" name="start_date" value="'.$clsISO->convertTimeToText($business_campaign_id>0?$oneBusinessCampaign['start_date']:time()).'" type="text" >
										<small class="help-block" style="margin-top:8px">'.__('Start on the selected date').'.</small>
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<div class="col-md-3 text-right"><h5>'.__('Duedate').'</h5></div>
									<div class="col-md-9">
										<input class="datepicker form-control fl mr5" name="end_date" value="'.$clsISO->convertTimeToText($business_campaign_id>0?$oneBusinessCampaign['end_date']:time()).'" type="text">
										<small class="help-block" style="margin-top:8px">'.__('End on the selected date').'.</small>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="box light bordered">
						<div class="box-title">
							<div class="caption">
								<span class="uppercase bold">'.__('Approach').'</span>
							</div>
							<div class="pull-right">
								<a href="javascript:void(0);" class="btn-outline pull-right hidebox">'.$core->makeIcon('compress').'</a>
							</div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-md-6 col-sm-6 col-xs-6">
									<div class="alert alert-warning">
										<strong>Phương pháp:</strong> Các quá trình để chuyển khách hàng từ không quan tâm -> quan tâm và muốn mua hàng.<br />
										VD: Tiềm năng > Xác định nhu cầu > Chăm sóc > Chốt Sale.
									</div>
									<div class="form-group">
										<input type="text" placeholder="'.__('AddStep').'" class="form-control js_create-new-campaign-status-item" autocomplete="off" maxlength="255" />
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<style type="text/css">
										.campaign-status-item{ clear:both; margin-bottom:5px;}
										.campaign-status-item:last-child{ margin-bottom:0px;}
										.campaign-status-item-title{ padding:4px;}
										.campaign-status-item-bottom{ margin-top:5px; width:calc(100% - 100px); width:100%}
										.campaign-status-item-top{display:inline-block; width:100%; height:32px;}
										.campaign-status-item-top>.no-index{float:left; width:60px; height:32px; padding:5px; line-height:20px;}
										.campaign-status-item-top>.no-index{position:relative;text-align:center;background:#c00000;color:#FFF;}
										.campaign-status-item-top>.desc{float:left;width:calc(100% - 160px);height:32px;background:#ccc}
										.campaign-status-item-top>.desc{padding:5px; margin-right:10px;}
										.campaign-status-item-top>.btn-groups{float:left;width:90px;padding:3px 0px 10px 8px;}
										.btn-groups>.btn-tiny{display:inline-block; padding:4px; outline:none; width:24px; height:24px; border:1px solid #b9b9b9; line-height:16px; text-align:center; border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;-khtml-border-radius:4px;}
									</style>
									<div class="form-group">
										<div class="list-campaign-status-item ui-sortable">';
											$arrCampaignStatus = array();
											if($business_campaign_id > 0){
												$clsBusinessCampaignStatus = new BusinessCampaignStatus();
												$arrCampaignStatus = $clsBusinessCampaignStatus->getAll("business_campaign_id='{$business_campaign_id}' order by order_no ASC");
												if(!empty($arrCampaignStatus)){
													for($i=0;$i<count($arrCampaignStatus); $i++){
														$html .= '<div class="campaign-status-item">
														<div class="campaign-status-item-top clear clearfix">
															<div class="no-index mySortableHandler">'.($i+1).'</div>
															<div class="desc">
																<span class="campaign-status-item-title">'.$arrCampaignStatus[$i]['title'].'</span>
																<input type="text" class="campaign-status-item-input hidden" name="campaign_status_title[]" value="'.$arrCampaignStatus[$i]['title'].'" style="width:100%" />
																<input type="hidden" name="campaign_status_id[]" value="'.$arrCampaignStatus[$i][$clsBusinessCampaignStatus->pkey].'" />
															</div>
															<div class="btn-groups">
																<button type="button" class="btn-tiny js_edit-campaign-status-item">'.$core->makeIcon('pencil').'</button>
																<button type="button" class="btn-tiny js_delete-campaign-status-item">'.$core->makeIcon('trash').'</button>
																<button type="button" class="btn-tiny js_toggle-campaign-status-item">'.$core->makeIcon('expand').'</button>
															</div>
														</div>
														<div class="campaign-status-item-bottom hidden">
															<div class="row">
																<div class="col-xs-9">
																	<textarea class="form-control campaign-status-item-desc" name="campaign_status_description[]">'.$arrCampaignStatus[$i]['description'].'</textarea>
																</div>
																<div class="col-xs-3">
																	<input type="color" class="form-control" name="campaign_status_color[]" value="'.$arrCampaignStatus[$i]['color'].'" />
																</div>
															</div>
														</div>
													</div>';	
													}
												}
											}else{
												$arrCampaignStatus[] = 'Thông tin khách hàng';
												$arrCampaignStatus[] = 'Tiếp cận khách hàng';
												$arrCampaignStatus[] = 'Gặp mặt và chăm sóc';
												$arrCampaignStatus[] = 'Chốt sales- bán hàng';
												for($i=0;$i<count($arrCampaignStatus); $i++){
													$html .= '<div class="campaign-status-item">
														<div class="campaign-status-item-top clear clearfix">
															<div class="no-index mySortableHandler">'.($i+1).'</div>
															<div class="desc">
																<span class="campaign-status-item-title">'.$arrCampaignStatus[$i].'</span>
																<input type="text" class="campaign-status-item-input hidden" name="campaign_status_title[]" value="'.$arrCampaignStatus[$i].'" style="width:100%" />
																<input type="hidden" name="campaign_status_id[]" value="0" /> 
															</div>
															<div class="btn-groups">
																<button type="button" class="btn-tiny js_edit-campaign-status-item">'.$core->makeIcon('pencil').'</button>
																<button type="button" class="btn-tiny js_delete-campaign-status-item">'.$core->makeIcon('trash').'</button>
																<button type="button" class="btn-tiny js_toggle-campaign-status-item">'.$core->makeIcon('expand').'</button>
															</div>
														</div>
														<div class="campaign-status-item-bottom hidden">
															<div class="row">
																<div class="col-xs-9">
																	<textarea class="form-control campaign-status-item-desc" name="campaign_status_description[]"></textarea>
																</div>
																<div class="col-xs-3">
																	<input type="color" class="form-control" name="campaign_status_color[]" />
																</div>
															</div>
														</div>
													</div>';	
												}
											}
										$html .= '</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="box light bordered" hide>
						<div class="box-title">
							<div class="caption">
								<span class="bold uppercase">'.__('TargetCustomers').'</span>
							</div>
							<div class="pull-right">
								<a href="javascript:void(0);" class="btn-outline pull-right hidebox">'.$core->makeIcon('compress').'</a>
							</div>
						</div>
						<div class="box-body form-horizontal">
							<div class="box light bordered">
								<div class="box-title">
									<div class="caption">
										<span class="uppercase bold">'.__('SystemFields').'</span>
									</div>
								</div>
								<div class="box-body">
									<div class="form-group">
										<div class="col-md-4 col-sm-4 col-xs-4 groupcampain_item" column="name">
											<div class="col-md-4 text-right">
												<h5>'.__('Name').' <input type="checkbox" class="chkcampain_item" '.($clsBusinessCampaign->getValueFilter($arrFilters,'name','status')?'checked="checked"':'').' value="1"></h5>
											</div>
											<div class="col-md-8">
												<input class="form-control ipncampain_item" value="'.$clsBusinessCampaign->getValueFilter($arrFilters,'name','searchvalue').'">
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-4 groupcampain_item" column="status_id">
											<div class="col-md-4 text-right">
												<h5>'.__('Status').' <input type="checkbox" class="chkcampain_item" '.($clsBusinessCampaign->getValueFilter($arrFilters,'status_id','status')?'checked="checked"':'').' value="1"></h5>
											</div>
											<div class="col-md-8">
												<select class="form-control ipncampain_item">
													'.$clsISO->getSelectByPropertyTypeNotTitle('_COMPANY_STATUS',$clsBusinessCampaign->getValueFilter($arrFilters,'status_id','searchvalue')).'
												</select>
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-4 groupcampain_item" column="priority">
											<div class="col-md-4 text-right">
												<h5>'.__('Priority ').'
												<input type="checkbox" class="chkcampain_item" '.($clsBusinessCampaign->getValueFilter($arrFilters,'priority','status')?'checked="checked"':'').' value="1"></h5>
											</div>
											<div class="col-md-8">
												<select class="form-control ipncampain_item">
													'.CRM::getFORMSelectPriority($clsBusinessCampaign->getValueFilter($arrFilters,'priority','searchvalue')).'
												</select>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-4 col-sm-4 col-xs-4 groupcampain_item" column="email">
											<div class="col-md-4 text-right">
												<h5>'.__('Email').' <input type="checkbox" class="chkcampain_item" '.($clsBusinessCampaign->getValueFilter($arrFilters,'email','status')?'checked="checked"':'').' value="1"></h5>
											</div>
											<div class="col-md-8">
												<input class="form-control ipncampain_item" value="'.$clsBusinessCampaign->getValueFilter($arrFilters,'email','searchvalue').'">
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-4 groupcampain_item" column="phone">
											<div class="col-md-4 text-right">
												<h5>'.__('Phone').' <input type="checkbox" class="chkcampain_item" '.($clsBusinessCampaign->getValueFilter($arrFilters,'phone','status')?'checked="checked"':'').' value="1"></h5>
											</div>
											<div class="col-md-8">
												<input class="form-control ipncampain_item" value="'.$clsBusinessCampaign->getValueFilter($arrFilters,'phone','searchvalue').'">
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-4 groupcampain_item" column="country_id">
											<div class="col-md-4 text-right">
												<h5>'.__('Country').' 
												<input type="checkbox" '.($clsBusinessCampaign->getValueFilter($arrFilters,'country_id','status')?'checked="checked"':'').' class="chkcampain_item" value="1"></h5>
											</div>
											<div class="col-md-8">
												<select class="form-control ipncampain_item">
													'.$clsISO->getOptionCountry($clsBusinessCampaign->getValueFilter($arrFilters,'country_id','searchvalue')).'
												</select>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-4 col-sm-4 col-xs-4 groupcampain_item" column="type_id">
											<div class="col-md-4 text-right"><h5>'.__('ContactType').'<input type="checkbox" class="chkcampain_item" '.($clsBusinessCampaign->getValueFilter($arrFilters,'type_id','status')?'checked="checked"':'').' value="1"></h5>
											</div>
											<div class="col-md-8">
												<select class="form-control ipncampain_item">
													'.$clsISO->getSelectByPropertyTypeNotTitle('_CONTACT_TYPE',$clsBusinessCampaign->getValueFilter($arrFilters,'type_id','searchvalue')).'
												</select>
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-4 groupcampain_item"  column="automation_id">
											<div class="col-md-4 text-right"><h5>Chiến dịch<input type="checkbox" class="chkcampain_item" '.($clsBusinessCampaign->getValueFilter($arrFilters,'automation_id','status')?'checked="checked"':'').' value="1"></h5>
											</div>
											<div class="col-md-8">
												<select class="form-control ipncampain_item">';
										$clsAutomation = new Automation();
										$cond = "is_trash=0 and cat_id='2'";
										$lstAutomaions = $clsAutomation->getAll($cond." order by reg_date DESC","automation_id,name");
										if(!empty($lstAutomaions)){
											foreach($lstAutomaions as $k_auto=>$oneAuto){
												$html .= '<option value="'.$oneAuto['automation_id'].'" '.($clsBusinessCampaign->getValueFilter($arrFilters,'automation_id','searchvalue')==$oneAuto['automation_id']?'selected':'').'>'.$oneAuto['name'].'</option>';
											}
										}
											$html .= '		
												</select>
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-4">
											<div class="col-md-3"></div>
											<div class="col-md-9">&nbsp;</div>
										</div>
									</div>
								</div>
							</div>';
							$clsCRMField = new CRMField();
							$clsCRMFieldGroup = new CRMFieldGroup(); 
							$lstFieldGroup = $clsCRMFieldGroup->GetAll("is_trash=0 and status='1' and main_group='0' order by order_no ASC","id,name");
							if(!empty($lstFieldGroup)){
								foreach($lstFieldGroup as $fieldgroup){
									$fieldgroup_id = $fieldgroup[$clsCRMFieldGroup->pkey];
									$lstField = $clsCRMField->GetAll("fieldgroup_id='{$fieldgroup_id}' and status='1' order by order_no ASC","crm_field_id,value");
									if(!empty($lstField)){ $ii=1; // Init
										$html .= '<div class="box light bordered">
										<div class="box-title">
											<div class="caption">
												<span class="uppercase bold">'.$fieldgroup['name'].'</span>
											</div>
										</div>
										<div class="box-body">
											<div class="form-group">';
											foreach($lstField as $field){
												$value = $field['value'];
												$value = !empty($value) ? json_decode($value, true) : array();
												$html .= '<div class="col-md-4 col-sm-4 col-xs-4 groupcampain_item CustomCRMField" column="'.$value['fieldname'].'" crm_field_id="'.$field[$clsCRMField->pkey].'">
													<div class="col-md-4 text-right">
														<h5>'.$value['name'].' <input type="checkbox" class="chkcampain_item" '.($clsBusinessCampaign->getValueFilter($arrFilters,$value['fieldname'],'status')?'checked="checked"':'').' value="1"></h5>	
													</div>
													<div class="col-md-8">
														'.$clsCRMField->renderHTMLSimple($field[$clsCRMField->pkey],$value,$clsBusinessCampaign->getValueFilter($arrFilters,$value['fieldname'],'searchvalue'),'form-control ipncampain_item').'
													</div>
												</div>';
												$html .= ($ii%3==0?'</div><div class="form-group">':'');
												++$ii;
											}
										$html .= '</div>
											</div>
										</div>';
									} 
								}
							}
						$html .= '</div>
					</div>
					<div class="form-group text-center">
						<button type="button" class="btn btn-success saveCRMCampain" business_campaign_id="'.$business_campaign_id.'"><span>'.$core->makeIcon('check',__($business_campaign_id>0?'UpdateCampaign':'AddCampaign')).'</span></button>
						<button type="button" class="btn btn-primary saveCRMCampain showMatchingPotential" business_campaign_id="'.$business_campaign_id.'">'.$core->makeIcon('search',__('ShowMatchingRecords')).'</button>
					</div>
				</form>
			</div>
		</div>
		<div class="clearfix"><div>
		<form method="post" action="#">
			<div class="holderPotentialCampain"></div>
		</form>
	</div>';
	echo $html; die();
}
function default_ajAddPotentialCampaign(){
	global $adminid,$core,$clsISO;
	if($clsISO->validateLoginInformation()==0){echo('');die();}
	$clsProperty = new Property();
	$clsCountry = new Country();
	$clsBusinessCampaign = new BusinessCampaign();
	#
	$business_campaign_id = (int) Input::post('business_campaign_id',0);
	$oneBusinessCampaign = array();
	$arrFilters = array();
	if($business_campaign_id > 0){
		$oneBusinessCampaign = $clsBusinessCampaign->GetOne($business_campaign_id);
		$arrFilters = @json_decode($oneBusinessCampaign['filters'], true);
	}
	$html = '<div class="mg-wrapper">
		<div class="box light">
			<div class="box-title">
				'.CRM::renderHTMLButtonBack(array('page'=>'campaign','action'=>'add')).'
				<div class="caption">
					<span class="uppercase">'.__('AddPotentialCampaign').'</span>
				</div>
			</div>
			<div class="box-body">
				<form method="post" action="" enctype="multipart/form-data" id="frmCampain__'.$business_campaign_id.'">
					<div class="box light bordered" hide>
						<div class="box-title">
							<div class="caption">
								<span class="bold uppercase">'.__('Search').'</span>
							</div>
							<div class="pull-right">
								<a href="javascript:void(0);" class="btn-outline pull-right hidebox">'.$core->makeIcon('compress').'</a>
							</div>
						</div>
						<div class="box-body form-horizontal">
							<div class="box light bordered">
								<div class="box-title">
									<div class="caption">
										<span class="uppercase bold">'.__('SystemFields').'</span>
									</div>
								</div>
								<div class="box-body">
									<div class="form-group">
										<div class="col-md-4 col-sm-4 col-xs-4 groupcampain_item" column="name">
											<div class="col-md-4 text-right">
												<h5>'.__('Name').' <input type="checkbox" class="chkcampain_item" '.($clsBusinessCampaign->getValueFilter($arrFilters,'name','status')?'checked="checked"':'').' value="1"></h5>
											</div>
											<div class="col-md-8">
												<input class="form-control ipncampain_item" value="'.$clsBusinessCampaign->getValueFilter($arrFilters,'name','searchvalue').'">
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-4 groupcampain_item" column="status_id">
											<div class="col-md-4 text-right">
												<h5>'.__('Status').' <input type="checkbox" class="chkcampain_item" '.($clsBusinessCampaign->getValueFilter($arrFilters,'status_id','status')?'checked="checked"':'').' value="1"></h5>
											</div>
											<div class="col-md-8">
												<select class="form-control ipncampain_item">
													'.$clsISO->getSelectByPropertyTypeNotTitle('_COMPANY_STATUS',$clsBusinessCampaign->getValueFilter($arrFilters,'status_id','searchvalue')).'
												</select>
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-4 groupcampain_item" column="priority">
											<div class="col-md-4 text-right">
												<h5>'.__('Priority ').'
												<input type="checkbox" class="chkcampain_item" '.($clsBusinessCampaign->getValueFilter($arrFilters,'priority','status')?'checked="checked"':'').' value="1"></h5>
											</div>
											<div class="col-md-8">
												<select class="form-control ipncampain_item">
													'.CRM::getFORMSelectPriority($clsBusinessCampaign->getValueFilter($arrFilters,'priority','searchvalue')).'
												</select>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-4 col-sm-4 col-xs-4 groupcampain_item" column="email">
											<div class="col-md-4 text-right">
												<h5>'.__('Email').' <input type="checkbox" class="chkcampain_item" '.($clsBusinessCampaign->getValueFilter($arrFilters,'email','status')?'checked="checked"':'').' value="1"></h5>
											</div>
											<div class="col-md-8">
												<input class="form-control ipncampain_item" value="'.$clsBusinessCampaign->getValueFilter($arrFilters,'email','searchvalue').'">
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-4 groupcampain_item" column="phone">
											<div class="col-md-4 text-right">
												<h5>'.__('Phone').' <input type="checkbox" class="chkcampain_item" '.($clsBusinessCampaign->getValueFilter($arrFilters,'phone','status')?'checked="checked"':'').' value="1"></h5>
											</div>
											<div class="col-md-8">
												<input class="form-control ipncampain_item" value="'.$clsBusinessCampaign->getValueFilter($arrFilters,'phone','searchvalue').'">
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-4 groupcampain_item" column="country_id">
											<div class="col-md-4 text-right">
												<h5>'.__('Country').' 
												<input type="checkbox" '.($clsBusinessCampaign->getValueFilter($arrFilters,'country_id','status')?'checked="checked"':'').' class="chkcampain_item" value="1"></h5>
											</div>
											<div class="col-md-8">
												<select class="form-control ipncampain_item">
													'.$clsISO->getOptionCountry($clsBusinessCampaign->getValueFilter($arrFilters,'country_id','searchvalue')).'
												</select>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-4 col-sm-4 col-xs-4 groupcampain_item" column="type_id">
											<div class="col-md-4 text-right"><h5>'.__('ContactType').'<input type="checkbox" class="chkcampain_item" '.($clsBusinessCampaign->getValueFilter($arrFilters,'type_id','status')?'checked="checked"':'').' value="1"></h5>
											</div>
											<div class="col-md-8">
												<select class="form-control ipncampain_item">
													'.$clsISO->getSelectByPropertyTypeNotTitle('_CONTACT_TYPE',$clsBusinessCampaign->getValueFilter($arrFilters,'type_id','searchvalue')).'
												</select>
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-4 groupcampain_item"  column="automation_id">
											<div class="col-md-4 text-right"><h5>Chiến dịch<input type="checkbox" class="chkcampain_item" '.($clsBusinessCampaign->getValueFilter($arrFilters,'automation_id','status')?'checked="checked"':'').' value="1"></h5>
											</div>
											<div class="col-md-8">
												<select class="form-control ipncampain_item">';
										$clsAutomation = new Automation();
										$cond = "is_trash=0 and cat_id='2'";
										$lstAutomaions = $clsAutomation->getAll($cond." order by reg_date DESC","automation_id,name");
										if(!empty($lstAutomaions)){
											foreach($lstAutomaions as $k_auto=>$oneAuto){
												$html .= '<option value="'.$oneAuto['automation_id'].'" '.($clsBusinessCampaign->getValueFilter($arrFilters,'automation_id','searchvalue')==$oneAuto['automation_id']?'selected':'').'>'.$oneAuto['name'].'</option>';
											}
										}
											$html .= '		
												</select>
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-4">
											<div class="col-md-3"></div>
											<div class="col-md-9">&nbsp;</div>
										</div>
									</div>
								</div>
							</div>';
							$clsCRMField = new CRMField();
							$clsCRMFieldGroup = new CRMFieldGroup(); 
							$lstFieldGroup = $clsCRMFieldGroup->GetAll("is_trash=0 and status='1' and main_group='0' order by order_no ASC","id,name");
							if(!empty($lstFieldGroup)){
								foreach($lstFieldGroup as $fieldgroup){
									$fieldgroup_id = $fieldgroup[$clsCRMFieldGroup->pkey];
									$lstField = $clsCRMField->GetAll("fieldgroup_id='{$fieldgroup_id}' and status='1' order by order_no ASC","crm_field_id,value");
									if(!empty($lstField)){ $ii=1; // Init
										$html .= '<div class="box light bordered">
										<div class="box-title">
											<div class="caption">
												<span class="uppercase bold">'.$fieldgroup['name'].'</span>
											</div>
										</div>
										<div class="box-body">
											<div class="form-group">';
											foreach($lstField as $field){
												$value = $field['value'];
												$value = !empty($value) ? json_decode($value, true) : array();
												$html .= '<div class="col-md-4 col-sm-4 col-xs-4 groupcampain_item CustomCRMField" column="'.$value['fieldname'].'" crm_field_id="'.$field[$clsCRMField->pkey].'">
													<div class="col-md-4 text-right">
														<h5>'.$value['name'].' <input type="checkbox" class="chkcampain_item" '.($clsBusinessCampaign->getValueFilter($arrFilters,$value['fieldname'],'status')?'checked="checked"':'').' value="1"></h5>	
													</div>
													<div class="col-md-8">
														'.$clsCRMField->renderHTMLSimple($field[$clsCRMField->pkey],$value,$clsBusinessCampaign->getValueFilter($arrFilters,$value['fieldname'],'searchvalue'),'form-control ipncampain_item').'
													</div>
												</div>';
												$html .= ($ii%3==0?'</div><div class="form-group">':'');
												++$ii;
											}
										$html .= '</div>
											</div>
										</div>';
									} 
								}
							}
						$html .= '</div>
					</div>
					<div class="form-group text-center">
						<button type="button" class="btn btn-primary saveCRMCampain showMatchingPotential" business_campaign_id="'.$business_campaign_id.'">'.$core->makeIcon('search',__('ShowMatchingRecords')).'</button>
					</div>
				</form>
			</div>
		</div>
		<div class="clearfix"><div>
		<form method="post" action="#">
			<div class="holderPotentialCampain"></div>
		</form>
	</div>';
	echo $html; die();
}
function default_ajOpenViewCRMCampaign(){
	global $adminid, $core, $dbconn, $clsISO;
	$clsproperty = new Property();
	$clsPotential = new Potential();
	$clsBusinessCampaign = new BusinessCampaign();
	$clsBusinessCampaignStatus = new BusinessCampaignStatus();
	$clsBusinessCampaignPotential = new BusinessCampaignPotential();
	#
	$business_campaign_id = Input::post('business_campaign_id');
	$lstCampaignStatus = $clsBusinessCampaignStatus->GetAll("business_campaign_id='{$business_campaign_id}' order by order_no ASC");
	$totalCampaignStatus = !empty($lstCampaignStatus) ? count($lstCampaignStatus) : 0;
	$html = '<div class="modal right fade" id="OpenViewCRMCampaign_'.$business_campaign_id.'" tabindex="-1" role="dialog" aria-labelledby="OpenViewCRMCampaign_'.$business_campaign_id.'">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<button type="button" class="close closeEv" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<div class="modal-header">
					<div class="links-bar-create-edit">
						<div class="profile-photo-create-edit">
							<img src="'._ICON_GENERAL.'" width="48px" />
						</div>
					</div>
					<div class="head">
						<div class="subtitle">'.__('Campaigns').'</div>
						<div class="title">'.$clsBusinessCampaign->getTitle($business_campaign_id).'</div>
					</div>
				</div>
				<div class="modal-body">
					<style type="text/css">
						#kanbanTbl .stage-lbl{color: #fff;line-height: 20px;background-color:#4a8cf7;}
						.stage-lbl-txt{display: -webkit-box; -webkit-box-align: center; align-items: center; -webkit-box-pack: center; justify-content: center; padding:10px 0 10px 10px; min-height:62px;}
						#kanbanTbl thead tr th:first-child .stage-lbl {border-top-left-radius: .75em;border-bottom-left-radius: .75em;}
						#kanbanTbl thead tr th:last-child .stage-lbl {border-top-right-radius: .75em;border-bottom-right-radius: .75em;}
						#kanbanTbl .pipeline-arrow {width: 0;height: 0;border-top:31px solid transparent;border-bottom: 31px solid transparent;border-left: 31px solid #fff;float: left;}
						#kanbanTbl .arrow-inner {width:0; height:0; border-top:22px solid transparent; border-bottom:22px solid transparent;border-left:22px solid #4a8cf7; margin-top:-22px; margin-left:-31px;}
						#kanbanTbl .stage-off {background-color:#aaa; color: #fff;}
						#kanbanTbl .stage-cntr {height:600px; overflow-y:auto; overflow-x:hidden;}
						#kanbanTbl .kanban-card {background-color:#fff; border:1px solid #c5c5c5; min-height:90px; min-width:150px; padding: 10px;margin:0 5px 5px 5px; border-radius:2px; position:relative;}
						#kanbanTbl .kanban-card .card-control {position:absolute; right:0px; top:5px; cursor:pointer;}
						#kanbanTbl .kanban-card .card-control > a{display:block; width:20px; height:20px; text-align:center; padding:5px;}
						#kanbanTbl .kanban-card .card-control > a{ color:#333;}
						#kanbanTbl .kanban-card .card-line {font-size:13px;margin-bottom:5px; max-width:100%;}
						#kanbanTbl .kanban-card .card-line{overflow:hidden;text-overflow: ellipsis;}
						#kanbanTbl .kanban-card .card-warning{float:right; margin-right:2px; cursor:default; position:absolute; bottom:5px;right:1px;}
					</style>
					<table id="kanbanTbl" cellspacing="0" cellpadding="0" width="100%">';
						if(!empty($lstCampaignStatus)){
							$ii=0;
							$html.= '<thead><tr>';
							foreach($lstCampaignStatus as $status){
								$campaign_status_id = $status[$clsBusinessCampaignStatus->pkey];
								$totalItem = $clsBusinessCampaignPotential->countItem("business_campaign_id='{$business_campaign_id}' 
								and (status_id='{$campaign_status_id}'".($ii==0?" or status_id='0'":"").")");
								$html .= '<th width="'.(100/$totalCampaignStatus).'%">
									'.($ii>0?'<div class="pipeline-arrow">
										<div class="arrow-inner" '.(!empty($status['color'])?'style="border-left:22px solid '.$status['color'].'"':'').'></div>
									</div>':'').'
									<div class="stage-lbl" '.(!empty($status['color'])?'style="background-color:'.$status['color'].'"':'').'>
										<div class="stage-lbl-txt">'.$status['title'].' 
											<span class="stage-count badge tipped-bottom" id="stage-count__'.$campaign_status_id.'">'.$totalItem.'</span>
											<a class="icon icon-export" target="_blank" href="'.PCMS_URL.'/inc/export.php?t=campaign&campaign_id='.$business_campaign_id.'&status_id='.$campaign_status_id.'"></a>
										</div>
									</div>
								</th>';
								++$ii;
							}
							$html.= '</tr></thead>';
							$html.= '<tbody><tr>';
							$ii = 0;
							foreach($lstCampaignStatus as $status){
								$campaign_status_id = $status[$clsBusinessCampaignStatus->pkey];
								$lstPotential = $dbconn->GetAll("select t1.potential_id,t1.name,t1.email,t1.phone,t1.status_id,t1.type_id  
								from ".$clsPotential->tbl." as t1 
								inner join ".$clsBusinessCampaignPotential->tbl." as t2 on t1.potential_id=t2.potential_id 
								WHERE t2.business_campaign_id='{$business_campaign_id}' and (t2.status_id='{$campaign_status_id}'".($ii==0?" or t2.status_id='0'":"").") order by t1.reg_date DESC");
								$html .= '<td>
									<div class="stage-cntr sortable sortableContainer" id="listPotentialCampaignInStatus_'.$campaign_status_id.'">';
									foreach($lstPotential as $potential){
										$type_id = $potential['type_id'];
										$status_id = $potential['status_id'];
										$potential_id = $potential[$clsPotential->pkey];
										#
										$html .= '<div class="kanban-card" id="'.$potential_id.'">
											<div class="card-control" title="'.__('Actions').'" potential_id="'.$potential_id.'" campaign_status_id="'.$campaign_status_id.'" id="ul-'.$potential_id.'">
												<a class="dropdown-toggle" data-toggle="dropdown">
													<i class="fa fa-ellipsis-v" aria-hidden="true"></i>
												</a>
												<ul class="dropdown-menu icon" style="right:0; left:auto">
													'.$clsPotential->getMenuAction($potential_id, $status_id, $type_id).'
												</ul>
											</div>
											<div class="card-line">
												<a class="nopjax inblade">'.$potential['name'].'</a>
											</div>
											<div class="card-line">
												<i class="fa fa-envelope-o"></i> '.$potential['email'].'
											</div>
											<div class="card-warning">
												<img class="opp-next-action" src="'.URL_IMAGES.'/warning.png">
											</div>
											<div class="card-line">
												<i class="fa fa-phone"></i> '.$potential['phone'].'
											</div>
										</div>';
									}
								$html .= '
									</div>
									<script type="text/javascript">
										$(function(){
											$("#listPotentialCampaignInStatus_'.$campaign_status_id.'" ).sortable({
												scroll: true,
												connectWith: ".sortableContainer",
												update: function (event, ui) {
													var list_potential_array = $(this).sortable(\'toArray\'),
														$_adata = {
															\'campaign_status_id\':\''.$campaign_status_id.'\',
															\'business_campaign_id\':\''.$business_campaign_id.'\',
															\'list_potential_array\':list_potential_array
														};
													$.post("'.PCMS_URL.'/index.php?mod=crm&act=update_campaign_potential_change_status",$_adata,function(html){
														$(\'#stage-count__'.$campaign_status_id.'\').text(html);
													});
												}
											}).disableSelection();
										});
									</script>
								</td>';
								++$ii;
							}
							$html.= '</tr><tbody>';
						}
					$html .= '
					</table>
				</div>
			</div>
		</div>
	</div>';
	// Output
	echo $html; die();
}
function default_update_campaign_potential_change_status(){
	global $adminid,$core,$clsISO;
	$clsPotential = new Potential();
	$clsBusinessCampaignStatus = new BusinessCampaignStatus();
	$clsBusinessCampaignPotential = new BusinessCampaignPotential();
	#
	$msg = '_error';
	$campaign_status_id = Input::post('campaign_status_id');
	$business_campaign_id = Input::post('business_campaign_id');
	$list_potential_array = Input::post('list_potential_array', array());
	if(!empty($list_potential_array)){
		$msg = '_success';
		$clsBusinessCampaignPotential->updateByCond("business_campaign_id='{$business_campaign_id}' 
		and potential_id in (".implode(",",$list_potential_array).")", array(
			'status_id'	=> $campaign_status_id
		));
	}
	$totalItem = $clsBusinessCampaignPotential->countItem("business_campaign_id='{$business_campaign_id}' and status_id='{$campaign_status_id}'");
	echo $totalItem; die();
}
function default_ajLoadListPotentialCampain(){
	global $adminid,$core,$clsISO;
	if($clsISO->validateLoginInformation()==0){echo('');die();}
	$clsproperty = new Property();
	$clsPotential = new Potential();
	$clsCRMFieldValue = new CRMFieldValue();
	$clsAutomation = new Automation();
	
	$html = '';
	$currentPage = (int) Input::post('currentPage',1);
	$number_per_page = (int) Input::post('number_per_page',20);
	#---
	$searchconds = Input::post('searchcond');
	$business_campaign_id = Input::post('business_campaign_id',0,true);
	
	$cond = "is_trash=0";
	if($business_campaign_id > 0){
		$cond .= " and potential_id not in (
			select potential_id from ".DB_PREFIX."business_campaign_potential 
			where business_campaign_id='{$business_campaign_id}'
		)";
	}
	if(!empty($searchconds) && is_array($searchconds)){
		foreach($searchconds as $c){
			if($c['status']==1 && !empty($c['searchvalue'])){
				if($c['isCustomCRMField']==0){
					if($c['column']=='automation_id'){
						$automation_id_select = $c['searchvalue'];
						$contacts_select = $clsAutomation->getOneField("contacts", $automation_id_select);
						$contacts_select = !empty($contacts_select) ? @json_decode($contacts_select, true) : array();
						if(!empty($contacts_select)){
							$cond .= " and potential_id in (".implode(',',$contacts_select).")";
						}
					}elseif($c['column']=='name'){
						$cond .= " and ({$c['column']} like '%".$c['searchvalue']."%' 
							or name_slug like '%".$core->replaceSpace($c['searchvalue'])."%'
						)";
					}else{
						if($c['nodetype']=='INPUT' || $c['nodetype']=='TEXTAREA'){
							$cond .= " and {$c['column']} like '%".$c['searchvalue']."%'";
						}else{
							$cond .= " and {$c['column']}='{$c['searchvalue']}'";
						}
					}
				}else{
					if($c['nodetype']=='INPUT' || $c['nodetype']=='TEXTAREA'){
						$cond .= " and potential_id in (
							select potential_id 
							from ".$clsCRMFieldValue->tbl." 
							where crm_field_id='{$c['crm_field_id']}' and field_value like '%{$c['searchvalue']}%'
						)";
					}else{
						$cond .= " and potential_id in (
							select potential_id 
							from ".$clsCRMFieldValue->tbl." 
							where crm_field_id='{$c['crm_field_id']}' and field_value='{$c['searchvalue']}'
						)";
					}
				}
			}
		}
	}
	//var_dump($cond); die();
	$totalRecord = $clsPotential->countItem($cond);
	$totalPage = ceil($totalRecord/$number_per_page);
	$offset = ($currentPage-1)*$number_per_page;
	$limitCond = " limit {$offset},{$number_per_page}";
	$lstPotential = $clsPotential->GetAll($cond." order by reg_date ASC".$limitCond);
	if(!empty($lstPotential)){
		$html .= '
		<div class="box light bordered">
			<div class="box-title">
				<div class="caption">
					<span class="uppercase bold">'.__('ListContacts').'</span>
				</div>
			</div>
			<div class="box-body">
				<table border="0 id="ListPotential" width="100%" class="table table-striped table-hover table-responsive">
					<thead><tr>
						<th width="3.5%" class="text-center">
							<div class="custom-checkbox-wrapper core-checkbox-custom">
								<label>
									<input type="checkbox" value="1" class="js_choice-contact-all">
									<span class="custom-checkbox custom-icon"></span>
								</label>
							</div>
						</th>
						<th width="5%" class="text-center">No.</th>
						<th width="35%">'.__('Fullname').'</th>
						<th width="15%">'.__('_Status').'</th>
						<th width="15%">'.__('Email').'</th>
						<th width="15%">'.__('PhoneNumber').'</th>
						<th width="15%">'.__('Type').'</th>
					</tr></thead>';
					if(!empty($lstPotential)){
						foreach($lstPotential as $potential){
							$potential_id = $potential[$clsPotential->pkey];
							$props = 'potential_id="'.$potential_id.'"';
							$html .= '<tr class="trPotential">
								<td class="text-center">
									<div class="custom-checkbox-wrapper core-checkbox-custom">
										<label>
											<input type="checkbox" name="potential[]" value="'.$potential_id.'" class="js_choice-contact-one">
											<span class="custom-checkbox custom-icon"></span>
										</label>
									</div>
								</td>
								<td data-label="No." class="text-center">'.$potential_id.'</td>
								<td data-label="'.__('Fullname').'"><a href="javascript:void(0);" class="ajOpenPotential" '.$props.'>'.$potential['name'].'</a></td>
								<td data-label="'.__('_Status').'">'.$clsPotential->getStatus($potential_id, $potential).'</td>
								<td data-label="'.__('Email').'">'.$potential['email'].'</td>
								<td data-label="'.__('PhoneNumber').'">'.$potential['phone'].'</td>
								<td data-label="'.__('Type').'">'.$clsPotential->getHtmlType($potential_id, $potential).'</td>
							</tr>';
						}
					}
			$html .= '</table>
				'.($totalPage>1?'<div class="easyui-pagination" id="PagePotentialCampain" pageNumber="'.$currentPage.'" pageList="[10,20]"></div>':'').'
			</div>
			'.($totalRecord>0?'<div class="box-end text-center">
				<button class="btn btn-danger js_add-contact-campaign" business_campaign_id="'.$business_campaign_id.'" disabled="disabled">'.$core->makeIcon('plus-circle',__('AddContactToCampaign')).'(<span class="js_total-choice-contact">0</span>)</button>
			</div>':'').'
		</div>';
	}else{
		$html = '_empty';
	}
	// output
	echo @json_encode(array(
		'currentPage'	=> $currentPage,
		'number_per_page'	=> $number_per_page,
		'totalPage'	=> $totalPage,
		'totalRecord'	=> $totalRecord,
		'html'	=> $html
	)); die();
}
function default_ajSaveBusinessCampaign(){
	global $adminid,$core,$clsISO;
	if($clsISO->validateLoginInformation()==0){echo('');die();}
	$clsProperty = new Property();
	$clsPotential = new Potential();
	$clsCRMFieldValue = new CRMFieldValue();
	$clsBusinessCampaign = new BusinessCampaign();
	$clsBusinessCampaignStatus = new BusinessCampaignStatus();
	$clsBusinessCampaignPotential = new BusinessCampaignPotential();
	$business_campaign_id = (int) Input::post('business_campaign_id',0);
	$filters = array();
	if(Input::exists('filters') && !empty(Input::post('filters'))){
		$filters = @json_decode(Input::post('filters'), true);
	}
	$IsUpdate = 0;
	$msg = '_error';
	if($business_campaign_id==0){
		$action = '_add';
		$business_campaign_id = $clsBusinessCampaign->getMaxId();
		if($clsBusinessCampaign->insert(array(
			'business_campaign_id'	=> $business_campaign_id,
			'type'	=> 'CRM',
			'title'	=> Input::post('title'),
			'slug'	=> $core->replaceSpace(Input::post('title')),
			'intro'	=> Input::post('intro'),
			'color'	=> Input::post('color'),
			'start_date'	=> $clsISO->convertTextToTime(Input::post('start_date')),
			'end_date'	=> $clsISO->convertTextToTime(Input::post('end_date')),
			'admin_list'	=> $clsISO->makeSlashListFromArray(Input::post('admins')),
			'reg_date'	=> time(),
			'upd_date'	=> time(),
			'user_id'	=> $adminid,
			'user_id_update'	=> $adminid,
			'filters' => @json_encode(CRM::convertToArray($filters))
		))){
			$msg = '_success';
			// Add new status
			$campaign_status_title = Input::post('campaign_status_title');
			$campaign_status_description = Input::post('campaign_status_description');
			$campaign_status_color = Input::post('campaign_status_color');
			for($i=0; $i<count($campaign_status_title); $i++){
				$clsBusinessCampaignStatus->insert(array(
					'id'	=> $clsBusinessCampaignStatus->getMaxId(),
					'business_campaign_id'	=> $business_campaign_id,
					'title'	=> $campaign_status_title[$i],
					'description'	=> $campaign_status_description[$i],
					'color'	=> $campaign_status_color[$i],
					'order_no'	=> ($i+1)
				));
			}
			#record log
			$log_message = ''.__("AddNewCampaign").': '.Input::post('title');
			$clsISO->logs($clsBusinessCampaign->tbl,$clsBusinessCampaign->pkey, $business_campaign_id, $log_message); 
		}
	}else{
		$action = '_edit';
		if($clsBusinessCampaign->updateOne($business_campaign_id, array(
			'type'	=> 'CRM',
			'title'	=> Input::post('title'),
			'slug'	=> $core->replaceSpace(Input::post('title')),
			'intro'	=> Input::post('intro'),
			'color'	=> Input::post('color'),
			'start_date'	=> $clsISO->convertTextToTime(Input::post('start_date')),
			'end_date'	=> $clsISO->convertTextToTime(Input::post('end_date')),
			'admin_list'	=> $clsISO->makeSlashListFromArray(Input::post('admins')),
			'upd_date'	=> time(),
			'user_id_update'	=> $adminid,
			'filters' => @json_encode(CRM::convertToArray($filters))
		))){
			$msg = '_success';
			// Update status
			$campaign_status_id = Input::post('campaign_status_id');
			$campaign_status_title = Input::post('campaign_status_title');
			$campaign_status_description = Input::post('campaign_status_description');
			$campaign_status_color = Input::post('campaign_status_color');
			if(!empty($campaign_status_id)){
				$campaign_status_id_not_in = @array_values(array_diff($campaign_status_id, array(0)));
				if(!empty($campaign_status_id_not_in)){
					$clsBusinessCampaignStatus->deleteByCond("business_campaign_id='{$business_campaign_id}' 
					and id not in (".implode(",",$campaign_status_id_not_in).")");
				}else{
					$clsBusinessCampaignStatus->deleteByCond("business_campaign_id='{$business_campaign_id}'");
				}
				for($i=0; $i<count($campaign_status_id); $i++){
					if(isset($campaign_status_id[$i]) && $campaign_status_id[$i]>0){
						$clsBusinessCampaignStatus->updateOne($campaign_status_id[$i], array(
							'title'	=> $campaign_status_title[$i],
							'description'	=> $campaign_status_description[$i],
							'color'	=> $campaign_status_color[$i],
							'order_no'	=> ($i+1)
						));
					}else{
						$clsBusinessCampaignStatus->insert(array(
							'id'	=> $clsBusinessCampaignStatus->getMaxId(),
							'business_campaign_id'	=> $business_campaign_id,
							'title'	=> $campaign_status_title[$i],
							'description'	=> $campaign_status_description[$i],
							'color'	=> $campaign_status_color[$i],
							'order_no'	=> ($i+1)
						));
					}
				}
			}
			#record log
			$log_message = __("UpdateCampaign").': '.Input::post('title');
			$clsISO->logs($clsBusinessCampaign->tbl,$clsBusinessCampaign->pkey, $business_campaign_id, $log_message);
		}
	}
	echo($msg.'|||'.$business_campaign_id);die();
}
function default_ajDeleteBusinessCampaign(){
	global $adminid,$core,$clsISO;
	if($clsISO->validateLoginInformation()==0){echo('');die();}
	$business_campaign_id = Input::post('business_campaign_id');
	
	$clsBusinessCampaign = new BusinessCampaign();
	$clsBusinessCampaign->deleteOne($business_campaign_id);
	#
	$clsBusinessCampaignStatus = new BusinessCampaignStatus();
	$clsBusinessCampaignStatus->deleteByCond("business_campaign_id='{$business_campaign_id}'");
	#
	$clsBusinessCampaignPotential = new BusinessCampaignPotential();
	$clsBusinessCampaignPotential->deleteByCond("business_campaign_id='{$business_campaign_id}'");
	// Output
	echo($business_campaign_id); die();
}
function default_aj_add_contact_campaign(){
	global $core, $smarty, $dbconn, $clsISO, $clsConfiguration,$adminid;
	$clsPotential = new Potential();
	$clsBusinessCampaign = new BusinessCampaign();
	$clsBusinessCampaignStatus = new BusinessCampaignStatus();
	$clsBusinessCampaignPotential = new BusinessCampaignPotential();
	$business_campaign_id = Input::post('business_campaign_id');
	$listId = Input::post('potential');
	if(!empty($listId)){
		$sql = "is_trash=0 and business_campaign_id='{$business_campaign_id}'";
		$tmp = $clsBusinessCampaignStatus->getAll("{$sql} order by order_no ASC limit 0,1");
		$status_id = !empty($tmp) ? $tmp[0][$clsBusinessCampaignStatus->pkey] : 0;
		foreach($listId as $potential_id){
			$clsBusinessCampaignPotential->insert(array(
				'id'	=> $clsBusinessCampaignPotential->getMaxId(),
				'status_id' => $status_id,
				'potential_id' => $potential_id,
				'business_campaign_id' => $business_campaign_id,
				'user_id' => $adminid,
				'reg_date'	=> time()
			));
		}
		$clsBusinessCampaign->updateTotal($business_campaign_id);
	}
	echo(1); die();
}
/* Notes */
function default_ajManageCRMNote(){
	global $adminid,$core,$clsISO;
	if($clsISO->validateLoginInformation()==0){echo('');die();}
	$clsProperty = new Property();
	$html = '<div class="mg-wrapper">
		<div class="box light">
			<style type="text/css">.btnSearchNoteAll{ cursor:pointer;}</style>
			<div class="box-title">
				<div class="caption mr10">
					'.CRM::renderHTMLButtonBack(array('page'=>'note','action'=>'manage')).'
					<span class="uppercase">'.__('CRMNote').'</span>
				</div>
				<div class="input-group fl" style="max-width:250px">
					<span class="input-group-addon btnSearchNoteAll hide"><i class="fa fa-search"></i></span>
					<input type="text" class="form-control txtSearchNoteAll" id="txtSearchNoteAll" placeholder="'.__('Search').'" />
				</div>
				<div class="input-group fl ml-1" style="max-width:150px">
					<input type="text" readonly placeholder="Từ ngày" class="form-control datepicker txtSearchFromDateAll" id="txtSearchFromDateAll" />
				</div>
				<div class="input-group ml-1 fl" style="max-width:150px">
					<input type="text" readonly placeholder="Đến ngày" class="form-control datepicker txtSearchToDateAll" id="txtSearchToDateAll" />
				</div>
				<div class="input-group ml-1 fl">
					<button class="btn btn-success btnSearchNoteAll">'.$core->makeIcon('search').'</button>
				</div>
				<div class="pull-right hidden">
					'.($clsISO->checkPermission('create_new_note')?'
					<button class="iso-button ajOpenCRMNote" crm_note_id="0">'.$core->makeIcon('plus-circle', __("Addnew")).'</button>':'').'
				</div>
			</div>
			<div class="box-body" style="min-height:600px">
				<div id="holderCRMNoteAll" class="holderCRMNoteAll"></div>
			</div>
		</div>
	</div>';
	// Output
	echo($html);die();
}
function default_ajLoadListCRMNoteAll(){
	global $adminid,$core,$clsISO,$dbconn;
	if($clsISO->validateLoginInformation()==0){echo('');die();}
	$clsUser = new User();
	$clsProperty = new Property();
	$clsCrmNote = new CrmNote();
	$clsPotential = new Potential();
	$clsVS_Admin = new VS_Admin();
	$html = '<table class="table table-striped table-hover table-responsive" width="100%" cellpadding="2" cellspacing="2" border="0">
	<thead><tr>
		<th width="3%">No.</th>
		<th width="200">'.__('Customer').'</th>
		<th>'.__('Description').'</th>
		<th width="200">'.__('Date').'</th>
		<th width="200">'.__('Admin').'</th>
		<th class="text-center" width="60">'.__('_Actions').'</th>
	</tr></thead>';
	$currentPage = (int) Input::post('currentPage',1);
	$number_per_page = (int) Input::post('number_per_page',20);
	$keySearch = Input::post('keySearch');
	$start_date = Input::post('start_date',0,true);
	$end_date = Input::post('end_date',0,true);
	$start_date = $clsISO->convertTextToTime($start_date);
	$end_date = $clsISO->convertTextToTime($end_date,"23:59:59");
	$cond = "is_trash=0 and tp='potential'";
	if(!empty($keySearch) && $keySearch != '0'){
		$cond .= " and (intro like '%".$keySearch."%'
			or pval_id IN (select potential_id from default_potential where phone like '%{$keySearch}' or email like '%{$keySearch}%' or companyname like '%{$keySearch}%' or name like '%{$keySearch}%' or name_slug like '%".$core->replaceSpace($keySearch)."%')
		)";
	}
	if(!empty($start_date)){
		$cond .= " and reg_date>={$start_date}";
	}
	if(!empty($end_date)){
		$cond .= " and reg_date<={$end_date}";
	}
	if($clsISO->checkPermission('full_permissions_crm')==0){
		if($core->_USER['user_group_id']==USER_GROUP_SALE){
			if($core->_USER['admin_permiss_manage']){//trưởng phòng
				$lstSale = $dbconn->getCol("select {$clsVS_Admin->pkey} from {$clsVS_Admin->tbl} where is_trash=0 and is_active='1' and user_group_id=".USER_GROUP_SALE);
				$cond .= " and user_id in (".implode(',',$lstSale).")";
			}else{
				$cond .= " and user_id='{$adminid}'";
			}
		}elseif($core->_USER['user_group_id']==USER_GROUP_CARE){
			if(1){//trưởng phòng:$core->_USER['admin_permiss_manage']
				$lstCare = $dbconn->getCol("select {$clsVS_Admin->pkey} from {$clsVS_Admin->tbl} where is_trash=0 and is_active='1' and user_group_id=".USER_GROUP_CARE);
				$cond .= " and user_id in (".implode(',',$lstCare).")";
			}/*else{
				$cond .= " and (t2.admin_id='{$adminid}' or t2.user_id='{$adminid}')";
			}*/
		}else{
			$cond .= " and user_id='{$adminid}'";
		}
	}
	$totalRecord = $clsCrmNote->countItem($cond);
	$totalPage = ceil($totalRecord/$number_per_page);
	$offset = ($currentPage-1)*$number_per_page;
	$limitCond = " limit {$offset},{$number_per_page}";
	$lstCrmNote = $clsCrmNote->GetAll("{$cond} order by reg_date DESC".$limitCond);
	if(!empty($lstCrmNote)){
		foreach($lstCrmNote as $k => $oneNote){
			$crm_note_id = $oneNote[$clsCrmNote->pkey];
			$onePotential = $clsPotential->getOne($oneNote['pval_id'],'companyname,name,phone,email,type_id');
			$props = $clsISO->make_attrs_builder(array(
				'tp'	=> 'potential',
				'potential_id'	=> $oneNote['pval_id'],
				'crm_note_id'	=> $crm_note_id
			));
			$html .= '<tr>
				<td data-label="No." class="text-center">'.($k+1).'</td>
				<td data-label="'.__('Customer').'"><a href="javascript:void(0);" class="ajOpenPotential link goLink" route="/contacts/'.$onePotential['type_id'].'/'.$oneNote['pval_id'].'/summary" '.$props.'>'.ucfirst($onePotential['name']).'</a></td>
				<td data-label="'.__('Description').'" class="white-space-normal-all">
					<div class="note-content" '.$props.'>'.html_entity_decode($oneNote['intro']).'</div>
					<div class="note-edit" '.$props.' style="display:none">
						<form method="post" action="" enctype="multipart/form-data" id="frmEditNote__'.$crm_note_id.'">
							<div class="form-group">
								 <textarea class="form-control CrmNote_intro_'.$crm_note_id.'" rows="3">'.$oneNote['intro'].'</textarea>
							</div>
							<div class="form-group">
								<button class="btn btn-dafault cancelEditNoteCRMAll" '.$props.'>
									'.$core->makeIcon('reply',__('Cancel')).'
								</button>
								<button class="btn btn-success clickToSaveCrmNoteAll" '.$props.'>
									'.$core->makeIcon('check',__('Update')).'
								</button>
							</div>
						</form>
					</div>
				
				</td>
				<td data-label="'.__('Date').'">'.$clsISO->convertTimeToText($oneNote['reg_date'], true).'</td>
				<td data-label="'.__('Admin').'">'.$clsUser->getFullName($oneNote['user_id']).'</td>
				<td data-label="'.__('_Actions').'" class="text-center">
					<div class="dropdown dropdown-action">
						<a class="dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-ellipsis-v" aria-hidden="true"></i>
						</a>
						<ul class="dropdown-menu icon">
							<li><a class="editNoteCRMAll" '.$props.'>'.$core->makeIcon('pencil').' '.__('Edit').'</a></li>
							<li><a class="deleteNoteCRM" '.$props.'>'.$core->makeIcon('trash').' '.__('Delete').'</a></li>
						</ul>
					</div>
				</td>
			</tr>';
		}
	}else{
		$html .= '<tr>
			<td class="text-center" colspan="10">
				'.CRM::renderHTMLNoDocument('Not any records').'
			</td>
		</tr>';
	}
	$html .= '</table>';
	if($totalPage > 0){
		$html .= '<div class="easyui-pagination" id="PageCRMNoteAll" pageNumber="'.$currentPage.'" pageList="[10,20,30,50]"></div>';
	}
	// output
	echo json_encode(array(
		'html'	=> $html,
		'currentPage'	=> $currentPage,
		'number_per_page'	=> $number_per_page,
		'totalRecord'	=> $totalRecord,
		'totalPage'	=> $totalPage
	)); die();
}
function default_aj_done_note(){
	global $adminid,$smarty,$core,$clsISO;
	$clsCrmNote = new CrmNote();
	$crm_note_id = (int) Input::post('crm_note_id', 0);
	
	$msg = '_error';
	if($clsCrmNote->updateOne($crm_note_id, array(
		'is_done' => 1
	))){
		$msg = '_sussess';
	}
	// Return
	echo $msg; die();
}
function default_init_page_all_follow_ups(){
	global $core, $smarty, $clsISO;
	$holderG = 'general';
	$smarty->assign('holderG', $holderG);
	
	$comeback_button = CRM::renderHTMLButtonBack(array('page'=>'note','action'=>'manage'));
	$smarty->assign('comeback_button', $button_back);
	/** Permission */
	$permiss_access = $clsISO->checkPermission('full_permissions_crm');
	if(!$permiss_access){
		if($core->_USER['user_group_id']==USER_GROUP_SALE && $core->_USER['admin_permiss_manage']){
			$permiss_access = 2;
		}
	}
	$smarty->assign('permiss_access', $permiss_access);
		
	// Return
	$html = $core->build('followups.tpl');
	echo $html; die();
}
function default_list_all_follow_ups(){
	global $core, $smarty, $dbconn, $adminid, $clsISO;
	$holderG = 'detail';
	$smarty->assign('holderG', $holderG);
	$clsPotential  = new Potential();
	$clsFollowUp = new FollowUp();
	$clsVS_Admin = new VS_Admin();
	$clsVS_Addon = new VS_Addon();
	$clsVS_Hosting = new VS_Hosting();
	
	$current_page = (int) Input::post('page',1);
	$number_per_page = (int) Input::post('number_per_page',20);
	$keySearch = Input::post('keySearch');
	$type_id = (int) Input::post('type_id',0);
	$start_date = Input::post('start_date');
	$due_date = Input::post('due_date');
	
	$cond = "is_trash=0";
	if(!empty($keySearch)){
		$cond .= " and content like '%{$keySearch}%'";
	}
	if($type_id > 0){
		$cond .= " and type_id='{$type_id}'";
	}
	/** Filter by start_date */
	if(!empty($start_date) && empty($due_date)){
		$start_date_time = $clsISO->convertTextToTime($start_date);
		$cond .= " and date_id>='{$start_date_time}'";
	} elseif (empty($start_date) && !empty($due_date)){
		$due_date_time = $clsISO->convertTextToTime($due_date);
		$cond .= " and date_id<='{$due_date_time}'";
	} elseif(!empty($start_date) && !empty($due_date)){
		$start_date_time = $clsISO->convertTextToTime($start_date);
		$due_date_time = $clsISO->convertTextToTime($due_date,"23:59:59");
		$cond .= " and (date_id between {$start_date_time} and {$due_date_time})";
	}
	/* Permiss */
	if(!$clsISO->checkPermission('full_permissions_crm')){
		if($core->_USER['user_group_id']==USER_GROUP_SALE){
			if($core->_USER['admin_permiss_manage']==1){//trưởng phòng
				$lstSaleInGroup = $dbconn->getCol("select {$clsVS_Admin->pkey} from {$clsVS_Admin->tbl} 
				where is_trash=0 and is_active='1' and user_group_id='".USER_GROUP_SALE."'");
				$cond .= " and (admin_id in (".implode(',',$lstSaleInGroup).") or user_id in (".implode(',',$lstSaleInGroup)."))";
			}else{
				$cond .= " and (admin_id='{$adminid}' or user_id='{$adminid}')";
			}
		}elseif($core->_USER['user_group_id']==USER_GROUP_CARE){
			if(1){//trưởng phòng:$core->_USER['admin_permiss_manage']
				$lstCare = $dbconn->getCol("select {$clsVS_Admin->pkey} from {$clsVS_Admin->tbl} where is_trash=0 and is_active='1' and user_group_id=".USER_GROUP_CARE);
				$cond .= " and (admin_id in (".implode(',',$lstCare).") or user_id in (".implode(',',$lstCare)."))";
			}/*else{
				$cond .= " and (t2.admin_id='{$adminid}' or t2.user_id='{$adminid}')";
			}*/
		}else{
			$cond .= " and (admin_id='{$adminid}' or user_id='{$adminid}')";
		}
	}else{
		$admin_id = (int) Input::post('admin_id', 0);
		if($admin_id > 0){
			$cond .= " and (admin_id='{$admin_id}' or user_id='{$admin_id}')";
		}
	}
	
	#begin pagination
	$total_record = $clsFollowUp->countItem($cond);
	$total_page = ceil($total_record/$number_per_page);
	$offset = ($current_page-1) * $number_per_page;
	$limitCond = " limit {$offset},{$number_per_page}";
	#end pagination
	$htmlTable = "";
	$lstFollowUp = $clsFollowUp->GetAll("{$cond} order by reg_date DESC".$limitCond);
	if(!empty($lstFollowUp)){ $ii=1; //Init
		foreach($lstFollowUp as $followup){
			$resource_id = $followup['resource_id'];
			$resource_name = '';
			if($resource_id){//$followup['holderG']=='potential'
				$resource_name = $clsPotential->getPotential($resource_id);
			}elseif($followup['holderG']=='addon'){
				$resource_name = $clsVS_Addon->getPotential($followup['service_id']);
			}elseif($followup['holderG']=='hosting'){
				$resource_name = $clsVS_Hosting->getPotential($followup['service_id']);
			}
			$crm_followup_id = $followup[$clsFollowUp->pkey];
			$props = 'resource_id="'.$resource_id.'" crm_followup_id="'.$crm_followup_id.'"';
			$htmlAction = '<div class="dropdown dropdown-action">
				<a class="dropdown-toggle" data-toggle="dropdown">
					<i class="fa fa-ellipsis-v" aria-hidden="true"></i>
				</a>
				<ul class="dropdown-menu">
					<li><a class="aj_open-followup-reschedue" '.$props.'>'.$core->makeIcon('clock-o').' '.__('Reschedue').'</a></li>
					<li><a class="aj_open-followup" '.$props.'>'.$core->makeIcon('pencil').' '.__('EditFollowUp').'</a></li>
					<li><a class="aj_delete-followup" '.$props.'>'.$core->makeIcon('trash').' '.__('Trash').'</a></li>
				</ul>
			</div>';
			$htmlTable .= '<tr>
				<td data-label="No." class="text-center">'.((($current_page-1)*$number_per_page)+$ii).'</td>
				<td data-label="'.__('View').'" class="text-center" bgcolor="#F5F5F5"><a class="btn btn-xs btn-default ajOpenFollowUpCRM" '.$props.'>'.$core->makeIcon('eye').'</a></td>
				<td data-label="'.__('Name').'">'.$resource_name.'</td>
				<td data-label="'.__('Date').'">'.$clsISO->convertTimeToText($followup['date_id'], true).'</td>
				<td data-label="'.__('Type').'" class="text-left">'.$clsFollowUp->getHTMLType($crm_followup_id, $followup).'</td>
				<td data-label="'.__('Description').'">'.html_entity_decode($followup['content']).'</td>
				<td data-label="'.__('Status').'" class="text-left">'.$clsFollowUp->getStatus($crm_followup_id, $followup).'</td>
				<td data-label="'.__('Reminders').'" class="text-center"><button class="iso-button-small">'.$followup['number_reminder'].'</button></td>
				<td data-label="'.__('_Actions').'" class="text-center">'.$htmlAction.'</td>
			</tr>';
			++$ii;
		}
	}else{
		$htmlTable .= '<tr>
			<td class="text-center" colspan="7">
				'.CRM::renderHTMLNoDocument('Not any foolow-Ups').'
			</td>
		</tr>';
	}
	$smarty->assign('htmlTable', $htmlTable);
	$smarty->assign('current_page', $current_page);
	$smarty->assign('total_page', $total_page);
	$smarty->assign('total_record', $total_record);
	$smarty->assign('number_per_page', $number_per_page);
	// Return
	$html = $core->build('followups.tpl');
	echo json_encode(array(
		'html' => $html,
		'total_record'	=> $total_record,
		'number_per_page' => $number_per_page
	)); die();
}
function default_open_campaign(){
	global $core, $smarty, $dbconn, $adminid, $clsISO;
	$clsCampaign = new Campaign();
	
	$uid = $clsISO->getUniqid();
	$toId = Input::post('toId');
	$campaign_id = (int) Input::post('campaign_id', 0);
	$titlePage = 'Thêm mới chiến dịch';
	$smarty->assign('toId', $toId);
	$smarty->assign('campaign_id', $campaign_id);
	$smarty->assign('titlePage', $titlePage);
	// Return
	$html = $core->build('_ajax.open_campaign.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_pop_save_campaign(){
	global $core, $dbconn, $smarty, $dbconn, $profile_id, $clsISO;
	$clsCampaign = new Campaign();
	###
	$msg = "_error";
	$campaign_id = (int) Input::post('campaign_id', 0);
	$title = Input::post('title');
	if($campaign_id == 0){
		$campaign_id = $clsCampaign->getMaxId();
		if($clsCampaign->insert(array(
			$clsCampaign->pkey => $campaign_id,
			'campaign_type' => '_campaign',
			'title' => $title,
			'slug' => $core->replaceSpace($title),
			'reg_date' => time(),
			'upd_date' => time(),
			'user_id' => $profile_id,
			'user_id_update' => $profile_id
		))){
			$msg = "_success";
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'name' => $title,
		'campaign_id' => $campaign_id
	)); die();
}
function default_open_help(){
	global $core, $dbconn, $smarty, $dbconn, $profile_id, $clsISO;
	$clsConfiguration = new Configuration();
	$SiteMsg_CRM_Help = $clsConfiguration->getValue('SiteMsg_CRM_Help');
	$html = '<div class="modal-dialog modal-ipad modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header border-bottom">
				<h5 class="modal-title">Hướng dẫn sử dụng CRM</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<div class="tinyContent">
					'.html_entity_decode($SiteMsg_CRM_Help).'
				</div>
			</div>
		</div>
	</div>';
	// Return
	echo json_encode(array(
		'html' => $html,
		'uid' => $clsISO->getUniqid()
	)); die();
}
function default_open_participant(){
	global $core, $dbconn, $smarty, $dbconn, $profile_id, $clsISO;
	$clsCustomer = new Customer();
	$clsProperty = new Property();
	$clsProfile = new Profile();
	###
	$uid = $clsISO->getUniqid();
	$customer_id = (int) Input::post('customer_id', 0);
	$list_share_id = $clsCustomer->getOneField('list_share_id', $customer_id);
	$arr_share_ids = !empty($list_share_id) 
		? $clsISO->getArrayByTextSlash($list_share_id) : array();
	#
	$html_user_participants = "";
	if(!empty($arr_share_ids)){
		foreach($arr_share_ids as $key => $val){
			$html_user_participants.= '<option value="'.$val.'" selected>'.$clsProfile->getFullName($val).'</option>';
		}
	}
	$smarty->assign('customer_id', $customer_id);
	$smarty->assign('html_user_participants', $html_user_participants);
	// Return
	$html = $core->build('_ajax.participant.tpl');
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function default_pop_save_participant(){
	global $core, $dbconn, $smarty, $dbconn, $profile_id, $clsISO;
	$clsNotify = new Notify();
	$clsProfile = new Profile();
	$clsCustomer = new Customer();
	$clsProperty = new Property();
	###
	$msg = "_error";
	$customer_id = (int) Input::post('customer_id', 0);
	$user_participants = Input::post('user_participants');
	$list_share_id_slash = ""; $use_globe = 0;
	if(!empty($user_participants)){
		$use_globe = 1;
		$list_share_id_slash = $clsISO->makeSlashListFromArray($user_participants);
	}
	$oCustomer = $clsCustomer->getOne($customer_id, "name,list_share_id");
	$list_share_id = $oCustomer['list_share_id'];
	$list_share_arrs = $clsISO->getArrayByTextSlash($list_share_id);
	###
	$participants_added_arrs = $participants_removed_arrs = array();
	if(!empty($user_participants) && empty($list_share_arrs)){
		$participants_added_arrs = $user_participants;
	} else if(!empty($user_participants) && !empty($list_share_arrs)){
		foreach($user_participants as $usr_id){
			if(!in_array($usr_id, $list_share_arrs)){
				$participants_added_arrs[] = $usr_id;
			}
		}
	}
	if(!empty($list_share_arrs) && empty($user_participants)){
		$participants_removed_arrs = $list_share_arrs;
	} else if(!empty($user_participants) && !empty($list_share_arrs)){
		foreach($list_share_arrs as $key => $val){
			if(!in_array($val, $user_participants)){
				$participants_removed_arrs[] = $val;
			}
		}
	}
	// $clsISO->print_pre($participants_added_arrs); die();
	if($clsCustomer->updateOne($customer_id, array(
		'use_globe' => $use_globe,
		'list_share_id' => $list_share_id_slash
	))){
		$msg = "_success";
		if(!empty($participants_added_arrs)){
			$titleNoty = sprintf('<strong>%s</strong> đã gán bạn liên quan tới khách hàng <strong>%s</strong>', 
				$clsProfile->getFullName($profile_id, $oneProfile), 
				$clsCustomer->getName($customer_id, $oCustomer)
			);
			$clsNotify->insertNotify('Customer',$clsCustomer->pkey,$customer_id,$titleNoty,time(),$participants_added_arrs);
		}
		if(!empty($participants_removed_arrs)){
			$titleNoty = sprintf('<strong>%s</strong> đã xóa bạn liên quan tới khách hàng <strong>%s</strong>', 
				$clsProfile->getFullName($profile_id, $oneProfile), 
				$clsCustomer->getName($customer_id, $oCustomer)
			);
			$clsNotify->insertNotify('Customer',$clsCustomer->pkey,$customer_id,$titleNoty,time(),$participants_removed_arrs);
		}
	}
	// return
	echo $msg; die();
}
?>