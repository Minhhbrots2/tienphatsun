<?php
function default_default(){
	global $assign_list,$mod,$core,$clsModule,$clsISO;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];
	#
	$clsProperty = new Property();
	$assign_list["clsProperty"] = $clsProperty;
	// Loại hình (block type) cho nút "+ Thêm mới"
	$field = "{$clsProperty->pkey},title";
	$list_block_type = $clsProperty->getAll("property_type='_BLOCK_TYPE' order by order_no ASC", $field);
	$assign_list["list_block_type"] = $list_block_type;
	// Tìm kiếm -> redirect cho gọn URL
	if(isset($_POST['filter']) && $_POST['filter']=='filter'){
		$keyword = Input::post('keyword');
		$link = '';
		if(!empty($keyword)) $link .= '&keyword='.$keyword;
		header('location: '.PCMS_URL.'/?mod='.$mod.$link);
		exit();
	}
	$classTable = "PriceSheet";
	$clsClassTable = new $classTable;
	$pkeyTable = $clsClassTable->pkey;
	$assign_list["clsClassTable"] = $clsClassTable;
	$assign_list["pkeyTable"] = $pkeyTable;
	// Điều kiện lọc
	$cond = "is_trash=0";
	$keyword = Input::get('keyword');
	$assign_list["keyword"] = $keyword;
	if(!empty($keyword)){
		$keyword = $core->replaceSpace($keyword);
		$cond .= " and title like '%".$keyword."%'";
	}
	$orderBy = " reg_date desc";
	#-------Phân trang---------------------------------------------------------------
	$recordPerPage = 20;
	$currentPage = (int) Input::get('page',1);
	$start_limit = ($currentPage-1)*$recordPerPage;
	$limit = " limit $start_limit,$recordPerPage";
	$totalRecord = $clsClassTable->countItem($cond);
	$totalPage = ceil($totalRecord / $recordPerPage);
	$assign_list['totalRecord'] = $totalRecord;
	$assign_list['recordPerPage'] = $recordPerPage;
	$assign_list['totalPage'] = $totalPage;
	$assign_list['currentPage'] = $currentPage;
	$listPageNumber = array();
	for ($i=1; $i<=$totalPage; $i++){ $listPageNumber[] = $i; }
	$assign_list['listPageNumber'] = $listPageNumber;
	$query_string = $_SERVER['QUERY_STRING'];
	$lst_query_string = explode('&',$query_string);
	$link_page_current = '';
	for($i=0;$i<count($lst_query_string);$i++){
		$tmp = explode('=',$lst_query_string[$i]);
		if($tmp[0]!='page')
			$link_page_current .= ($i==0)?'?'.$lst_query_string[$i]:'&'.$lst_query_string[$i];
	}
	$assign_list['link_page_current'] = $link_page_current;
	#-------Hết phân trang-----------------------------------------------------------
	$fields = "id,title,stock_type,apply_date,scope,scope_slash,upd_date,is_trash";
	$allItem = $clsClassTable->getAll($cond." order by ".$orderBy.$limit, $fields);
	if(!empty($allItem)){
		$arr_property_cached = array();
		foreach($allItem as $key => $val){
			$stock_type = $val['stock_type'];
			if($stock_type && !isset($arr_property_cached[$stock_type])){
				$arr_property_cached[$stock_type] = $clsProperty->getTitle($stock_type);
			}
		}
		$assign_list["arr_property_cached"] = $arr_property_cached;
	}
	$assign_list["allItem"] = $allItem;
}
function default_open_price_sheet(){
	global $smarty,$core,$clsModule,$clsISO;
	$smarty->assign("clsModule",$clsModule);
	$user_id = $core->_USER['user_id'];
	$clsPriceSheet = new PriceSheet();
	$clsProject = new Project();
	$clsProperty = new Property();
	$smarty->assign('clsISO',$clsISO);
	$smarty->assign('clsProject',$clsProject);
	$smarty->assign('clsProperty',$clsProperty);
	#
	$field = "{$clsProject->pkey},title";
	$list_projects = $clsProject->getAll("is_trash=0",$field);
	$smarty->assign('list_projects',$list_projects);
	#
	$price_sheet_id = (int) Input::post('price_sheet_id', 0);
	$stock_type = (int) Input::post('stock_type', _BLOCK_TYPE_HIGHLEVEL_SALE);
	$action = "_edit"; $list_scopes = $early_bird = array();
	$oneItem = array('apply_date' => time());
	if($price_sheet_id == 0){
		$action = "_add";
		// Dọn các bản nháp cũ của chính user rồi tạo 1 bản nháp mới (is_trash=1)
		// để các thao tác lưu phương án/đợt sau đó bám vào 1 id có thật.
		$clsPriceSheet->deleteByCond("`is_trash`=1 and `user_id`='{$user_id}'");
		$price_sheet_id = $clsPriceSheet->getMaxId();
		$clsPriceSheet->insert(array(
			$clsPriceSheet->pkey => $price_sheet_id,
			'stock_type' => $stock_type,
			'apply_date' => time(),
			'user_id' => $user_id,
			'user_id_update' => $user_id,
			'reg_date' => time(),
			'upd_date' => time(),
			'is_trash' => 1
		));
		$oneItem = $clsPriceSheet->getOne($price_sheet_id);
	} else {
		$oneItem = $clsPriceSheet->getOne($price_sheet_id);
		$stock_type = (int) $oneItem['stock_type'];
		$early_bird = $clsISO->to_array_json($oneItem['early_bird']);
		$list_scopes = !empty($oneItem['scope']) ? json_decode($oneItem['scope'], true) : array();
		if(!empty($list_scopes)){
			foreach($list_scopes as $key => $val){
				$project_id = $val['project_id'];
				$list_blocks = $clsProperty->getOItems('_BLOCK', $project_id, 'parent_id,property_code');
				if($stock_type==_BLOCK_TYPE_LOWFLOOR_SALE){
					$arrs_blocks = !empty($val['block_id']) ? $val['block_id'] : array();
					if(!empty($list_blocks)){
						foreach($list_blocks as $okey => $oval){
							$list_blocks[$okey]['selected'] = in_array($oval[$clsProperty->pkey], $arrs_blocks) ? 1 : 0;
						}
					}
				} else {
					$block_id = $val['block_id'];
					$arrs_building = !empty($val['building_id']) ? $val['building_id'] : array();
					$list_buildings = $clsProperty->getOItems('_BUILDING', $block_id);
					if(!empty($list_buildings)){
						foreach($list_buildings as $okey => $oval){
							$list_buildings[$okey]['selected'] = in_array($oval[$clsProperty->pkey], $arrs_building) ? 1 : 0;
						}
					}
					$list_scopes[$key]['list_buildings'] = $list_buildings;
				}
				$list_scopes[$key]['list_blocks'] = $list_blocks;
			}
		}
	}
	// Mặc định an toàn cho nhánh "scope rỗng" trong template
	$smarty->assign('project_id', 0);
	$smarty->assign('block_id', 0);
	$smarty->assign('building_id', 0);
	$smarty->assign('list_blocks', array());
	$smarty->assign('list_buildings', array());
	#
	$smarty->assign('action',$action);
	$smarty->assign('price_sheet_id',$price_sheet_id);
	$smarty->assign('stock_type',$stock_type);
	$smarty->assign('stock_type_title',$clsProperty->getTitle($stock_type));
	$smarty->assign('oneItem',$oneItem);
	$smarty->assign('list_scopes',$list_scopes);
	$smarty->assign('early_bird',$early_bird);
	// Phương án × Đợt thanh toán (GĐ2): dựng lại từ more_information đã lưu
	$list_plans = array();
	if(!empty($oneItem['more_information'])){
		$more_information = $clsISO->to_array_json($oneItem['more_information']);
		if(!empty($more_information) && is_array($more_information)){
			foreach($more_information as $plan_id => $plan){
				if(!is_array($plan)) continue;
				$payment_progress = isset($plan['payment_progress']) ? $plan['payment_progress'] : array();
				$list_plans[] = array(
					'plan_id' => $plan_id,
					'title' => isset($plan['title']) ? $plan['title'] : '',
					'order_no' => isset($plan['order_no']) ? (int)$plan['order_no'] : 0,
					'is_agreement' => isset($plan['is_agreement']) ? $plan['is_agreement'] : 0,
					'discount_rate' => isset($plan['discount_rate']) ? $plan['discount_rate'] : '',
					'htls_rate' => isset($plan['htls_rate']) ? $plan['htls_rate'] : '',
					'htls_until' => isset($plan['htls_until']) ? $plan['htls_until'] : '',
					'rows_html' => _price_sheets_option_rows($payment_progress, $price_sheet_id, $plan_id, $stock_type)
				);
			}
			usort($list_plans, function($a, $b){ return $a['order_no'] - $b['order_no']; });
		}
	}
	$smarty->assign('list_plans', $list_plans);
	// Return
	$smarty->assign('core',$core);
	$html = $core->build('_ajax.price_sheets.tpl');
	echo json_encode(array(
		'html' => $html,
		'price_sheet_id' => $price_sheet_id
	)); die();
}
function default_pop_save_price_sheet(){
	global $core,$clsISO;
	$clsPriceSheet = new PriceSheet();
	###
	$msg = "_error";
	$price_sheet_id = (int) Input::post('price_sheet_id', 0);
	$stock_type = (int) Input::post('stock_type', _BLOCK_TYPE_HIGHLEVEL_SALE);
	$apply_date = Input::post('apply_date', 0);
	$deposit_amount = Input::post('deposit_amount', 0);
	$tax_fee_m2 = Input::post('tax_fee_m2', 0);
	$other_discount = Input::post('other_discount', 0);
	$scope = Input::post('scope');
	$early_bird = Input::post('early_bird');
	### Build scope_slash: |project_block| (thấp tầng) / |project_block_building| (cao tầng)
	$scope_slash = "";
	if(!empty($scope)){
		foreach($scope as $key => $val){
			$project_id = isset($val['project_id']) ? (int)$val['project_id'] : 0;
			if($stock_type==_BLOCK_TYPE_HIGHLEVEL_SALE){
				$block_id = isset($val['block_id']) ? (int)$val['block_id'] : 0;
				$building_ids = isset($val['building_id']) && !empty($val['building_id']) ? $val['building_id'] : array();
				$m = sprintf('|%s_%s', $project_id, $block_id);
				if(!empty($building_ids)){
					foreach($building_ids as $id){ $scope_slash .= $m.'_'.$id.'|'; }
				} else {
					$scope_slash .= $m.'|';
				}
			} else {
				$block_ids = isset($val['block_id']) && !empty($val['block_id']) ? $val['block_id'] : array();
				if(!empty($block_ids)){
					foreach($block_ids as $block_id){ $scope_slash .= sprintf('|%s_%s|', $project_id, $block_id); }
				}
			}
		}
	}
	###
	if($clsPriceSheet->updateOne($price_sheet_id, array(
		'is_trash' => 0,
		'stock_type' => $stock_type,
		'title' => Input::post('title'),
		'apply_date' => $clsISO->convertTextToTime($apply_date),
		'vat_rate' => Input::post('vat_rate'),
		'maintenance_rate' => Input::post('maintenance_rate'),
		'deposit_amount' => $clsISO->processSmartNumber($deposit_amount),
		'tax_fee_m2' => $clsISO->processSmartNumber($tax_fee_m2),
		'description' => Input::post('description'),
		'scope' => json_encode($scope, JSON_UNESCAPED_UNICODE),
		'scope_slash' => $scope_slash,
		'other_discount' => $other_discount,
		'early_bird' => json_encode($early_bird, JSON_UNESCAPED_UNICODE),
		'upd_date' => time(),
		'user_id_update' => $core->_USER['user_id']
	))){
		$msg = "_success";
	}
	// Merge plan_meta (chiết khấu + HTLS per phương án) vào more_information
	$plan_meta = Input::post('plan_meta', array());
	if(!empty($plan_meta) && is_array($plan_meta)){
		$more_information = $clsPriceSheet->getOneField('more_information', $price_sheet_id);
		$more_information = $clsISO->to_array_json($more_information);
		$changed = false;
		foreach($plan_meta as $plan_id => $meta){
			if(!isset($more_information[$plan_id])) continue;
			$more_information[$plan_id]['discount_rate'] = isset($meta['discount_rate']) ? $meta['discount_rate'] : '';
			$more_information[$plan_id]['htls_rate'] = isset($meta['htls_rate']) ? $meta['htls_rate'] : '';
			$more_information[$plan_id]['htls_until'] = isset($meta['htls_until']) ? $meta['htls_until'] : '';
			$changed = true;
		}
		if($changed){
			$clsPriceSheet->updateOne($price_sheet_id, array(
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			));
		}
	}
	// Return
	echo json_encode(array('msg' => $msg)); die();
}
function default_add_scope(){
	global $core,$clsISO;
	$clsProject = new Project();
	$field = "{$clsProject->pkey},title";
	$list_projects = $clsProject->getAll("is_trash=0",$field);
	$stock_type = (int) Input::post('stock_type', _BLOCK_TYPE_HIGHLEVEL_SALE);
	$uid = $clsISO->getUniqid();
	$html = '<div class="scope_item scope_item_'.$uid.'">
		<div class="form-group form-row">
			<div class="col-md-6">
				<label class="col-form-label">Chọn dự án</label>
				<select uid="'.$uid.'" class="form-control required iso-select2" onchange="$Core.price_sheets.load_option_block(this,event)" name="scope['.$uid.'][project_id]" toId="block_'.$uid.'" data-error="Chưa chọn dự án">
					<option value="0">Chọn dự án</option>';
					foreach($list_projects as $project){
						$html .= '<option value="'.$project[$clsProject->pkey].'">'.$project['title'].'</option>';
					}
				$html .= '</select>
			</div>
			<div class="col-md-6">
				<label class="col-form-label">Chọn phân khu</label>
				'.($stock_type==_BLOCK_TYPE_LOWFLOOR_SALE
					? '<select uid="'.$uid.'" class="form-control required iso-select2" id="block_'.$uid.'" toId="building_'.$uid.'" name="scope['.$uid.'][block_id][]" multiple="multiple" data-error="Chưa chọn phân khu">
						<option value="0">Chọn phân khu</option>
					</select>'
					: '<select uid="'.$uid.'" class="form-control required iso-select2" id="block_'.$uid.'" onchange="$Core.price_sheets.load_option_building(this,event)" toId="building_'.$uid.'" name="scope['.$uid.'][block_id]" data-error="Chưa chọn phân khu">
						<option value="0">Chọn phân khu</option>
					</select>').'
			</div>
		</div>
		'.($stock_type==_BLOCK_TYPE_HIGHLEVEL_SALE
			? '<div id="building_group_'.$uid.'" class="form-group d-none">
				<label class="col-form-label">Chọn tòa áp dụng</label>
				<select class="form-control iso-select2" multiple="multiple" id="building_'.$uid.'" data-placeholder="Chọn tòa nhà" name="scope['.$uid.'][building_id][]"></select>
			</div>'
			: '').'
		<div class="d-flex">
			<button type="button" uid="'.$uid.'" onClick="$Core.price_sheets.delete_scope(this,event)" class="btn btn-sm btn-default">'.$core->makeIcon('trash','Xóa').'</button>
		</div>
	</div>';
	// Return
	echo $html; die();
}
function default_load_option_block(){
	global $clsISO;
	$clsProperty = new Property();
	##
	$project_id = Input::post('project_id',0);
	$field = "{$clsProperty->pkey},parent_id,title";
	$list_blocks = $clsProperty->getAll("property_type='_BLOCK' and for_id='{$project_id}'",$field);
	##
	$html = sprintf('<option parent_id="%s" value="0">%s</option>',_BLOCK_TYPE_LOWFLOOR_SALE,'Lựa chọn phân khu');
	if(!empty($list_blocks)){
		foreach($list_blocks as $key => $val){
			$html .= sprintf(
				'<option parent_id="%s" value="%s">%s</option>',
				$val['parent_id'],
				$val[$clsProperty->pkey],
				$val['title']
			);
		}
	}
	// Return
	echo $html; die();
}
function default_load_option_building(){
	global $clsISO;
	$clsProperty = new Property();
	##
	$block_id = Input::post('block_id',0);
	$field = "{$clsProperty->pkey},title";
	$list_buildings = $clsProperty->getAll("`property_type`='_BUILDING' and `for_id`='{$block_id}'",$field);
	##
	$html = "";
	if(!empty($list_buildings)){
		foreach($list_buildings as $key => $val){
			$html .= sprintf('<option value="%s">%s</option>',$val[$clsProperty->pkey],$val['title']);
		}
		unset($list_buildings);
	}
	// Return
	echo $html; die();
}
function default_open_price_plan(){
	global $core,$clsISO;
	$clsPriceSheet = new PriceSheet();
	$price_sheet_id = (int) Input::post('price_sheet_id', 0);
	$price_plan_id = Input::post('price_plan_id', 0);
	$titlePage = "Thêm phương án tính giá";
	$title = "";
	if(!empty($price_plan_id)){
		$more_information = $clsPriceSheet->getOneField('more_information', $price_sheet_id);
		$more_information = $clsISO->to_array_json($more_information);
		if(isset($more_information[$price_plan_id])){
			$titlePage = "Sửa tên phương án";
			$title = isset($more_information[$price_plan_id]['title']) ? $more_information[$price_plan_id]['title'] : "";
		}
	}
	$html = '<div class="modal-dialog modal-sm">
		<form class="modal-content" method="post" action="" enctype="multipart/form-data">
			<div class="modal-header">
				<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a>
				<h3 class="modal-title"><strong>'.$titlePage.'</strong></h3>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<input type="text" id="'.$clsISO->getUniqid().'" autocomplete="off" class="form-control required"
						placeholder="Nhập tên phương án" name="title" value="'.htmlspecialchars($title, ENT_QUOTES).'" />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success pull-right" onClick="$Core.price_sheets.pop_save_price_plan(this, event)"
					price_sheet_id="'.$price_sheet_id.'" price_plan_id="'.$price_plan_id.'">Lưu lại</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">Đóng</button>
			</div>
		</form>
	</div>';
	// Return
	echo json_encode(array('html' => $html)); die();
}
function default_pop_save_price_plan(){
	global $core,$clsISO;
	$clsPriceSheet = new PriceSheet();
	$user_id = $core->_USER['user_id'];
	###
	$msg = "_error";
	$title = Input::post('title');
	$price_plan_id = Input::post('price_plan_id', 0);
	$price_sheet_id = (int) Input::post('price_sheet_id', 0);
	$stock_type = $clsPriceSheet->getOneField('stock_type', $price_sheet_id);
	$more_information = $clsPriceSheet->getOneField('more_information', $price_sheet_id);
	$more_information = $clsISO->to_array_json($more_information);
	###
	$action = "_edit"; $tablink = $tabcontent = "";
	if(!empty($price_plan_id)){
		$more_information[$price_plan_id]['title'] = $title;
		$more_information[$price_plan_id]['upd_date'] = time();
		$more_information[$price_plan_id]['user_id_update'] = $user_id;
	} else {
		$action = "_add";
		$order_no = !empty($more_information) ? count($more_information) : 0;
		$price_plan_id = $clsISO->getUniqid();
		$more_information[$price_plan_id] = array(
			'title' => $title,
			'order_no' => ($order_no+1),
			'is_agreement' => 0,
			'payment_progress' => array(),
			'reg_date' => time(),
			'upd_date' => time(),
			'user_id' => $user_id,
			'user_id_update' => $user_id,
		);
		$tab = _price_sheets_plan_tab($price_sheet_id, $price_plan_id, $more_information[$price_plan_id], $stock_type, ($order_no == 0));
		$tablink = $tab['tablink'];
		$tabcontent = $tab['tabcontent'];
	}
	if($clsPriceSheet->updateOne($price_sheet_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))){
		$msg = "_success";
	}
	// Return
	echo json_encode(array(
		'msg' => $msg,
		'action' => $action,
		'tablink' => $tablink,
		'tabcontent' => $tabcontent,
		'price_plan_id' => $price_plan_id,
		'title' => $title
	)); die();
}
function default_delete_price_plan(){
	global $core,$clsISO;
	$clsPriceSheet = new PriceSheet();
	###
	$msg = "_error";
	$price_plan_id = Input::post('price_plan_id', 0);
	$price_sheet_id = (int) Input::post('price_sheet_id', 0);
	$more_information = $clsPriceSheet->getOneField('more_information', $price_sheet_id);
	$more_information = $clsISO->to_array_json($more_information);
	if(isset($more_information[$price_plan_id])){
		unset($more_information[$price_plan_id]);
		if($clsPriceSheet->updateOne($price_sheet_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'upd_date' => time(),
			'user_id_update' => $core->_USER['user_id']
		))){
			$msg = "_success";
		}
	}
	// Return
	echo json_encode(array('msg' => $msg)); die();
}
function default_clone_price_plan(){
	global $core,$clsISO;
	$clsPriceSheet = new PriceSheet();
	$user_id = $core->_USER['user_id'];
	###
	$msg = "_error"; $tablink = $tabcontent = ""; $new_plan_id = "";
	$price_plan_id = Input::post('price_plan_id', 0);
	$price_sheet_id = (int) Input::post('price_sheet_id', 0);
	$stock_type = $clsPriceSheet->getOneField('stock_type', $price_sheet_id);
	$more_information = $clsPriceSheet->getOneField('more_information', $price_sheet_id);
	$more_information = $clsISO->to_array_json($more_information);
	if(isset($more_information[$price_plan_id])){
		$src = $more_information[$price_plan_id];
		$new_progress = array();
		if(!empty($src['payment_progress']) && is_array($src['payment_progress'])){
			foreach($src['payment_progress'] as $opt){
				$new_progress[$clsISO->getUniqid()] = $opt;
			}
		}
		$order_no = count($more_information);
		$new_plan_id = $clsISO->getUniqid();
		$more_information[$new_plan_id] = array(
			'title' => (isset($src['title']) ? $src['title'] : '').' (bản sao)',
			'order_no' => ($order_no+1),
			'is_agreement' => isset($src['is_agreement']) ? $src['is_agreement'] : 0,
			'payment_progress' => $new_progress,
			'reg_date' => time(),
			'upd_date' => time(),
			'user_id' => $user_id,
			'user_id_update' => $user_id,
		);
		if($clsPriceSheet->updateOne($price_sheet_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'upd_date' => time(),
			'user_id_update' => $user_id
		))){
			$tab = _price_sheets_plan_tab($price_sheet_id, $new_plan_id, $more_information[$new_plan_id], $stock_type, 0);
			$tablink = $tab['tablink'];
			$tabcontent = $tab['tabcontent'];
			$msg = "_success";
		}
	}
	// Return
	echo json_encode(array('msg' => $msg, 'tablink' => $tablink, 'tabcontent' => $tabcontent, 'price_plan_id' => $new_plan_id)); die();
}
function default_open_option(){
	global $smarty,$core,$clsISO;
	$clsPriceSheet = new PriceSheet();
	$option_id = Input::post('option_id');
	$stock_type = Input::post('stock_type', _BLOCK_TYPE_HIGHLEVEL_SALE);
	$price_plan_id = Input::post('price_plan_id', 0);
	$price_sheet_id = (int) Input::post('price_sheet_id', 0);
	$more_information = $clsPriceSheet->getOneField('more_information', $price_sheet_id);
	$more_information = $clsISO->to_array_json($more_information);
	$onePlan = isset($more_information[$price_plan_id]) ? $more_information[$price_plan_id] : array();
	$payment_progress = isset($onePlan['payment_progress']) ? $onePlan['payment_progress'] : array();
	###
	$titlePage = "Thêm lịch thanh toán";
	$oneOption = array(
		'name' => "",
		'date_mode' => 'days',
		'payment_days' => "",
		'fixed_date' => 0,
		'estimated_text' => "",
		'amount_mode' => 'percent',
		'payment_rate' => 0,
		'payment_amount' => 0,
		'include_kpbt' => 0,
		'tax_rate' => 0,
		"description" => ""
	);
	// Lấy KPBT % từ header PTG để gợi ý trong checkbox
	$smarty->assign('maintenance_rate', $clsPriceSheet->getOneField('maintenance_rate', $price_sheet_id));
	if(!empty($option_id) && isset($payment_progress[$option_id])){
		$titlePage = "Sửa lịch thanh toán";
		$oneOption = array_merge($oneOption, $payment_progress[$option_id]);
		if(empty($oneOption['name']) && !empty($oneOption['payment_deadline'])) $oneOption['name'] = $oneOption['payment_deadline'];
	}
	$smarty->assign('titlePage', $titlePage);
	$smarty->assign('option_id', $option_id);
	$smarty->assign('stock_type', $stock_type);
	$smarty->assign('price_plan_id', $price_plan_id);
	$smarty->assign('price_sheet_id', $price_sheet_id);
	$smarty->assign('oneOption', $oneOption);
	// Return
	$smarty->assign('core', $core);
	$html = $core->build('_ajax.option.tpl');
	echo json_encode(array('html' => $html)); die();
}
function default_pop_save_option(){
	global $core,$clsISO;
	$clsPriceSheet = new PriceSheet();
	###
	$option_id = Input::post('option_id', "");
	$price_plan_id = Input::post('price_plan_id',0);
	$price_sheet_id = (int) Input::post('price_sheet_id',0);
	$more_information = $clsPriceSheet->getOneField('more_information', $price_sheet_id);
	$more_information = $clsISO->to_array_json($more_information);
	$price_one_plan = isset($more_information[$price_plan_id]) ? $more_information[$price_plan_id] : array();
	$payment_progress = isset($price_one_plan['payment_progress']) ? $price_one_plan['payment_progress'] : array();
	###
	$msg = '_error';
	if(Input::exists('action','GET') && Input::get('action')=='_delete'){
		unset($payment_progress[$option_id]);
		$more_information[$price_plan_id]['payment_progress'] = $payment_progress;
		if($clsPriceSheet->updateOne($price_sheet_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'upd_date' => time(),
			'user_id_update' => $core->_USER['user_id']
		))){
			$msg = '_success';
		}
	} else {
		$name = Input::post('name');
		$date_mode = Input::post('date_mode', 'days');
		$payment_days = (int) Input::post('payment_days', 0);
		$fixed_date_text = Input::post('fixed_date', '');
		$fixed_date = !empty($fixed_date_text) ? $clsISO->convertTextToTime($fixed_date_text) : 0;
		$estimated_text = Input::post('estimated_text', '');
		$amount_mode = Input::post('amount_mode', 'percent');
		$payment_amount = $clsISO->processSmartNumber(Input::post('payment_amount', 0));
		$include_kpbt = (int) Input::post('include_kpbt', 0);
		if(!empty($option_id)){
			$payment_progress[$option_id]['name'] = $name;
			$payment_progress[$option_id]['date_mode'] = $date_mode;
			$payment_progress[$option_id]['payment_days'] = $payment_days;
			$payment_progress[$option_id]['fixed_date'] = $fixed_date;
			$payment_progress[$option_id]['estimated_text'] = $estimated_text;
			$payment_progress[$option_id]['amount_mode'] = $amount_mode;
			$payment_progress[$option_id]['payment_rate'] = Input::post('payment_rate',0);
			$payment_progress[$option_id]['payment_amount'] = $payment_amount;
			$payment_progress[$option_id]['include_kpbt'] = $include_kpbt;
			$payment_progress[$option_id]['tax_rate'] = Input::post('tax_rate',0);
			$payment_progress[$option_id]['description'] = Input::post('description');
			$payment_progress[$option_id]['upd_date'] = time();
			$payment_progress[$option_id]['user_id_update'] = $core->_USER['user_id'];
		} else {
			$payment_progress[$clsISO->getUniqid()] = array(
				'name' => $name,
				'date_mode' => $date_mode,
				'payment_days' => $payment_days,
				'fixed_date' => $fixed_date,
				'estimated_text' => $estimated_text,
				'amount_mode' => $amount_mode,
				'payment_rate'	=> Input::post('payment_rate',0),
				'payment_amount' => $payment_amount,
				'include_kpbt' => $include_kpbt,
				'tax_rate'	=> Input::post('tax_rate',0),
				'description' => Input::post('description'),
				'user_id'	=> $core->_USER['user_id'],
				'user_id_update'	=> $core->_USER['user_id'],
				'reg_date'	=> time(),
				'upd_date'	=> time()
			);
		}
		$more_information[$price_plan_id]['payment_progress'] = $payment_progress;
		if($clsPriceSheet->updateOne($price_sheet_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'upd_date' => time(),
			'user_id_update' => $core->_USER['user_id']
		))){
			$msg = '_success';
		}
	}
	// Return
	echo $msg; die();
}
function default_load_tbl_options(){
	global $core,$clsISO;
	$clsPriceSheet = new PriceSheet();
	###
	$price_plan_id = Input::post('price_plan_id', 0);
	$price_sheet_id = (int) Input::post('price_sheet_id', 0);
	$more_information = $clsPriceSheet->getOneField('more_information', $price_sheet_id);
	$more_information = $clsISO->to_array_json($more_information);
	$stock_type = $clsPriceSheet->getOneField('stock_type', $price_sheet_id);
	$onePlan = isset($more_information[$price_plan_id]) ? $more_information[$price_plan_id] : array();
	$payment_progress = isset($onePlan['payment_progress']) ? $onePlan['payment_progress'] : array();
	$html = _price_sheets_option_rows($payment_progress, $price_sheet_id, $price_plan_id, $stock_type);
	// Return
	echo json_encode(array('html' => $html)); die();
}
function default_delete(){
	global $mod,$core;
	$clsPriceSheet = new PriceSheet();
	$pkeyTable = $clsPriceSheet->pkey;
	$pvalTable = isset($_GET[$pkeyTable]) ? intval($_GET[$pkeyTable]) : 0;
	##
	if($pvalTable == 0){
		header('location: '.PCMS_URL.'/?mod='.$mod.'&message=notPermission');
		exit();
	}
	if($clsPriceSheet->deleteOne($pvalTable)){
		header('location: '.PCMS_URL.'/?mod='.$mod.'&message=DeleteSuccess');
		exit();
	}
}
// Nhân bản 1 tiến độ thanh toán (PTG) -> tạo bản ghi mới, copy toàn bộ scope + phương án + đợt
function default_clone(){
	global $mod,$core,$clsISO;
	$clsPriceSheet = new PriceSheet();
	$user_id = $core->_USER['user_id'];
	$id = (int) Input::get('id', 0);
	$src = $id > 0 ? $clsPriceSheet->getOne($id) : array();
	if($id == 0 || empty($src)){
		header('location: '.PCMS_URL.'/?mod='.$mod.'&message=notPermission');
		exit();
	}
	$new_id = $clsPriceSheet->getMaxId();
	$clsPriceSheet->insert(array(
		$clsPriceSheet->pkey => $new_id,
		'title' => (isset($src['title']) ? $src['title'] : '').' (bản sao)',
		'stock_type' => $src['stock_type'],
		'apply_date' => $src['apply_date'],
		'vat_rate' => isset($src['vat_rate']) ? $src['vat_rate'] : 0,
		'maintenance_rate' => isset($src['maintenance_rate']) ? $src['maintenance_rate'] : 0,
		'deposit_amount' => isset($src['deposit_amount']) ? $src['deposit_amount'] : 0,
		'description' => isset($src['description']) ? $src['description'] : '',
		'scope' => isset($src['scope']) ? $src['scope'] : '',
		'scope_slash' => isset($src['scope_slash']) ? $src['scope_slash'] : '',
		'more_information' => isset($src['more_information']) ? $src['more_information'] : '',
		'is_trash' => 0,
		'user_id' => $user_id,
		'user_id_update' => $user_id,
		'reg_date' => time(),
		'upd_date' => time()
	));
	header('location: '.PCMS_URL.'/?mod='.$mod.'&message=insertSuccess');
	exit();
}
// Nhân bản 1 đợt thanh toán: copy với uid mới, chèn ngay sau đợt gốc
function default_clone_option(){
	global $core,$clsISO;
	$clsPriceSheet = new PriceSheet();
	$user_id = $core->_USER['user_id'];
	###
	$msg = "_error";
	$option_id = Input::post('option_id', "");
	$price_plan_id = Input::post('price_plan_id', 0);
	$price_sheet_id = (int) Input::post('price_sheet_id', 0);
	$more_information = $clsPriceSheet->getOneField('more_information', $price_sheet_id);
	$more_information = $clsISO->to_array_json($more_information);
	$payment_progress = isset($more_information[$price_plan_id]['payment_progress']) ? $more_information[$price_plan_id]['payment_progress'] : array();
	if(!empty($option_id) && isset($payment_progress[$option_id])){
		$src = $payment_progress[$option_id];
		$src['reg_date'] = time();
		$src['upd_date'] = time();
		$src['user_id'] = $user_id;
		$src['user_id_update'] = $user_id;
		// Chèn ngay sau đợt gốc để tiện thấy và sửa
		$new_progress = array();
		foreach($payment_progress as $k => $v){
			$new_progress[$k] = $v;
			if($k === $option_id){
				$new_progress[$clsISO->getUniqid()] = $src;
			}
		}
		$more_information[$price_plan_id]['payment_progress'] = $new_progress;
		if($clsPriceSheet->updateOne($price_sheet_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
			'upd_date' => time(),
			'user_id_update' => $user_id
		))){
			$msg = "_success";
		}
	}
	echo $msg; die();
}
// Sắp xếp lại thứ tự đợt trong 1 phương án (nhận mảng uid theo thứ tự mới)
function default_reorder_options(){
	global $core,$clsISO;
	$clsPriceSheet = new PriceSheet();
	$user_id = $core->_USER['user_id'];
	###
	$msg = "_error";
	$price_plan_id = Input::post('price_plan_id', 0);
	$price_sheet_id = (int) Input::post('price_sheet_id', 0);
	$order = Input::post('order'); // array các uid theo thứ tự mới
	if(!is_array($order) || empty($order)){ echo $msg; die(); }
	$more_information = $clsPriceSheet->getOneField('more_information', $price_sheet_id);
	$more_information = $clsISO->to_array_json($more_information);
	$payment_progress = isset($more_information[$price_plan_id]['payment_progress']) ? $more_information[$price_plan_id]['payment_progress'] : array();
	$new_progress = array();
	foreach($order as $uid){
		$uid = preg_replace('/^opt_/', '', $uid); // sortable toArray trả "opt_UID"
		if(isset($payment_progress[$uid])){
			$new_progress[$uid] = $payment_progress[$uid];
		}
	}
	// Bổ sung đợt nào còn sót (nếu sortable thiếu)
	foreach($payment_progress as $uid => $v){
		if(!isset($new_progress[$uid])){ $new_progress[$uid] = $v; }
	}
	$more_information[$price_plan_id]['payment_progress'] = $new_progress;
	if($clsPriceSheet->updateOne($price_sheet_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
		'upd_date' => time(),
		'user_id_update' => $user_id
	))){
		$msg = "_success";
	}
	echo $msg; die();
}
// Helper dùng chung: dựng 1 tab phương án (tablink + tabcontent) cho add / clone / template
function _price_sheets_plan_tab($price_sheet_id, $price_plan_id, $plan, $stock_type, $is_active=0){
	$title = isset($plan['title']) ? $plan['title'] : '';
	$payment_progress = isset($plan['payment_progress']) ? $plan['payment_progress'] : array();
	$rows_html = _price_sheets_option_rows($payment_progress, $price_sheet_id, $price_plan_id, $stock_type);
	$discount = isset($plan['discount_rate']) ? htmlspecialchars($plan['discount_rate'], ENT_QUOTES) : '';
	$htls_rate = isset($plan['htls_rate']) ? htmlspecialchars($plan['htls_rate'], ENT_QUOTES) : '';
	$htls_until = isset($plan['htls_until']) ? htmlspecialchars($plan['htls_until'], ENT_QUOTES) : '';
	$tablink = '<li class="nav-item nav-item-'.$price_sheet_id.''.($is_active ? ' active' : '').'">
		<a href="#'.$price_plan_id.'" class="nav-link" data-toggle="tab" role="tab">'.$title.'</a>
	</li>';
	$tabcontent = '<div class="tab-pane fade py-3'.($is_active ? ' active in' : '').'" id="'.$price_plan_id.'" role="tabpanel">
		<div class="form-row" style="margin-bottom:10px">
			<div class="col-md-4">
				<label class="col-form-label">Chiết khấu</label>
				<input type="text" class="form-control input-sm" name="plan_meta['.$price_plan_id.'][discount_rate]" placeholder="VD: ~13% hoặc Không áp dụng" value="'.$discount.'" />
			</div>
			<div class="col-md-4">
				<label class="col-form-label">HTLS (Hỗ trợ lãi suất)</label>
				<input type="text" class="form-control input-sm" name="plan_meta['.$price_plan_id.'][htls_rate]" placeholder="VD: 0% hoặc Không áp dụng" value="'.$htls_rate.'" />
			</div>
			<div class="col-md-4">
				<label class="col-form-label">HTLS đến</label>
				<input type="text" class="form-control input-sm" name="plan_meta['.$price_plan_id.'][htls_until]" placeholder="VD: 31/12/2028" value="'.$htls_until.'" />
			</div>
		</div>
		<fieldset>
			<legend>Đợt thanh toán</legend>
			<div style="display:flex; align-items:center; gap:8px; margin-bottom:10px; flex-wrap:wrap">
				<label class="text-muted" style="margin:0">Giả định ngày cọc:</label>
				<input type="date" class="form-control input-sm ptg-cocdate" id="cocdate_'.$price_sheet_id.'_'.$price_plan_id.'"
					onchange="$Core.price_sheets.refresh_eta(\''.$price_sheet_id.'\',\''.$price_plan_id.'\')"
					data-ps="'.$price_sheet_id.'" data-pp="'.$price_plan_id.'" style="max-width:170px" />
				<small class="text-muted">(chỉ xem trước, không lưu)</small>
			</div>
			<table class="table text-nowrap">
				<thead><tr>
					<th class="text-center" width="10%">Đợt</th>
					<th class="text-left" width="35%">Tên mốc/đợt</th>
					<th class="text-right" width="15%">Cách đợt trước</th>
					<th class="text-right" width="15%">Ngày dự kiến</th>
					<th class="text-right" width="10%">Tỉ lệ nộp</th>
					<th class="text-right" width="10%">VAT</th>
					<th class="text-center" width="5%"></th>
				</tr></thead>
				<tbody class="price_sheets_'.$price_sheet_id.'_'.$price_plan_id.'">'.$rows_html.'</tbody>
				<tfoot>
					<tr class="ptg-totals-'.$price_sheet_id.'-'.$price_plan_id.'">
						<td colspan="4" class="text-right text-muted"><strong>Tổng tỷ lệ:</strong></td>
						<td class="text-right ptg-total-rate"><strong>0%</strong></td>
						<td colspan="2" class="ptg-total-note text-muted"></td>
					</tr>
				</tfoot>
			</table>
		</fieldset>
		<button type="button" onClick="$Core.price_sheets.open_option(this, event)" price_sheet_id="'.$price_sheet_id.'"
			price_plan_id="'.$price_plan_id.'" stock_type="'.$stock_type.'" class="btn btn-default text-danger"><i class="fa fa-plus"></i> Thêm đợt thanh toán</button>
		<button type="button" onClick="$Core.price_sheets.edit_price_plan(this, event)" price_sheet_id="'.$price_sheet_id.'"
			price_plan_id="'.$price_plan_id.'" class="btn btn-link text-muted"><i class="fa fa-edit"></i> Sửa tên</button>
		<button type="button" onClick="$Core.price_sheets.clone_price_plan(this, event)" price_sheet_id="'.$price_sheet_id.'"
			price_plan_id="'.$price_plan_id.'" class="btn btn-link text-muted"><i class="fa fa-copy"></i> Nhân bản phương án</button>
		<button type="button" onClick="$Core.price_sheets.delete_price_plan(this, event)" price_sheet_id="'.$price_sheet_id.'"
			price_plan_id="'.$price_plan_id.'" class="btn btn-link text-muted"><i class="fa fa-trash"></i> Xóa phương án</button>
	</div>';
	return array('tablink' => $tablink, 'tabcontent' => $tabcontent);
}
// Helper dùng chung: dựng các dòng <tr> đợt thanh toán (cho load_tbl_options & open_price_sheet)
function _price_sheets_option_rows($payment_progress, $price_sheet_id, $price_plan_id, $stock_type){
	global $core;
	$html = '';
	if(!empty($payment_progress)){ $ii = 0;
		foreach($payment_progress as $key => $option){
			global $clsISO;
			$name = isset($option['name']) ? $option['name'] : (isset($option['payment_deadline']) ? $option['payment_deadline'] : '');
			$date_mode = isset($option['date_mode']) ? $option['date_mode'] : 'days';
			$days_raw = isset($option['payment_days']) ? $option['payment_days'] : '';
			$fixed_ts = isset($option['fixed_date']) ? (int)$option['fixed_date'] : 0;
			$est_text = isset($option['estimated_text']) ? $option['estimated_text'] : '';
			// Cột "Cách đợt trước"
			if($date_mode == 'fixed' && $fixed_ts > 0){
				$gap_disp = '<i class="fa fa-calendar" style="color:#888"></i> '.date('d/m/Y', $fixed_ts);
			} elseif($date_mode == 'estimated' && $est_text !== ''){
				$gap_disp = '<i class="fa fa-clock-o" style="color:#888"></i> '.htmlspecialchars($est_text, ENT_QUOTES);
			} else {
				$gap_disp = $days_raw !== '' ? '+'.$days_raw.' ngày' : '';
			}
			$amount_mode = isset($option['amount_mode']) ? $option['amount_mode'] : 'percent';
			$rate = isset($option['payment_rate']) ? $option['payment_rate'] : 0;
			$amount = isset($option['payment_amount']) ? (int)$option['payment_amount'] : 0;
			$include_kpbt = !empty($option['include_kpbt']) ? 1 : 0;
			$vat = isset($option['tax_rate']) ? $option['tax_rate'] : 0;
			// Cột "Tỉ lệ nộp" — append "+ KPBT" hoặc chỉ "KPBT" nếu rate=0
			if($amount_mode == 'fixed'){
				$pay_disp = number_format($amount, 0, ',', '.').' đ';
				if($include_kpbt) $pay_disp .= ' + KPBT';
			} else {
				if($include_kpbt && (float)$rate == 0){
					$pay_disp = '<span class="text-info">KPBT</span>';
				} elseif($include_kpbt){
					$pay_disp = $rate.'% + <span class="text-info">KPBT</span>';
				} else {
					$pay_disp = $rate.'%';
				}
			}
			$data_attrs = 'data-uid="'.$key.'"'
				.' data-date-mode="'.htmlspecialchars($date_mode, ENT_QUOTES).'"'
				.' data-days="'.htmlspecialchars((string)$days_raw, ENT_QUOTES).'"'
				.' data-fixed-ts="'.$fixed_ts.'"'
				.' data-estimated="'.htmlspecialchars($est_text, ENT_QUOTES).'"'
				.' data-amount-mode="'.htmlspecialchars($amount_mode, ENT_QUOTES).'"'
				.' data-rate="'.htmlspecialchars((string)$rate, ENT_QUOTES).'"'
				.' data-amount="'.$amount.'"'
				.' data-kpbt="'.$include_kpbt.'"';
			$html .= '<tr id="opt_'.$key.'" '.$data_attrs.'>
				<td class="text-center"><span class="mySortableHandler" style="cursor:move; color:#999; margin-right:4px" title="Kéo để sắp xếp"><i class="fa fa-bars"></i></span>Đợt '.($ii+1).'</td>
				<td class="text-left">'.$name.'</td>
				<td class="text-right" style="white-space: break-spaces" >'.$gap_disp.'</td>
				<td class="text-right ptg-eta text-muted"></td>
				<td class="text-right">'.$pay_disp.'</td>
				<td class="text-right">'.$vat.'%</td>
				<td class="text-center">
					<div class="btn-group">
						<button class="btn btn-xs btn-default dropdown-toggle" type="button" data-toggle="dropdown">
							<i class="icon-cog"></i> <span class="caret"></span></button>
						<ul class="dropdown-menu" style="right:0px !important;left:auto; min-width:140px">
							<li><a href="javascript:void(0);" title="Chỉnh sửa" onClick="$Core.price_sheets.open_option(this, event)" price_sheet_id="'.$price_sheet_id.'" price_plan_id="'.$price_plan_id.'" stock_type="'.$stock_type.'" option_id="'.$key.'">'.$core->makeIcon('pencil', 'Chỉnh sửa').'</a></li>
							<li><a href="javascript:void(0);" title="Nhân bản đợt" onClick="$Core.price_sheets.clone_option(this, event)" price_sheet_id="'.$price_sheet_id.'" price_plan_id="'.$price_plan_id.'" option_id="'.$key.'"><i class="fa fa-copy"></i> Nhân bản đợt</a></li>
							<li><a href="javascript:void(0);" title="Xóa" onClick="$Core.price_sheets.delete_option(this, event)" price_sheet_id="'.$price_sheet_id.'" price_plan_id="'.$price_plan_id.'" option_id="'.$key.'">'.$core->makeIcon('trash', 'Xóa').'</a></li>
						</ul>
					</div>
				</td>
			</tr>';
			++$ii;
		}
		unset($payment_progress);
	} else {
		$html .= '<tr>
			<td class="text-center" colspan="7">
				<img src="'.URL_IMAGES.'/listing-empty.svg" width="60px" />
				<p class="text-muted">Chưa có dữ liệu</p>
			</td>
		</tr>';
	}
	return $html;
}
?>
