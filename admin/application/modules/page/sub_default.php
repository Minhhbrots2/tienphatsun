<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the ISOCMS                         # ||
|| # ISOCMS 6.0.0 VietISO Techical Team (vanthiembui.it@gmail.com)    # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 VietISO JSC.             # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||
|| #################################################################### ||
\*======================================================================*/
function default_default(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];
	
	$clsCategory = new Category(); 
	$assign_list["clsCategory"] = $clsCategory;
	if(isset($_POST['filter'])&&$_POST['filter']=='filter'){
		$cat_id = (int) Input::post('cat_id', 0);
		$keyword = Input::post('keyword', "");
		
		$link = "";
		if($cat_id > 0) $link .= "&cat_id=".$cat_id;
		if(!empty($keyword)) $link .= "&keyword=".$keyword;
		header('Location: '.PCMS_URL.'/?mod='.$mod.'&act='.$act.$link);
		exit();
	}
	/*Get type of list news*/
	$cat_id = (int) Input::get('cat_id',0);
	$type_list = Input::get('type_list','');
	$keyword = Input::get('keyword', "");
	$assign_list["cat_id"] = $cat_id;
	$assign_list["type_list"] = $type_list;
	$assign_list["keyword"] = $keyword;
	/**/
	$classTable = "Page";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	$assign_list["pkeyTable"] = $pkeyTable;
	
	/*List all item*/
	$pUrl = '';
	$cond = "1='1'";
	if($cat_id > 0){
		$pUrl.='&cat_id='.$cat_id;
		$cond .= " and cat_id='$cat_id'";
	}
	#Filter By Keyword
	if(!empty($keyword)){
		$keyword = $core->replaceSpace($keyword);
		$cond .= " and slug like '%".$keyword."%'";
	}
	
	$cond2 = $cond;
	if(!empty($type_list)){
		if($type_list=='Trash'){
			$cond .= " and is_trash=1";
		} else {
			$cond .= " and is_trash=0";
		}
	}
	$orderBy = " reg_date desc";
	#-------Page Divide---------------------------------------------------------------
	$record_per_page = 20;
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
	$allItem = $clsClassTable->getAll($cond." order by ".$orderBy.$limit); 
	//$clsISO->print_pre($allItem); die();
	$assign_list["allItem"] = $allItem; unset($allItem);
	$assign_list["pUrl"] = $pUrl;
	#
	$assign_list["number_trash"] = $clsClassTable->countItem("is_trash=1 and ".$cond2);
	$assign_list["number_item"] = $clsClassTable->countItem("is_trash=0 and ".$cond2);
	$assign_list["number_all"] = $clsClassTable->countItem($cond2);
}
function default_edit(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$dbconn,$clsConfiguration,$clsISO;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Page";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	#
	$clsCategory = new Category(); 
	$assign_list["clsCategory"] = $clsCategory;
	$cat_id = (int) Input::get('cat_id',0);
	#
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	$assign_list['pvalTable'] = $pvalTable;
	#
	$oneItem = array();
	$more_information = [];
	if($pvalTable > 0){
		$oneItem = $clsClassTable->getOne($pvalTable);
		$cat_id = (int) $oneItem['cat_id'];
		$more_information = $clsISO->to_array_json($oneItem["more_information"]);
	}
	$assign_list["cat_id"] = $cat_id;
	$assign_list["oneItem"] = $oneItem;
	$assign_list["more_information"] = $more_information;
	#-------------Update Config Meta
	$clsMeta = new Meta(); 
	$assign_list["clsMeta"] = $clsMeta;
	$linkMeta = $clsClassTable->getLink($pvalTable);
	$allMeta = $clsMeta->getAll("config_link='{$linkMeta}' limit 0,1",$clsMeta->pkey);
	$meta_id = (int) $allMeta[0]['meta_id'];
	$assign_list["meta_id"] = $meta_id; 
	$assign_list["oneMeta"] = $clsMeta->getOne($meta_id); 
	
	require_once DIR_COMMON."/Form.php";
	$clsForm = new Form();
	$clsForm->setDbTable($tableName,$pkeyTable,$pvalTable);
	$assign_list["clsForm"] = $clsForm;
	$clsForm->addInputTextArea("",'intro',"",'intro',255,25,3,1,"style='width:100%'");
	$clsForm->addInputTextArea("full",'content',"",'content',255,25,15,1,"style='width:100%'");
	if($string!='' && $pvalTable==0){
		header('Location:'.PCMS_URL.'/index.php?&mod='.$mod.'&message=notPermission');
	}
	#=========================================#
	if(isset($_POST['submit']) && $_POST['submit'] =='Update'){
		$cat_id = (int) Input::post('iso-cat_id');
		$contacts = Input::post('contacts',"");
		if($pvalTable>0){
			$set = ""; $firstAdd = 0;
			foreach($_POST as $key=>$val){
				$tmp = explode('-',$key);
				if($tmp[0]=='iso'){
					if($firstAdd==0){
						$set .= $tmp[1]."='".addslashes($val)."'";
						$firstAdd = 1;
					} else{
						$set .= ",".$tmp[1]."='".addslashes($val)."'";
					}
				}
			}
			$set .= ",upd_date='".time()."'";
			$set .= ",is_online='".Input::post('is_online',0)."'";
			$set .= ",is_about_us='".Input::post('is_about_us',0)."'";
			$set .= ",user_id_update='".$user_id."'";
			$set .= ",slug='".$core->replaceSpace($_POST['iso-title'])."'";
			#--Special Field: image
			if(_isoman_use){
				$image = Input::post('isoman_url_image');
			} else{
				$image = Input::post('image_src');
			}
			if(!empty($image)){
				$set .= ",image='".addslashes($image)."'";
			}
			#
			$pUrl = '';
			if($cat_id > 0) $pUrl .= '&cat_id='.$cat_id;
			$more_information = $clsISO->to_array_json($oneItem["more_information"]);
			$more_information["contacts"] = $contacts;
			$set .= ",more_information='".json_encode($more_information,JSON_UNESCAPED_UNICODE)."'";
			if($clsClassTable->updateOne($pvalTable,$set)) {
				//$clsClassTable->crawImage($pvalTable);
				$config_value_title = Input::post('config_value_title');
				$config_value_intro = Input::post('config_value_intro');
				if(!empty($config_value_title)){
					if($meta_id==0){
						$clsMeta->insertOne("config_link,reg_date,meta_id","'".$linkMeta."','".time()."','".$clsMeta->getMaxId()."'");
						$allMeta = $clsMeta->getAll("config_link='".$linkMeta."'",$clsMeta->pkey);
						$meta_id = (int) $allMeta[0]['meta_id'];
					}
					$clsMeta->updateOne($meta_id,"config_value_intro='".addslashes($config_value_intro)."',config_value_title='".addslashes($config_value_title)."',upd_date='".time()."'");
				}
				if($_POST['button']=='_EDIT'){
					header('Location: '.PCMS_URL.'/?mod='.$mod.'&act=edit&'.$pkeyTable.'='.$string.'&message=updateSuccess');
				}else{
					header('Location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=updateSuccess');
				}
			} else{
				header('Location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=updateFailed');
			}
		} else{
			$value = ""; $firstAdd = 0; $field = "";
			$more_information = array();
			foreach($_POST as $key=>$val){
				$tmp = explode('-',$key);
				if($tmp[0]=='iso'){
					if($firstAdd==0){
						$field .= $tmp[1];
						$value .= "'".addslashes($val)."'";
						$firstAdd = 1;
					} else{
						$field .= ','.$tmp[1];
						$value .= ",'".addslashes($val)."'";
					}
				}
			}
			#
			$pvalTable = $clsClassTable->getMaxId();
			$field.= ",user_id,user_id_update,reg_date,upd_date,slug,{$clsClassTable->pkey},is_online,is_about_us,more_information";
			$value.= ",'".$user_id."','".$user_id."','".time()."','".time()."'";
			$value.= ",'".$core->replaceSpace($_POST['iso-title'])."','".$pvalTable."'";
			$value.= ",'".Input::post('is_online',0)."'"; //print_r($value); die();
			$value.= ",'".Input::post('is_about_us',0)."'";
			
			$more_information["contacts"] = $contacts;
			$value.= ",'".json_encode($more_information,JSON_UNESCAPED_UNICODE)."'";
			#--Special Field: image
			if(_isoman_use){
				$image = Input::post('isoman_url_image');
			} else{
				$image = Input::post('image_src');
			}
			if(!empty($image)){
				$field.= ",image";
				$value.= ",'".addslashes($image)."'";
			}
			#
			$pUrl = '';
			if($cat_id > 0) $pUrl .= '&cat_id='.$cat_id;
			//$clsISO->print_pre($field.'<br />'.$value); die();
			//$clsClassTable->setDebug(true);
			if($clsClassTable->insertOne($field,$value,false)){
				//$clsClassTable->crawImage($pvalTable);
				if ($_POST['button'] == '_EDIT') {
					header('Location:'.PCMS_URL.'/?mod='.$mod.'&act=edit&'.$pkeyTable.'='.$core->encryptID($pvalTable).'&message=insertSuccess');
				}else {
					header('Location:'.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=insertSuccess');
				}
			} else{
				header('Location: '.PCMS_URL.'/?mod='.$mod.'&message=insertFailed');
			}
		}
	}
}
function default_trash(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Page";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;

	$cat_id = (int) Input::get('cat_id',0);
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	
	$pUrl = '';
	if($cat_id > 0){
		$pUrl .= '&cat_id='.$cat_id;
	}
	if($pvalTable == 0){
		header('Location: '.PCMS_URL.'/?mod='.$mod.$param_url.'&message=notPermission');
	}
	if($clsClassTable->updateOne($pvalTable,"is_trash='1'")){
		header('Location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=TrashSuccess');
	}
}
function default_restore(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Page";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	
	$cat_id = (int) Input::get('cat_id',0);
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	
	$pUrl = '';
	if(intval($cat_id)> 0){
		$pUrl .= '&cat_id='.$cat_id;
	}
	if($pvalTable == 0){
		header('Location: '.PCMS_URL.'/?mod='.$mod.$param_url.'&message=notPermission');
	}
	if($clsClassTable->updateOne($pvalTable,"is_trash='0'")){
		header('Location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=RestoreSuccess');
	}
}
function default_delete(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Page";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey;
	
	$cat_id = (int) Input::get('cat_id',0);
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	
	$pUrl = '';
	if(intval($cat_id)> 0){
		$pUrl .= '&cat_id='.$cat_id;
	}
	if($pvalTable == 0){
		header('Location: '.PCMS_URL.'/?mod='.$mod.$param_url.'&message=notPermission');
	}
	if($clsClassTable->doDelete($pvalTable)){
		header('Location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=DeleteSuccess');
	}
}
function default_move(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Page";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey;
	#
	$cat_id = (int) Input::get('cat_id',0);
	$direct = (int) Input::get('direct',"moveup");
	$string = isset($_GET[$pkeyTable])? ($_GET[$pkeyTable]) : '';
	$pvalTable = intval($core->decryptID($string));
	
	$one = $clsClassTable->getOne($pvalTable);
	$order_no = $one['order_no'];
	if(($string!='' && $pvalTable == 0) || $direct==''){
		header('Location: '.PCMS_URL.'/?mod='.$mod);
	}
	$pUrl = '';
	$where = '1=1 and is_trash=0';
	if(intval($cat_id) > 0){
		$pUrl .= '&cat_id='.$cat_id;
		$where.=" and (cat_id='$cat_id' or list_cat_id like '%|$cat_id|%')";
	}
	if($direct=='moveup'){
		$lst = $clsClassTable->getAll($where." and order_no > $order_no order by order_no asc limit 0,1");
		$clsClassTable->updateOne($pvalTable,"order_no='".$lst[0]['order_no']."'");
		$clsClassTable->updateOne($lst[0][$clsClassTable->pkey],"order_no='".$order_no."'");
	}
	if($direct=='movedown'){
		$lst = $clsClassTable->getAll($where." and order_no < $order_no order by order_no desc limit 0,1");
		$clsClassTable->updateOne($pvalTable,"order_no='".$lst[0]['order_no']."'");
		$clsClassTable->updateOne($lst[0][$clsClassTable->pkey],"order_no='".$order_no."'");
	}
	if($direct=='movetop'){
		$lst = $clsClassTable->getAll($where." and order_no > $order_no order by order_no asc");
		$clsClassTable->updateOne($pvalTable,"order_no='".$lst[count($lst)-1]['order_no']."'");
		$lstItem = $clsClassTable->getAll($where." and $pkeyTable <> '$pvalTable' and order_no > $order_no order by order_no asc");
		for($i=0;$i<count($lstItem);$i++) {
			$clsClassTable->updateOne($lstItem[$i][$clsClassTable->pkey],"order_no='".($lstItem[$i]['order_no']-1)."'");	
		}
	}
	if($direct=='movebottom'){
		$lst = $clsClassTable->getAll($where." and order_no < $order_no order by order_no desc");
		$clsClassTable->updateOne($pvalTable,"order_no='".$lst[count($lst)-1]['order_no']."'");
		$lstItem = $clsClassTable->getAll($where." and $pkeyTable <> '$pvalTable' and order_no < $order_no order by order_no desc");
		for($i=0;$i<count($lstItem);$i++) {
			$clsClassTable->updateOne($lstItem[$i][$clsClassTable->pkey],"order_no='".($lstItem[$i]['order_no']+1)."'");	
		}
	}
	header('Location: '.PCMS_URL.'/?mod='.$mod.$pUrl.'&message=PositionSuccess');
}
function default_suggestion(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	require_once DIR_COMMON."/Form.php";
	$suggestion = $clsConfiguration->getValue('suggestion');	
	$assign_list['suggestion'] = $suggestion;
	if(isset($_POST['submit']) && $_POST['submit']='UpdateSuggestion'){
		$clsConfiguration->updateValue("suggestion", $_POST['suggestion']);
		header('location:'.PCMS_URL.'?mod='.$mod.'&act='.$act.'&message=updateSuccess');
		exit();
	}
}
function default_add_input_suggest(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$uid = $clsISO->getUniqid();
	$html = '<div class="mb-3 item_suggestion w-100 p-3 border">
		<button class="btn btn-danger ml-2 mb-3 fr" title="Xoá" type="button" onclick="$Core.suggestion.delete_input_suggestion(this,event)"><i class="fa fa-minus" aria-hidden="true"></i></button><textarea id="textarea_editor_'.$uid.'" class="textarea_intro_editor" name="suggestions[]" style="width:100%"></textarea>
	</div>';
	echo $html;
}
function default_loan_interest(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$loan_interest = $clsConfiguration->getValue('loan_interest');
	$lst_loan_interest = $clsISO->to_array_json($loan_interest);
	$assign_list['lst_loan_interest'] = $lst_loan_interest;
	if(isset($_POST['submit']) && $_POST['submit']='Update'){
		$loan_interest = Input::post("data",[]);		
		$clsConfiguration->updateValue("loan_interest", json_encode($loan_interest));
		header('location:'.PCMS_URL.'?mod='.$mod.'&act='.$act.'&message=updateSuccess');
	}
}
function default_add_item_loan_interest(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$uid = $clsISO->getUniqid();
	$html = '<div class="item_bank col-md-6 mb-3">  
		<div class="p-3 next-card">  
			<div class="item_top d-flex justify-content-between align-items-start mb-2">
				<h2 class="title_item m-0 flex-fill" style="font-size:18px"></h2>
				<button class="btn ml-2" type="button" type="button" data-toggle="collapse" data-target="#'.$uid.'" aria-expanded="false" aria-controls="'.$uid.'"><i class="fa fa-chevron-down" aria-hidden="true"></i></button>
			</div>
			<div class="item_body collapse in" id="'.$uid.'">
				<div class="form-row form-group">
					<div class="col-md-12">
						<label class="col-form-label">Tên ngân hàng <span class="text-red">*</span></label>
						<input type="text" class="form-control require" required="true" placeholder="Tên ngân hàng" onKeyUp="$Core.loan_interest.setTitleItem(this,event)" name="data['.$uid.'][bank_name]" value="">
					</div>
				</div>
				<div class="form-row form-group">
					<div class="col-md-6">
						<label class="col-form-label">Lãi suất ưu đãi <span class="text-red">*</span></label>
						<div class="input-group input-group-merge d-flex align-items-center">
							<input type="number" class="form-control mr-2" value="" placeholder="Lãi suất ưu đãi (%)" id="introRate" name="data['.$uid.'][introRate]" min="0" step="0.1">
							<span class="input-group-text cursor-pointer">%</span>
						</div>
					</div>
					<div class="col-md-6">
						<label class="col-form-label">Thời gian ưu đãi <span class="text-red">*</span></label>										
						<div class="input-group d-flex">
							<input type="number" class="form-control calc_field" value="" placeholder="Nhập thời gian" id="introMonths" name="data['.$uid.'][introMonths]" min="0">
							<select class="form-control form-select" name="data['.$uid.'][introMonthsUnit]">
								<option value="_MONTH">Tháng</option>
								<option selected="" value="_YEAR">Năm</option>
							</select>
						</div>
					</div>
				</div>
				<div class="form-row form-group">
					<div class="col-md-12">
						<label class="col-form-label">Lãi suất sau ưu đãi <span class="text-red">*</span></label>
						<div class="input-group input-group-merge d-flex align-items-center">
							<input type="number" class="form-control mr-2" value="" placeholder="Lãi suất sau ưu đãi (%)" id="rate" name="data['.$uid.'][rate]" min="0" step="0.1">
							<span class="input-group-text cursor-pointer">%</span>
						</div>
					</div>
				</div>
				<div class="d-flex justify-content-end"><button class="btn btn-danger ml-2" title="Xoá" type="button" onclick="$Core.loan_interest.delete_item_bank(this,event)">Xoá</button></div>
			</div>
		</div>
	</div>';
	echo $html;
}
function default_performance(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$investment_performance_revenue = $clsConfiguration->getValue('investment_performance_revenue');
	$lst_investment_performance_revenue = $clsISO->to_array_json($investment_performance_revenue);
	$assign_list['lst_investment_performance_revenue'] = $lst_investment_performance_revenue;
	$investment_performance_expense = $clsConfiguration->getValue('investment_performance_expense');
	$lst_investment_performance_expense = $clsISO->to_array_json($investment_performance_expense);
	$assign_list['lst_investment_performance_expense'] = $lst_investment_performance_expense;
	//	$clsISO->print_pre($lst_investment_performance_revenue);
	//	$clsISO->print_pre($lst_investment_performance_expense);die;
	if(isset($_POST['submit']) && $_POST['submit']='Update'){
	//	$clsISO->print_pre($_POST);die;
		$investment_performance_revenue = Input::post("revenue",[]);		
		$clsConfiguration->updateValue("investment_performance_revenue", json_encode($investment_performance_revenue));
		$investment_performance_expense = Input::post("expense",[]);		
		$clsConfiguration->updateValue("investment_performance_expense", json_encode($investment_performance_expense));
		header('location:'.PCMS_URL.'?mod='.$mod.'&act='.$act.'&message=updateSuccess');
	}
}
function default_add_item_performance(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$type = Input::post("type","child-1");
	$property = Input::post("property","revenue");
	$uid = $clsISO->getUniqid();
	$uid1 = Input::post("uid1","");
	$assign_list['type'] = $type;
	$assign_list['property'] = $property;
	$assign_list['uid'] = $uid;
	$assign_list['uid1'] = $uid1;
	$html = $core->build("_ajax.loadFormPerfomance.tpl");
	echo $html;die;
}
function default_help_page(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$help_page = $clsConfiguration->getValue('help_page');
	$lst_help_page = $clsISO->to_array_json($help_page);
	$assign_list['lst_help_page'] = $lst_help_page;
	if(isset($_POST['submit']) && $_POST['submit']='Update'){
		// $clsISO->print_pre($_POST);die;
		$help_page = Input::post("help_page",[]);		
		$clsConfiguration->updateValue("help_page", json_encode($help_page));
		header('location:'.PCMS_URL.'?mod='.$mod.'&act='.$act.'&message=updateSuccess');
	}
}
function default_report(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	
	$report_configs = $clsConfiguration->getValue('report_configs');
	$report_configs = $clsISO->to_array_json($report_configs);
	// $clsISO->print_pre($report_configs); die();
	$assign_list['report_configs'] = $report_configs;
}
function default_open_field(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	
	$tp = Input::post('tp', "_add");
	$field_id = Input::post('field_id');
	$group_id = Input::post('group_id');
	$assign_list['field_id'] = $field_id;
	$assign_list['group_id'] = $group_id;
	
	$report_configs = $clsConfiguration->getValue('report_configs');
	$report_configs = $clsISO->to_array_json($report_configs);
	
	$oneField = array("title" => "");
	if($field_id == "" && $group_id != ""){
		$oneField = $report_configs[$group_id];
	} else if($field_id != "" && $group_id != ""){
		$oneField = $report_configs[$group_id]['list_items'][$field_id];
	}
	$assign_list['tp'] = $tp;
	$assign_list['oneField'] = $oneField;
	// $clsISO->print_pre($oneField); die();
	// Return
	$html = $core->build('_ajax.open_field.tpl');
	echo $html; die();	
}
function default_pop_save_field(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	
	$msg = "";
	$tp = Input::post('tp', "_add");
	$field_id = Input::post('field_id');
	$group_id = Input::post('group_id');
	$report_configs = $clsConfiguration->getValue('report_configs');
	$report_configs = $clsISO->to_array_json($report_configs);
	if($group_id == "" && $field_id == ""){
		$report_configs[$clsISO->getUniqid()] = array(
			'title' => Input::post("title"),
			'reg_date' => time(),
			'upd_date' => time(),
			'order_no' => $order_no,
			'user_id' => $core->_USER['user_id'],
			'user_id_update' => $core->_USER['user_id'],
			'list_items' => array()
		);
	} else if($field_id == "" && $group_id != ""){
		if($tp== "_add"){
			$report_configs[$group_id]['list_items'][$clsISO->getUniqid()] = array(
				'title' => Input::post("title"),
				'reg_date' => time(),
				'upd_date' => time(),
				'order_no' => $order_no,
				'user_id' => $core->_USER['user_id'],
				'user_id_update' => $core->_USER['user_id']
			);
		} else if($tp=='_edit') {
			$report_configs[$group_id]['upd_date'] = time();
			$report_configs[$group_id]['title'] = Input::post("title");
			$report_configs[$group_id]['user_id_update'] = $core->_USER['user_id'];
		}
	} else if($field_id != "" && $group_id != ""){
		$report_configs[$group_id]['list_items'][$field_id]['title'] = Input::post('title');
		$report_configs[$group_id]['list_items'][$field_id]['upd_date'] = time();
		$report_configs[$group_id]['list_items'][$field_id]['user_id_update'] = $core->_USER['user_id'];
	}
	if($clsConfiguration->updateValue("report_configs", json_encode($report_configs, JSON_UNESCAPED_UNICODE))){
		$msg = "_success";
	}
	// Return
	echo $msg; die();
	
}
function default_addProperty(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$assign_list;
	#
	$uid = $clsISO->getUniqid();
	$html = '<div class="form-group item_property">
					<div class="border p-3">
						<div class="row" id="'.$uid.'">
							<div class="col-12 col-md-6 mb-2">
								<label>Tên dự án</label>
								<input type="text" class="form-control property_keys" name="contacts['.$uid.'][project_name]" value="" placeholder="Nhập tên dự án">
							</div>
							<div class="col-10 col-md-6 mb-2">
								<label>Liên hệ</label>												
								<input type="text" class="form-control property_keys" name="contacts['.$uid.'][contact][]" value="" placeholder="Nhập liên hệ">
							</div>
						</div>
						<div class="d-flex justify-content-between">
							<a class="btn" href="javascript:void(0)" onClick="$Core.page.addContact(this,event)" toId="'.$uid.'"><i class="fa fa-plus-circle mr-2" aria-hidden="true"></i>Thêm liên hệ</a>	
							<a class="btn btn-icon text-danger" href="javascript:void(0)" onclick="$Core.page.deleteItem(this,event)"><i class="fa fa-trash mr-2" aria-hidden="true"></i> Xóa liên hệ</a>
						</div>
						
					</div>
				</div>';
	$res = [
		"result" => true,
		"html"	=>	$html
	];
	echo json_encode($res); die();
}
?>