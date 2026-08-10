<?php 
function default_default(){
	global $smarty,$assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;
	$clsProject = new Project();
	$clsProperty = new Property();
	$assign_list["clsProject"] = $clsProject;
	$assign_list["clsProperty"] = $clsProperty;
	###
	$classTable = "Furniture";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	$assign_list["pkeyTable"] = $pkeyTable;
	###
	if(isset($_POST['filter'])&&$_POST['filter']=='filter'){
		$hasCond = false;
		$keyword = Input::post('keyword', "");
		$cat_id = (int)Input::post('cat_id', 0);
		if(!empty($keyword)){
			$link .= '&keyword='.$keyword;
		}
		if(!empty($cat_id)){
			$link .= '&cat_id='.$cat_id;
		}
		header('location: '.PCMS_URL.'/?mod='.$mod.$link);
		exit();
	}
	$keyword = Input::get('keyword', "");
	$assign_list["keyword"] = $keyword;
	$cat_id = Input::get('cat_id', "");
	$assign_list["cat_id"] = $cat_id;
	/*List all item*/
	$cond = "1='1'";
	
	#Filter By Keyword
	if(!empty($keyword)){
		$keyword = $core->replaceSpace($keyword);
		$cond .= " and slug like '%".$keyword."%'";
	}
	if(!empty($cat_id)){
		$cond .= " and cat_id like'%|{$cat_id}|%'";
	}
	$orderBy = " reg_date DESC";
	#-------Page Divide---------------------------------------------------------------
	$record_per_page = 50;
	$current_page = (int) Input::get('page',1);
	$total_record = $clsClassTable->countItem($cond);
	$pUrl .= '&page='.$current_page;
	
	$link_page_current = '';
	$query_string = $_SERVER['QUERY_STRING'];
	$lst_query_string = explode('&',$query_string);
	for($i=0;$i<count($lst_query_string);$i++){
		$tmp = explode('=',$lst_query_string[$i]);
		if($tmp[0]!='page')
			$link_page_current .= ($i==0)?'?'.$lst_query_string[$i]:'&'.$lst_query_string[$i];
	}
	$assign_list['link_page_current'] = $link_page_current;
	#
	$link_page_current_2 = '';
	for($i=0;$i<count($lst_query_string);$i++){
		$tmp = explode('=',$lst_query_string[$i]);
		if($tmp[0]!='page'&&$tmp[0]!='vpc_status')
			$link_page_current_2 .= ($i==0)?'?'.$lst_query_string[$i]:'&'.$lst_query_string[$i];
	}
	$assign_list['link_page_current_2'] = $link_page_current_2;
	$config = array(
		'total'	=> $total_record,
		'current_page'	=> $current_page,
		'number_per_page'	=> $record_per_page,
		'link'	=> PCMS_URL.'/index.php'.$link_page_current_2
	);
	$clsPagination = new Pagination();
	$clsPagination->initianize($config);
	$html_pager = $clsPagination->create_links();
	$assign_list["html_pager"] = $html_pager;
	#
	$offset = ($current_page-1)*$record_per_page;
	$limit = " limit {$offset},{$record_per_page}";
	#-------End Page Divide-----------------------------------------------------------
//	$clsClassTable->setDeBug(1);
	$allItem = $clsClassTable->getAll($cond." order by ".$orderBy.$limit); 
	if(!empty($allItem)){
		$arr_property_cached = array();
		
		foreach($allItem as $key => $val){
			$more_information = $clsISO->to_array_json($val['more_information']);
			$unit_id = $val['unit_id'];
			if(!isset($arr_property_cached[$unit_id])){
				$arr_property_cached[$unit_id] = $clsProperty->getTitle($unit_id);
			}
			$allItem[$key]['unit_name'] = $arr_property_cached[$unit_id];
			$allItem[$key]['length'] = $more_information['size']['length'];
			$allItem[$key]['width'] = $more_information['size']['width'];
			$allItem[$key]['height'] = $more_information['size']['height'];
			
			$cat_ids = $clsISO->getArrayByTextSlash($val['cat_id']);
			$cat_names = "";
			for($i=0; $i<count($cat_ids); $i++){
				if(!isset($arr_property_cached[$cat_ids[$i]])){
					$arr_property_cached[$cat_ids[$i]] = $clsProperty->getTitle($cat_ids[$i]);
				}
				$cat_names .= (($cat_names != "")?", ":"").$arr_property_cached[$cat_ids[$i]];
			}
			$allItem[$key]['cat_names'] = $cat_names;
			
			unset($unit_id);
		}
	}
//	$clsISO->print_pre($allItem); die();
	$assign_list["total_record"] = $total_record;
	$assign_list["allItem"] = $allItem; unset($allItem);
	$assign_list["pUrl"] = $pUrl;
}
function default_options(){
	global $smarty,$assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;
	$clsProject = new Project();
	$clsProperty = new Property();
	$assign_list["clsProject"] = $clsProject;
	$assign_list["clsProperty"] = $clsProperty;
	###
	$classTable = "FurnitureOptions";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	$assign_list["pkeyTable"] = $pkeyTable;
	###
	if(isset($_POST['filter'])&&$_POST['filter']=='filter'){
		$hasCond = false;
		$keyword = Input::post('keyword', "");
		$cat_id = (int)Input::post('cat_id', 0);
		if(!empty($keyword)){
			$link .= '&keyword='.$keyword;
		}
		if(!empty($cat_id)){
			$link .= '&cat_id='.$cat_id;
		}
		header('location: '.PCMS_URL.'/?mod='.$mod.'&act='.$act.$link);
		exit();
	}
	$keyword = Input::get('keyword', "");
	$assign_list["keyword"] = $keyword;
	$cat_id = Input::get('cat_id', "");
	$assign_list["cat_id"] = $cat_id;
	/*List all item*/
	$cond = "1='1'";
	
	#Filter By Keyword
	if(!empty($keyword)){
		$keyword = $core->replaceSpace($keyword);
		$cond .= " and slug like '%".$keyword."%'";
	}
	if(!empty($cat_id)){
		$cond .= " and cat_id LIKE '%|{$cat_id}|%'";
	}
	$orderBy = " reg_date DESC";
	#-------Page Divide---------------------------------------------------------------
	$record_per_page = 50;
	$current_page = (int) Input::get('page',1);
	$total_record = $clsClassTable->countItem($cond);
	$pUrl .= '&page='.$current_page;
	
	$link_page_current = '';
	$query_string = $_SERVER['QUERY_STRING'];
	$lst_query_string = explode('&',$query_string);
	for($i=0;$i<count($lst_query_string);$i++){
		$tmp = explode('=',$lst_query_string[$i]);
		if($tmp[0]!='page')
			$link_page_current .= ($i==0)?'?'.$lst_query_string[$i]:'&'.$lst_query_string[$i];
	}
	$assign_list['link_page_current'] = $link_page_current;
	#
	$link_page_current_2 = '';
	for($i=0;$i<count($lst_query_string);$i++){
		$tmp = explode('=',$lst_query_string[$i]);
		if($tmp[0]!='page'&&$tmp[0]!='vpc_status')
			$link_page_current_2 .= ($i==0)?'?'.$lst_query_string[$i]:'&'.$lst_query_string[$i];
	}
	$assign_list['link_page_current_2'] = $link_page_current_2;
	$config = array(
		'total'	=> $total_record,
		'current_page'	=> $current_page,
		'number_per_page'	=> $record_per_page,
		'link'	=> PCMS_URL.'/index.php'.$link_page_current_2
	);
	$clsPagination = new Pagination();
	$clsPagination->initianize($config);
	$html_pager = $clsPagination->create_links();
	$assign_list["html_pager"] = $html_pager;
	#
	$offset = ($current_page-1)*$record_per_page;
	$limit = " limit {$offset},{$record_per_page}";
	#-------End Page Divide-----------------------------------------------------------
//	$clsClassTable->setDeBug(1);
	$allItem = $clsClassTable->getAll($cond." order by ".$orderBy.$limit); 
	if(!empty($allItem)){
		$arr_property_cached = array();
		
		foreach($allItem as $key => $val){
			$more_information = $clsISO->to_array_json($val['more_information']);
			$unit_id = $val['unit_id'];
			if(!isset($arr_property_cached[$unit_id])){
				$arr_property_cached[$unit_id] = $clsProperty->getTitle($unit_id);
			}
			$allItem[$key]['unit_name'] = $arr_property_cached[$unit_id];
			$allItem[$key]['length'] = $more_information['size']['length'];
			$allItem[$key]['width'] = $more_information['size']['width'];
			$allItem[$key]['height'] = $more_information['size']['height'];
			
			$arr_cat_id = $clsISO->getArrayByTextSlash($val['cat_id']);
			$cat_name = "";
			for($i=0; $i< count($arr_cat_id); $i++){
				if(!isset($arr_property_cached["cat"][$arr_cat_id[$i]])){				
					$arr_property_cached["cat"][$arr_cat_id[$i]] = $clsProperty->getTitle($arr_cat_id[$i]);
				}
				$cat_name .= (($i > 0)? ", ":"").$arr_property_cached["cat"][$arr_cat_id[$i]];
			}
			
			$allItem[$key]['cat_name'] = $cat_name;
			
			unset($unit_id);
		}
	}
//	$clsISO->print_pre($allItem); die();
	$assign_list["total_record"] = $total_record;
	$assign_list["allItem"] = $allItem; unset($allItem);
	$assign_list["pUrl"] = $pUrl;
}
function default_loadFormPrice(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$assign_list;
	$user_id = $core->_USER['user_id'];
	$clsFurniture = new Furniture();
	$clsProperty = new Property(); $assign_list['clsProperty'] = $clsProperty;
	#
	$property_keys = Input::post('property_keys', "");
	$property_values = Input::post('property_values', "");
	$furniture_id = (int)Input::post('furniture_id', 0);
	if($furniture_id > 0){
		$oneItem = $clsFurniture->getOne($furniture_id);
		$more_information = $oneItem['more_information'];
		$more_information = !empty($more_information) ? json_decode(html_entity_decode($more_information), true) : array();
		$assign_list['oneItem'] = $oneItem;
		$assign_list['lstProperty'] = $more_information["property"];
	}
//	var_dump($property_keys,$property_values);die;
	$arr_data = [];
	if(count($property_values) == 1){
		$arr_data = explode(",",$property_values[0]);
	}else if(count($property_values) == 2){
		$tmp1 = explode(",",$property_values[0]);
		$tmp2 = explode(",",$property_values[1]);
		for($i=0; $i<count($tmp1); $i++){
			for($j=0; $j<count($tmp2); $j++){
				$arr_data[] = $tmp1[$i]."-".$tmp2[$j];
			}
		}
	}else if(count($property_values) == 3){
		$tmp1 = explode(",",$property_values[0]);
		$tmp2 = explode(",",$property_values[1]);
		$tmp3 = explode(",",$property_values[2]);
		for($i=0; $i<count($tmp1); $i++){
			for($j=0; $j<count($tmp2); $j++){				
				for($k=0; $k<count($tmp3); $k++){
					$arr_data[] = $tmp1[$i]."-".$tmp2[$j]."-".$tmp3[$k];
				}
			}
		}
	}
	$assign_list['arr_data'] = $arr_data;
	$html = $core->build('_ajax.loadFormPrice.tpl');
	echo $html; die();
}
function default_loadFurniture(){
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$assign_list;
	$user_id = $core->_USER['user_id'];
	$clsFurniture = new Furniture();
	$clsFurnitureOptions = new FurnitureOptions();
	$clsProperty = new Property();
	#
	$cat_ids = Input::post('cat_ids', array());	
	$furniture_option_id = (int)Input::post('furniture_option_id', 0);	
	$cat_dup = [];
	if($furniture_option_id > 0){
		$oneItem = $clsFurnitureOptions->getOne($furniture_option_id,"more_information");
		$furniture_option_information = !empty($oneItem['more_information'])?$clsISO->to_array_json($oneItem['more_information']):array();
		$detail_option = $furniture_option_information['detail_option'];
		$detail_option_dup = $furniture_option_information['detail_option_dup'];
		$cat_dup = $furniture_option_information['cat_dup'];
//		var_dump($detail_option_dup,$cat_dup);die;
		$assign_list['detail_option'] = $detail_option;
		$assign_list['detail_option_dup'] = $detail_option_dup;
		$assign_list['cat_dup'] = $cat_dup;
	}
	$data = [
		"result" => false,
		"html"	=>	$html
	];
	if(!empty($cat_ids)){
		$lstCatFurniture = $clsProperty->getAll("`is_trash`=0 and `parent_id`=0 and `is_locked`=0 and `property_id` IN (".implode(",",$cat_ids).") order by `order_no` ASC", "{$clsProperty->pkey},property_code,title");
		foreach($lstCatFurniture as $key => $value){
			
			$is_catdup = 0;
			$lstFurniture = $clsFurniture->getAll("is_trash='0' and is_online='1' and cat_id like '%|".$value['property_id']."|%'");
			foreach($lstFurniture as $k => $v){
				$check_exist_property = 0;
				$more_information = $clsISO->to_array_json($v['more_information']);
				if(!empty($more_information['property'])){
					$check_exist_property = 1;
				}
				$lstFurniture[$k]['is_property'] = $check_exist_property;
				#
				$price_hidden = $property_hidden = $number_hidden = "";
				if(!empty($detail_option)){
					$price_property = $detail_option[$value['property_id']]['funiture'][$v['furniture_id']];
					$lstFurniture[$k]['price_property'] = $price_property;
//					var_dump($price_property['detail']);die;
					foreach($price_property['detail'] as $i=>$property){
						$price_hidden .= (($price_hidden !="")?",":"").$property['price'];
						$property_hidden .= (($property_hidden !="")?",":"").$property['property'];
						$number_hidden .= (($number_hidden !="")?",":"").$property['number'];
					}	
					unset($price_property);
				}			
				$lstFurniture[$k]['price_hidden'] = $price_hidden;
				$lstFurniture[$k]['property_hidden'] = $property_hidden;
				$lstFurniture[$k]['number_hidden'] = $number_hidden;
				
				$price_hidden_dup = $property_hidden_dup = $number_hidden_dup = $key_dup = "";
				if(!empty($cat_dup[$value["property_id"]]) && !empty($detail_option_dup)){
					foreach($cat_dup[$value["property_id"]] as $k_dup){
						$price_property = $detail_option_dup[$k_dup]['funiture'][$v['furniture_id']];
						$lstFurniture[$k]['price_property_dup'][$k_dup] = $price_property;
						foreach($price_property['detail'] as $i=>$property){
//							var_dump($property);die;
							$price_hidden_dup .= (($price_hidden_dup !="")?",":"").$property['price'];
							$property_hidden_dup .= (($property_hidden_dup !="")?",":"").$property['property'];
							$number_hidden_dup .= (($number_hidden_dup !="")?",":"").$property['number'];
						}
						
						$is_catdup = 1;
						$lstFurniture[$k]['key_dup'] = $k_dup;
						$lstFurniture[$k][$k_dup]['price_hidden_dup'] = $price_hidden_dup;
						$lstFurniture[$k][$k_dup]['property_hidden_dup'] = $property_hidden_dup;
						$lstFurniture[$k][$k_dup]['number_hidden_dup'] = $number_hidden_dup;
						
						unset($price_property,$price_hidden_dup,$property_hidden_dup,$number_hidden_dup);
					}
				}
				
//				var_dump($lstFurniture);die;
			}
			$lstCatFurniture[$key]['is_catdup'] = $is_catdup;
			$lstCatFurniture[$key]['cat_dup'] = $cat_dup[$value["property_id"]];
			$lstCatFurniture[$key]['lstFurniture'] = $lstFurniture;			
			unset($lstFurniture);
		}
//		$clsISO->print_pre($lstCatFurniture);die;
		$assign_list['lstCatFurniture'] = $lstCatFurniture;
		if (!empty($lstCatFurniture)){
			$html = $core->build("_ajax.loadFurniture.tpl");
			$data = [
				"result" => true,
				"html"	=>	$html
			];
		}		
	}	
	echo json_encode($data); die();
}
function default_duplicate(){
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$assign_list;
	$user_id = $core->_USER['user_id'];
	$clsFurniture = new Furniture();
	$clsFurnitureOptions = new FurnitureOptions();
	$clsProperty = new Property(); $assign_list['clsProperty'] = $clsProperty;
	#
	$cat_id = (int)Input::post('cat_id', 0);	
	$typeOption = Input::post('typeOption', "option");	
	$id_dup = Input::post('id_dup', "");	
	$furniture_option_id = Input::post('furniture_option_id', 0);	
	$cat_dup = [];
	$data = [
		"result" => false,
		"html"	=>	$html
	];
	if($furniture_option_id > 0 && $cat_id > 0){
		$oneItem = $clsFurnitureOptions->getOne($furniture_option_id,"more_information,title");
		$furniture_option_information = !empty($oneItem['more_information'])?$clsISO->to_array_json($oneItem['more_information']):array();
		$detail_option = $furniture_option_information['detail_option'];
		$detail_option_dup = $furniture_option_information['detail_option_dup'];
		$cat_dup = $furniture_option_information['cat_dup'];
//		var_dump($detail_option,$detail_option_dup,$cat_dup);die;
		$assign_list['oneItem'] = $oneItem;
		$assign_list['detail_option'] = $detail_option;
		$assign_list['detail_option_dup'] = $detail_option_dup;
		$assign_list['cat_dup'] = $cat_dup;
		
		$is_catdup = 0;
		$lstFurniture = $clsFurniture->getAll("is_trash='0' and is_online='1' and cat_id like '%|".$cat_id."|%'");
		foreach($lstFurniture as $k => $v){
			$check_exist_property = 0;
			$more_information = $clsISO->to_array_json($v['more_information']);
			if(!empty($more_information['property'])){
				$check_exist_property = 1;
			}
			$lstFurniture[$k]['is_property'] = $check_exist_property;
			#
			$key_dup = "";
			if($typeOption == "duplicate"){
				$price_hidden_dup = $property_hidden_dup = $number_hidden_dup = "";
				if(!empty($cat_dup[$cat_id]) && !empty($detail_option_dup)){
					foreach($cat_dup[$cat_id] as $k_dup){
						if ($clsISO->checkItemInArray($k_dup,array_keys($detail_option_dup))){
							$key_dup = $k_dup;
							break;
						}
					}
					if($key_dup !== ""){
						$price_property = $detail_option_dup[$key_dup]['funiture'][$v['furniture_id']];
						$lstFurniture[$k]['price_property'] = $price_property;
						foreach($price_property['detail'] as $i=>$property){
							$price_hidden_dup .= (($price_hidden_dup !="")?",":"").$property['price'];
							$property_hidden_dup .= (($property_hidden_dup !="")?",":"").$property['property'];
							$number_hidden_dup .= (($number_hidden_dup !="")?",":"").$property['number'];
						}
						$is_catdup = 1;
						unset($price_property);
					}	
				}
				$lstFurniture[$k]['key_dup'] = $key_dup;
				$lstFurniture[$k]['price_hidden'] = $price_hidden_dup;
				$lstFurniture[$k]['property_hidden'] = $property_hidden_dup;
				$lstFurniture[$k]['number_hidden'] = $number_hidden_dup;
			}else{
//				var_dump($detail_option);die;
				$price_hidden = $property_hidden = $number_hidden = "";
				if(!empty($detail_option)){
					$price_property = $detail_option[$cat_id]['funiture'][$v['furniture_id']];
					$lstFurniture[$k]['price_property'] = $price_property;
					foreach($price_property['detail'] as $i=>$property){
						$price_hidden .= (($price_hidden !="")?",":"").$property['price'];
						$property_hidden .= (($property_hidden !="")?",":"").$property['property'];
						$number_hidden .= (($number_hidden !="")?",":"").$property['number'];
					}	
					unset($price_property);
				}			
				$lstFurniture[$k]['key_dup'] = $key_dup;
				$lstFurniture[$k]['price_hidden'] = $price_hidden;
				$lstFurniture[$k]['property_hidden'] = $property_hidden;
				$lstFurniture[$k]['number_hidden'] = $number_hidden;
			}
		}
//		var_dump($lstFurniture);die;
		$assign_list['cat_id'] = $cat_id;
		$assign_list['is_catdup'] = $is_catdup;
		$assign_list['cat_dup'] = $cat_dup[$cat_id];
		$assign_list['lstFurniture'] = $lstFurniture;		
		$assign_list['lstCatFurniture'] = $lstCatFurniture;		
		$assign_list['uid'] = $clsISO->getUniqid();
		$html = $core->build("_ajax.loadDuplicate.tpl");
		$data = [
			"result" => true,
			"html"	=>	$html
		];
	}
		
	echo json_encode($data); die();
}
function default_propertyFurniture(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$assign_list;
	$user_id = $core->_USER['user_id'];
	$clsFurniture = new Furniture();
	$clsProperty = new Property();
	#	
	$furniture_id = (int)Input::post('furniture_id', 0);	
	$type = Input::post('type', "open");	
	$cat_id = (int)Input::post('cat_id', 0);	
	$typeOption = Input::post('typeOption', "option");	
	$id_dup = Input::post('id_dup', "");	
	$assign_list['cat_id'] = $cat_id;	
	$assign_list['typeOption'] = $typeOption;	
	$assign_list['id_dup'] = $id_dup;	
	$data = [
		"result" => false,
		"html"	=>	$html
	];
	if($type == 'open'){
		if($furniture_id > 0){
			$oneItem = $clsFurniture->getOne($furniture_id,$clsFurniture->pkey.",title,cat_id,more_information");
			$more_information = $clsISO->to_array_json($oneItem['more_information']);
			$property = $more_information['property'];
			$assign_list['oneItem'] = $oneItem;
			$assign_list['property'] = $property;
			if(!empty($property)){
				$html = $core->build("_ajax.openProprertyFurniture.tpl");
				$data = [
					"result" => true,
					"html"	=>	$html
				];
			}		
		}
	}else if($type == 'load'){
		
	}
		
	echo json_encode($data); die();
}
function default_addProperty(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$assign_list;
	$user_id = $core->_USER['user_id'];
	$clsFurniture = new Furniture();
	$clsProperty = new Property();
	#
	$property_default  = ['Kích thước','Chất liệu'];	
	$number_property = Input::post('number_property', 0);	
	$val_default = $property_default[$number_property];
	$assign_list['val_default'] = $val_default;
	$assign_list['number_property'] = $number_property;
	$html = $core->build('_ajax.addProperty.tpl');
	$is_max = ($number_property == count($property_default) - 1)?true:false;
	$data = [
		"is_max" => $is_max,
		"html"	=>	$html,
		"number"	=>	$number_property
	];
	echo json_encode($data); die();
}
function default_loadProperty(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$assign_list;
	$user_id = $core->_USER['user_id'];
	$clsFurniture = new Furniture();
	$clsProperty = new Property();
	#
	$furniture_ids = Input::post('furniture_ids', array());	
	if(!empty($furniture_ids)){
		$lstFurniture = $clsFurniture->getAll("is_trash='0' and is_online='1' and furniture_id IN (".implode(",",$furniture_ids).")");	
		foreach($lstFurniture as $key => $value){
			$more_information = $clsISO->to_array_json($value['more_information']);
			$lstFurniture[$key]['more_information'] = $more_information;
			$lstFurniture[$key]['property'] = $more_information['property'];
			unset($more_information);
		}
		$assign_list['lstFurniture'] = $lstFurniture;
		$html = $core->build("_ajax.loadProperty.tpl");
	}
	
	$data = [
		"is_max" => $is_max,
		"html"	=>	$html,
		"number"	=>	$number_property
	];
	echo json_encode($data); die();
}
function default_edit(){
	global $smarty,$assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;
	$clsProperty = new Property();
	$assign_list['clsProperty'] = $clsProperty;
	
    $user_id                      = $core->_USER['user_id']; #   
    $classTable                   = "Furniture";
    $clsClassTable                = new $classTable;
    $tableName                    = $clsClassTable->tbl;
    $pkeyTable                    = $clsClassTable->pkey;
    $assign_list["clsClassTable"] = $clsClassTable; #    
    $oneItem                      = array();
    $pvalTable                    = isset($_GET[$pkeyTable]) ? intval($_GET[$pkeyTable]) : 0;
	$assign_list['pvalTable'] = $pvalTable;
    if ($pvalTable > 0) {
        $oneItem = $clsClassTable->getOne($pvalTable);
		$more_information = $oneItem['more_information'];
		$more_information = !empty($more_information) ? json_decode(html_entity_decode($more_information), true) : array();
		$assign_list['oneItem'] = $oneItem;
		$assign_list['more_information'] = $more_information;
    }
	
//		$clsISO->print_pre($pvalTable);die;
	if(isset($_POST['submit']) && $_POST['submit'] == "Update"){
		$title = Input::post("iso-title","");
		$arr_data = [];
	 	foreach ($_POST as $key => $val) {
			$tmp = explode('-', $key);
			if ($tmp[0] == 'iso') {
				if($tmp[1] == "price"){
					$arr_data[$tmp[1]] = $clsISO->processSmartNumber(addslashes($val));
				}else{
					$arr_data[$tmp[1]] = addslashes($val);
				}
				
			}
		}
		$cat_ids = Input::post("cat_ids",array());
		$cat_id = $clsISO->makeSlashListFromArrayRoot($cat_ids);
		$arr_data['cat_id'] = $cat_id;
		$property_keys = Input::post("property_keys",[]);
		$property_values = Input::post("property_values",[]);
		$is_property = (int)Input::post("is_property",0);
		$is_online = (int)Input::post("is_online",0);
		$arr_data['slug'] = $core->replaceSpace($title);
		$arr_data['is_online'] = $is_online;
		$number = 0;
		if(!empty($property_values)){
			$number = 1;
			foreach($property_values as $k=>$v){
				$arr_property = explode(",",$v);
				$number *= count($arr_property);
				unset($arr_property);
			}
		}
		
		$more_information["property_keys"] = $property_keys;
		$more_information["property_values"] = $property_values;
		$more_information["is_property"] = $is_property;
		if($number > 0){
			$property = [];
			for($i=0; $i<$number; $i++){
				$property[] = [
					'title'		=>	Input::post('title_'.$i,""),
					'price'		=>	$clsISO->processSmartNumber(Input::post('price_'.$i,"")),
					'weight'	=>	Input::post('weight_'.$i,""),
					'unit'		=>	Input::post('unit_'.$i,""),
				];
			}
			$more_information['property'] = $property;
		}else{
			unset($more_information['property']);
		}
		
		$arr_data['more_information'] = json_encode($more_information);
		if($pvalTable > 0){
			$furniture_code = "NT-".$pvalTable;
			$arr_data["furniture_code"] = $furniture_code;
			if($clsClassTable->updateOne($pvalTable, $arr_data)){			
				#activity log		
				$clsActivityLog = new ActivityLog();
				$log = $clsActivityLog->addActivityLog("Furniture","update");
				if ($_POST['button'] == '_EDIT') {
                    header('location: ' . PCMS_URL . '/?mod=' . $mod . '&act=edit&furniture_id=' . $pvalTable . '&message=updateSuccess');
                } else if ($_POST['button'] == '_CAT') {
                    header('location: ' . PCMS_URL . '/?mod=' . $mod . '&message=updateSuccess');
                } else {
                    header('location: ' . PCMS_URL . '/?mod=' . $mod . '&message=updateSuccess');
                }
			} else {
                header('location: ' . PCMS_URL . '/?mod=' . $mod . '&message=updateFailed');
            }
		}else{
			$max_id = $clsClassTable->getMaxID();
			$furniture_code = "NT-".$max_id;
			$arr_data["furniture_code"] = $furniture_code;
			$arr_data["user_id"] 		= $user_id;
			$arr_data['reg_date'] 		=  time();
			if($clsClassTable->insert($arr_data)){
				if ($_POST['button'] == '_EDIT') {
                    header('location: ' . PCMS_URL . '/?mod=' . $mod . '&act=edit&' . $pkeyTable . '=' . $max_id . '&message=insertSuccess');
                } else if ($_POST['button'] == '_CAT') {
                    header('location: ' . PCMS_URL . '/?mod=' . $mod . '&message=insertSuccess');
                } else {
                    header('location: ' . PCMS_URL . '/?mod=' . $mod . '&message=insertSuccess');
                }
			} else {
                header('location: ' . PCMS_URL . '/?mod=' . $mod . '&message=insertFailed');
            }
		}
	}
	
}
function default_edit_option(){
	global $smarty,$assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn;
	$clsProperty = new Property();
	$assign_list['clsProperty'] = $clsProperty;
	
    $user_id                      = $core->_USER['user_id']; #   
    $classTable                   = "FurnitureOptions";
    $clsClassTable                = new $classTable;
    $tableName                    = $clsClassTable->tbl;
    $pkeyTable                    = $clsClassTable->pkey;
    $assign_list["clsClassTable"] = $clsClassTable; #    
    $oneItem                      = array();
    $pvalTable                    = isset($_GET[$pkeyTable]) ? intval($_GET[$pkeyTable]) : 0;
	$assign_list['pvalTable'] = $pvalTable;
	$more_information = [];
	require_once DIR_COMMON."/Form.php";
	$clsForm = new Form(); $assign_list['clsForm'] = $clsForm;
	$clsForm->setDbTable($tableName,$pkeyTable,$pvalTable);
	$clsForm->addInputTextArea("full",'content',"",'content',255,25,15,1,"style='width:100%'");
    if ($pvalTable > 0) {
        $oneItem = $clsClassTable->getOne($pvalTable);
		$more_information = $oneItem['more_information'];
		$more_information = !empty($more_information) ? json_decode(html_entity_decode($more_information), true) : array();
		$assign_list['oneItem'] = $oneItem;
//		$clsISO->print_pre($more_information);die; 
		$assign_list['more_information'] = $more_information;
    }
	if(isset($_POST['submit']) && $_POST['submit'] == "Update"){
//		$clsISO->print_pre($_POST);die; 
		$title = Input::post("iso-title","");
		$arr_data = [];
		$cat_ids = Input::post("cat_ids",array());
		$bedroom_id = (int)Input::post("bedroom_id",0);
		$cat_id = $clsISO->makeSlashListFromArrayRoot($cat_ids);
		$arr_data = [
			'cat_id'	=>	$cat_id,
			'slug'	=>	$core->replaceSpace($title),
			'table_id'	=>	$bedroom_id,
		];
		
	 	foreach ($_POST as $key => $val) {
			$tmp = explode('-', $key);
			if ($tmp[0] == 'iso') {
				if($tmp[1] == "price"){
					$arr_data[$tmp[1]] = $clsISO->processSmartNumber(addslashes($val));
				}else{
					$arr_data[$tmp[1]] = addslashes($val);
				}
				
			}
		}
		
		/*============================*/
		$furniture_ids = Input::post("furniture_ids",array());
		$prices = Input::post("prices",array());
		$property = Input::post("property",array());
		$number_pro = Input::post("number_pro",array());		
		$option = $option_dup = $cat_dup = [];
		$price_total = 0;
		if(!empty($cat_ids)){
			for($i=0;$i < count($cat_ids); $i++){
				$cat_id = $cat_ids[$i];
				$arr_furniture = [];
				foreach($furniture_ids[$cat_id] as $key => $funiture_id){
					if(!empty($prices[$cat_id][$funiture_id])){
						$arr_property = explode(",",$property[$cat_id][$funiture_id]);
						$arr_prices = explode(",",$prices[$cat_id][$funiture_id]);
						$arr_number = explode(",",$number_pro[$cat_id][$funiture_id]);
						$arr_detail_property = [];
						foreach($arr_prices as $k => $v){
							$price_total += $v*$arr_number[$k];
							$arr_detail_property[] = [
								'price'	=>	$v,
								'property'	=>	$arr_property[$k],
								'number'	=>	$arr_number[$k]
							];
						}
					}
					$arr_furniture[$funiture_id] = [
						'furniture_id'	=>	$funiture_id,
						'detail'	=>	$arr_detail_property
					];
				}

				$option[$cat_id] = [
					'cat_id'	=>	$cat_id,
					'funiture'	=>	$arr_furniture,
				];
				
				#
				$cat_dup = Input::post("cat_dup",array());
				$furniture_ids_dup = Input::post("furniture_ids_dup",array());
				$prices_dup = Input::post("prices_dup",array());
				$property_dup = Input::post("property_dup",array());
				$number_pro_dup = Input::post("number_pro_dup",array());
//				var_dump($_POST);die;
				$arr_furniture_dup = [];
				if(!empty($cat_dup[$cat_id])){
					foreach($cat_dup[$cat_id] as $key_dup){
						if(!empty($furniture_ids_dup[$key_dup]) && !empty($prices_dup[$key_dup])){
							foreach($furniture_ids_dup[$key_dup] as $key => $funiture_id){
								$arr_property = explode(",",$property_dup[$key_dup][$funiture_id]);
								$arr_prices = explode(",",$prices_dup[$key_dup][$funiture_id]);
								$arr_number = explode(",",$number_pro_dup[$key_dup][$funiture_id]);
								$arr_detail_property = [];
								foreach($arr_prices as $k => $v){
									$price_total += $v*$arr_number[$k];
									$arr_detail_property[] = [
										'price'	=>	$v,
										'property'	=>	$arr_property[$k],
										'number'	=>	$arr_number[$k]
									];
								}
								$arr_furniture_dup[$funiture_id] = [
									'furniture_id'	=>	$funiture_id,
									'detail'	=>	$arr_detail_property
								];
							}	
							if(!empty($arr_furniture_dup)){
								$option_dup[$key_dup] = [
									'cat_id'	=>	$cat_id,
									'funiture'	=>	$arr_furniture_dup,
								];
							}
						}
					}
				}
				
								
			}
		}
		$arr_data['total_price'] = $price_total;
		$more_information['detail_option'] = $option;
		$more_information['detail_option_dup'] = $option_dup;
		$more_information['cat_dup'] = $cat_dup;
//		$clsISO->print_pre($more_information);die;
		$arr_data['more_information'] = json_encode($more_information);
//		$clsISO->print_pre($arr_data);die;
		/*============================*/
		if($pvalTable > 0){
			$arr_data['upd_date'] = time();
			if($clsClassTable->updateOne($pvalTable, $arr_data)){
				if ($_POST['button'] == '_EDIT') {
                    header('location: ' . PCMS_URL . '/?mod=' . $mod . '&act=edit_option&' . $pkeyTable . '=' . $pvalTable . '&message=updateSuccess');
                } else if ($_POST['button'] == '_CAT') {
                    header('location: ' . PCMS_URL . '/?mod=' . $mod . '&act=options&message=updateSuccess');
                } else {
                    header('location: ' . PCMS_URL . '/?mod=' . $mod . '&act=options&message=updateSuccess');
                }
			} else {
                header('location: ' . PCMS_URL . '/?mod=' . $mod . '&act=options&message=updateFailed');
            }
		}else{
			$max_id = $clsClassTable->getMaxID();
			$arr_data['reg_date'] = time();
			$arr_data['upd_date'] = time();
			$arr_data['is_online'] = 0;
			$arr_data['is_trash'] = 0;
			
			if($clsClassTable->insert($arr_data)){	
				if ($_POST['button'] == '_EDIT') {
                    header('location: ' . PCMS_URL . '/?mod=' . $mod . '&act=edit_option&' . $pkeyTable . '=' . $max_id . '&message=insertSuccess');
                } else if ($_POST['button'] == '_CAT') {
                    header('location: ' . PCMS_URL . '/?mod=' . $mod . '&act=options&' . '&message=insertSuccess');
                } else {
                    header('location: ' . PCMS_URL . '/?mod=' . $mod . '&act=options&' . '&message=insertSuccess');
                }
			} else {
                header('location: ' . PCMS_URL . '/?mod=' . $mod . '&act=options&' . '&message=insertFailed');
            }
		}
	}
	
}
function default_trash(){
    global $assign_list, $_CONFIG, $_SITE_ROOT, $mod, $act;
    global $core, $clsModule, $clsButtonNav, $oneSetting;
    $user_id       = $core->_USER['user_id']; #    
    $classTable    = "Furniture";
    $clsClassTable = new $classTable;
    $tableName     = $clsClassTable->tbl;
    $pkeyTable     = $clsClassTable->pkey;
    $pvalTable     = isset($_GET[$pkeyTable]) ? intval($_GET[$pkeyTable]) : 0;
    $cat_id        = (int) Input::get('cat_id', 0);
    $pUrl          = '';
    if (intval($cat_id) != 0) {
        $pUrl .= '&cat_id=' . $cat_id;
    }
    if ($pvalTable == 0)
        header('location: ' . PCMS_URL . '/?mod=' . $mod . $pUrl . '&message=notPermission');
    if ($clsClassTable->updateOne($pvalTable, "is_trash='1'")) {			
		#activity log		
		$clsActivityLog = new ActivityLog();
		$log = $clsActivityLog->addActivityLog("Furniture","trash");
        header('location: ' . PCMS_URL . '/?mod=' . $mod . $pUrl . '&message=TrashSuccess');
    }
}
function default_restore(){
    global $assign_list, $_CONFIG, $_SITE_ROOT, $mod, $act;
    global $core, $clsModule, $clsButtonNav, $oneSetting;
    $user_id       = $core->_USER['user_id']; #    
    $classTable    = "Furniture";
    $clsClassTable = new $classTable;
    $tableName     = $clsClassTable->tbl;
    $pkeyTable     = $clsClassTable->pkey;
    $pvalTable     = isset($_GET[$pkeyTable]) ? intval($_GET[$pkeyTable]) : 0;
    $cat_id        = (int) Input::get('cat_id', 0);
    $pUrl          = '';
    if (intval($cat_id) != 0) {
        $pUrl .= '&cat_id=' . $cat_id;
    }
    if ($pvalTable == 0)
        header('location: ' . PCMS_URL . '/?mod=' . $mod . $pUrl . '&message=notPermission');
    if ($clsClassTable->updateOne($pvalTable, "is_trash='0'")) {					
		#activity log		
		$clsActivityLog = new ActivityLog();
		$log = $clsActivityLog->addActivityLog("Furniture","restore");
        header('location: ' . PCMS_URL . '/?mod=' . $mod . $pUrl . '&message=RestoreSuccess');
    }
}
function default_delete(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Furniture";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey;
	
	$cat_id = (int) Input::get('cat_id',0);
	$pvalTable = (int) Input::get('furniture_id',0);
	
	$pUrl = '';
	if(intval($cat_id)> 0){
		$pUrl .= '&cat_id='.$cat_id;
	}
	if($pvalTable == 0){
		header('Location: '.PCMS_URL.'/?mod='.$mod.$param_url.'&message=notPermission');
		exit();
	}
	if($clsClassTable->doDelete($pvalTable)){
		header('Location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=DeleteSuccess');
		exit();
	}
}
function default_delete_option(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "FurnitureOptions";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey;
	
	$cat_id = (int) Input::get('cat_id',0);
	$pvalTable = (int) Input::get('furniture_option_id',0);
	
	$pUrl = '';
	if(intval($cat_id)> 0){
		$pUrl .= '&cat_id='.$cat_id;
	}
	if($pvalTable == 0){
		header('Location: '.PCMS_URL.'/?mod='.$mod.$param_url.'&message=notPermission');
		exit();
	}
	if($clsClassTable->doDelete($pvalTable)){
		header('Location: '.PCMS_URL.'/?mod='.$mod."&act=options".$pUrl.'&message=DeleteSuccess');
		exit();
	}
}
function default_loadBill(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,
	$clsConfiguration,$IsoEditor,$clsSiteCrop;
	$msg = '_error';
	$clsFurniture = new Furniture();
	$clsProperty = new Property();
	$cat_ids = Input::post("cat_ids",array());
	$furniture_ids = Input::post("furniture_ids",array());
	$prices = Input::post("prices",array());
	$property = Input::post("property",array());
	$number_pro = Input::post("number_pro",array());
	$furniture_ids_dup = Input::post("furniture_ids_dup",array());
	$prices_dup = Input::post("prices_dup",array());
	$property_dup = Input::post("property_dup",array());
	$number_pro_dup = Input::post("number_pro_dup",array());
	$cat_dup = Input::post("cat_dup",array());
	$arr_data = [];
	$array_cache = [];
	$html = '<div class="box-title d-flex flex-wrap align-items-center">
				<div class="caption">
					<span class="bold">Tổng đơn</span>
				</div>
			</div>	
			<div class="box-body">
				<div>';
	$price_total = 0;
	if(!empty($cat_ids)){
		for($i=0;$i < count($cat_ids); $i++){
			$cat_id = $cat_ids[$i];
			if(!isset($array_cache['cat'][$cat_id])){
				$array_cache['cat'][$cat_id] = $clsProperty->getTitle($cat_id);
			}
			$cat_name = $array_cache['cat'][$cat_id];
			if(count($furniture_ids[$cat_id]) > 0){
				$html .= '<div><h5>'.$cat_name.'</h5>';	
			}
			foreach($furniture_ids[$cat_id] as $key => $funiture_id){
				if(!isset($array_cache['furniture'][$funiture_id])){
					$array_cache['furniture'][$funiture_id] = $clsFurniture->getTitle($funiture_id);
				}
				if(!empty($prices[$cat_id][$funiture_id])){
					$furniture_name = $array_cache['furniture'][$funiture_id];
					
					$html .= '<div class="d-flex justify-content-between flex-wrap py-2 pl-3">
								<label for="" class="bold mb-0 mr-2">'.$furniture_name.'</label>';

					$arr_property = explode(",",$property[$cat_id][$funiture_id]);
					$arr_prices = explode(",",$prices[$cat_id][$funiture_id]);
					$arr_number = explode(",",$number_pro[$cat_id][$funiture_id]);
					$html .= '<div class="d-flex flex-column flex-fill">';
					foreach($arr_prices as $k => $v){
						$price_total += $v*$arr_number[$k];
						if($v != ""){
							$html .='<div class="d-flex justify-content-between">
										<p class="mr-2">'.$arr_property[$k].'</p>
										<p><span class="text-main">'.$clsISO->priceFormat($v).'đ</span> x <span>'.$arr_number[$k].'</span></p>
									</div>';
						}				
					}

					$html .='</div></div>';
				}
			}
			if(!empty($cat_dup[$cat_id])){
				foreach($cat_dup[$cat_id] as $key_dup){			
					if(count($furniture_ids_dup[$key_dup]) > 0){
						$html .= '<div><h5>'.$cat_name.'</h5>';	
						foreach($furniture_ids_dup[$key_dup] as $key => $funiture_id){
							if(!isset($array_cache['furniture'][$funiture_id])){
								$array_cache['furniture'][$funiture_id] = $clsFurniture->getTitle($funiture_id);
							}
							if(!empty($prices_dup[$key_dup][$funiture_id])){
								$furniture_name = $array_cache['furniture'][$funiture_id];

								$html .= '<div class="d-flex justify-content-between flex-wrap py-2 pl-3">
											<label for="" class="bold mb-0 mr-2">'.$furniture_name.'</label>';

								$arr_property = explode(",",$property_dup[$key_dup][$funiture_id]);
								$arr_prices = explode(",",$prices_dup[$key_dup][$funiture_id]);
								$arr_number = explode(",",$number_pro_dup[$key_dup][$funiture_id]);
								$html .= '<div class="d-flex flex-column flex-fill">';
								foreach($arr_prices as $k => $v){
									$price_total += $v*$arr_number[$k];
									if($v != ""){
										$html .='<div class="d-flex justify-content-between">
													<p class="mr-2">'.$arr_property[$k].'</p>
													<p><span class="text-main">'.$clsISO->priceFormat($v).'đ</span> x <span>'.$arr_number[$k].'</span></p>
												</div>';
									}				
								}

								$html .='</div></div>';
							}
						}
					}
					
				}
			}
			
			
			
		}
	}
	$html .= '</div><div class="border-top d-flex justify-content-between py-2">
					<label for="" class="bold mb-0 fs-4">Tổng tiền</label>
					<span class="text-main fs-3">'.$clsISO->priceFormat($price_total).'đ</span>
					<input type="hidden" name="total_price" value="'.$price_total.'">
				</div>						
			</div>';
	// Return
	echo $html; die();
}
function default_uploadImage(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,
	$clsConfiguration,$IsoEditor,$clsSiteCrop;
	$msg = '_error';
	if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST"){
		$images = $_FILES['images'];
		$pvalTable = (int)Input::post("pvalTable",0);
		$clsTable = Input::post("clsTable","");
		$clsClassTable = new $clsTable();
		$type = Input::post("type","");
		if(!empty($images['name']) && $pvalTable >0){ 
			$ii = 0; //Init
			$oneItem = $clsClassTable->getOne($pvalTable,"more_information");
			if(!empty($oneItem)){
				$more_information = $clsISO->to_array_json($oneItem['more_information']);	
				$arr_images = (!empty($more_information['image']))?$more_information['image']:array();
				$results = $clsClassTable->uploadImage($images,$pvalTable);
				if($type == "images"){
					$arr_images = array_merge($arr_images,$results);
					$more_information['image'] = $arr_images;
					if($clsClassTable->updateOne($pvalTable,["more_information"=>json_encode($more_information)])){
						// Return
						$html = '';
						if(!empty($results)){
							foreach($results as $image){
								$html .= '<div class="item col-xs-3 mb-3" data-fancybox="gallery" href="'.$image.'">
											<img class="rounded drag-item cursor-pointer" src="'.$image.'" alt="avatar" style="width: 100%;height: auto">
										</div>';
							}
						}
						$msg = '_success|||' .$html;
					}
				}else if($type == 'avatar'){
					if(!empty($results) && $clsClassTable->updateOne($pvalTable,["avatar"=>$results[0]])){
						$msg = '_success|||' .$results[0]; 
					}
				}else if($type == 'banner'){
					$more_information['banner'] = $results[0];
					if($clsClassTable->updateOne($pvalTable,["more_information"=>json_encode($more_information)])){
						$msg = '_success|||' .$results[0]; 
					}
				}
			}			
		}
	}
	// Return
	echo $msg; die();
}
function default_addVideo(){
	global $clsISO,$clsEvent,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$clsUser,$_frontIsLoggedin,$_frontIsLoggedin_user_id,$_loggedin,$_lang,$extLang,$_LoggedUser,
	$clsConfiguration,$IsoEditor,$clsSiteCrop;
	$html = '';
	$clsFurnitureOptions = new FurnitureOptions();
	$option_id = (int)Input::post("option_id",0);
	$link_file = Input::post("link_file","");
	if(!empty($link_file) && $option_id >0){ 
		$oneItem = $clsFurnitureOptions->getOne($option_id,"more_information");	
		if(!empty($oneItem)){
			$more_information = (array)json_decode($oneItem['more_information']);
			$more_information['link_video'] = $link_file;
			if($clsFurnitureOptions->updateOne($option_id,["more_information"=>json_encode($more_information)])){
				// Return
				$html = $clsFurnitureOptions->getEmbedVideo($link_file,'100%',250); 
			}
		}
	}
	// Return
	echo $html; die();
}
function default_import(){
	global $core;
	$clsISO = new ISO();
	
	
	require_once DIR_INCLUDES."/phpexcel/PHPExcel.php";
    require_once DIR_INCLUDES."/phpexcel/PHPExcel/IOFactory.php";
	$inputFileName = ABSPATH . "/furniture1.xlsx";
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
	
	$tblColumn =array();
	for ($col = 0; $col < $highestColumnIndex; ++ $col) {
		$cell = $worksheet->getCellByColumnAndRow($col, 1);
		$tblColumn[] = $cell->getValue();
	}
	$index = 0;
	$tblData =array();
	for ($row = 4; $row <= $highestRow; ++ $row) {
		if($worksheet->getCellByColumnAndRow(2, $row)->getValue() != ""){
			for ($col = 2; $col < 11; ++ $col) {
				$cell = $worksheet->getCellByColumnAndRow($col, $row);
				if(PHPExcel_Shared_Date::isDateTime($cell)){
					if(!empty($cell)){
						$date= PHPExcel_Shared_Date::ExcelToPHPObject($cell->getValue());
						$tblData[$index][] =  date_format($date,'d/m/Y');
					}else{
						if($cell->isFormula()){
							$tblData[$index][] = $cell->getCalculatedValue();
						}else{
							$tblData[$index][] = $cell->getValue();
						}

					}
				}else{
					if($cell->isFormula()){
						$tblData[$index][] = $cell->getCalculatedValue();
					}else{
						$tblData[$index][] = $cell->getValue();
					}
				}
			}	
			++$index;
		}	
	}
	$clsISO->print_pre($tblData);die;
	$clsFurniture = new Furniture();
	$clsProperty = new Property();
	$lstItemUnit = $clsProperty->getCacheItems("_FURNITUREUNIT");
	$arrUnit = [];
	foreach($lstItemUnit as $k => $val){
		$arrUnit[$val['property_id']] = $val['title'];
	}
	
//	var_dump(array_search("m2",$arrUnit),$arrUnit);die;
	if(!empty($tblData)){
		$reg_date = time();
		foreach($tblData as $key => $val){
			$project_id = 1;
			$title = $val[0];
			$intro = $val[1];
			$weight = $val[2];
			$unit_name = $val[3];
			$unit_id = array_search($unit_name,$arrUnit);
			$length = (float)$val[4] * 1000;
			$width = (float)$val[5] * 1000;
			$height = (float)$val[6] * 1000;
			$price = $val[7];
			$cat_id = $val[8];
			
			$more_information['size'] = [
				'length'	=>	(int)round($length),
				'width'		=>	(int)round($width),
				'height'	=>	(int)round($height),
			];
			$more_information['unit_id'] = $unit_id;
			$arr_data = [
				'title' 			=> ucfirst($title),
				'weight' 			=> $weight,
				'cat_id' 			=> $cat_id,
				'image' 			=> "",
				'slug'				=>	$core->replaceSpace($title),
				'more_information' 	=> json_encode($more_information, JSON_UNESCAPED_UNICODE),
				'intro' 			=> ucfirst(addslashes($intro)),
				'price' 			=> $price,
				'user_id' 			=> 2,
				'reg_date' 			=> $reg_date
			];
			/*if($clsFurniture->insert($arr_data)){
				unset($arr_data,$more_information);
			}*/
			
		}
	}
	$clsISO->print_pre($tblData); die();
}
?>