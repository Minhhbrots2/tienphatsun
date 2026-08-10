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
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];
	#
	$type = isset($_GET['type'])?$_GET['type']:'NEWS';
	$assign_list["type"] = $type;
	#
	$classTable = "Category";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	$assign_list["pkeyTable"] = $pkeyTable;
	#
	$lstType = $clsClassTable->getListType();
	$assign_list["lstType"] = $lstType;
	#
	if($clsClassTable->getNameType($type)==''){
		header("location: ".PCMS_URL.'&message=NotPermission');
		exit();
	}
}
function default_ajLoadListSysCategory(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod,$act,$clsISO;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Category";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	$assign_list["pkeyTable"] = $pkeyTable;
	#
	$html=""; $lstItem = array();
	$type = Input::post('type','_NEWS');
	$lstItem = $clsClassTable->makeList(0,$type,"");
	//$clsISO->print_pre($lstItem); die();
	if(!empty($lstItem)){ $i=0; // Init
		foreach($lstItem as $key=>$val){
			$html.='<tr data="'.$key.'" class="'.($i%2==0?'row1':'row2').'">';
			$html.='<td class="index">'.$val['cat_id'].'</td>';
			$html.='<td>'.$val['title'].'</td>';
			$html.='<td class="text-center">
						<a href="javascript:void(0);" class="SiteClickPublic" clsTable="Category" pkey="'.$pkeyTable.'" sourse_id="'.$key.'" rel="'.$val['is_online'].'" title="'.$core->get_Lang('Click to change status').'">
							'.($val['is_online']==1?'<i class="fa fa-check-circle green"></i>':'<i class="fa fa-minus-circle red"></i>').'
						</a>
					</td>';
			$html.='<td class="text-center">
						'.($clsClassTable->checkMove($key,$type,'_UP') ? '<a href="javascript:void();" title="Di chuyển xuống lên đầu" cat_id="'.$key.'" class="btnMoveStepOne" direct="up"><i class="icon-circle-arrow-up"></i></a>' : '').'
				   </td>';
			$html.='<td class="text-center">
						'.($clsClassTable->checkMove($key,$type,'_DOWN') ? '<a href="javascript:void();" title="Di chuyển xuống dưới cùng" cat_id="'.$key.'" class="btnMoveStepOne" direct="down"><i class="icon-circle-arrow-down"></i></a>' : '').'
				   </td>';
			$html.='<td class="text-center">
						'.($clsClassTable->checkMove($key,$type,'_UP') ? '<a href="javascript:void();" title="Di chuyển lên" cat_id="'.$key.'" class="btnUpStepTwo" direct="up"><i class="icon-arrow-up"></i></a>' : '').'
				   </td>';
			$html.='<td class="text-center">
						'.($clsClassTable->checkMove($key,$type,'_DOWN') ? '<a href="javascript:void();" title="Di chuyển xuống" cat_id="'.$key.'" class="btnUpStepTwo" direct="down"><i class="icon-arrow-down"></i></a>' : '').'
				   </td>';
			$html.='<td class="text-center" style="white-space: nowrap;">
						<div class="btn-group">
							<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown">
								<i class="icon-cog"></i> <span class="caret"></span>
							</button>
							<ul class="dropdown-menu" style="right:0px !important">
								<li><a href="'.DOMAIN_NAME.$clsClassTable->getLink($key).'" target="_blank" title="'.$core->get_Lang('view').'"><i class="icon-eye-open"></i> <span>'.$core->get_Lang('view').'</span></a></li>
								<li><a href="javascript:void();" title="Edit" class="btnEditCategory" cat_id="'.$val['cat_id'].'"><i class="icon-edit"></i> <span>'.$core->get_Lang('edit').'</span></a></li>';
								if($val['is_special']!=1) {
									$html.='<li><a href="javascript:void();" title="Delete" class="btnDeleteCategory" cat_id="'.$val['cat_id'].'"><i class="icon-remove"></i><span>'.$core->get_Lang('delete').'</span></a></li>';
								}
			$html.='		</ul>
						</div>
					</td>
				</tr>';
			++$i;
		}
	}else{
		$html.='<tr>
			<td class="text-center" colspan="10">
				'.$core->get_Lang('notfoundanyrecord').'
			</td>
		</tr>';	
	}
	echo $html; die();
}
function default_ajOpenCategory(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$user_id = $core->_USER['user_id'];
	#
	$classTable = "Category";
	$clsClassTable = new $classTable;
	$tableName = $clsClassTable->tbl;
	$pkeyTable = $clsClassTable->pkey ;
	$assign_list["clsClassTable"] = $clsClassTable;
	$assign_list["pkeyTable"] = $pkeyTable;
	
	$tp = Input::post('tp');
	$type = Input::post('type');
	$cat_id = (int) Input::post('cat_id',0);
	
	$action = '_add';
	$oneItem = array(
		'title' 	=> '',
		'titlelg' 	=> "",
		'image'		=> "",
		'image_Icon'=> "",
		'intro'		=> "",
		'is_special'=> "0"
	);
	if($cat_id > 0){
		$action= '_edit';
		$oneItem = $clsClassTable->getOne($cat_id);
	}
	if($tp=='F'){
		$namePage = $core->get_Lang('Add new category').' - '.$clsClassTable->getNameType($type);;
		if($cat_id > 0){
			$namePage = $clsClassTable->getTitle($cat_id).' - '.$clsClassTable->getNameType($type);
		}
		#
		$html ='<div class="modal-dialog modal-standard">
			<div class="modal-content">
				<div class="modal-header"> 
					<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
					<h3 class="modal-title"><strong>'.$namePage.'</strong></h3>
				</div>
				<form method="post" enctype="multipart/form-data">
					<div class="modal-body form-horizontal">
						<div class="form-group">
							<label class="col-md-2 text-right col-form-label">'.$core->get_Lang('Title').'<span class="text-red">*</span></label>
							<div class="col-md-10 col-xs-12">
								<input type="text" name="title" '.($oneItem['is_special']==1?'disabled="disabled"':'').' class="form-control required fontLarge" value="'.$oneItem['title'].'" placeholder="Nhập tiêu đề danh mục" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 text-right col-form-label">'.$core->get_Lang('ParentCategory').'</label>
							<div class="col-md-10 col-xs-12">
								<select class="form-control" name="parent_id">
									'.$clsClassTable->makeSelectboxOption(0,$type,$oneItem['parent_id']).'
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 text-right col-form-label">'.$core->get_Lang('ShortIntro').'</label>
							<div class="col-md-10 col-xs-12">
								<textarea id="Category_Intro" class="isoTextArea" data-column="intro" rows="5" style="width:100%;">'.$clsClassTable->getIntro($cat_id).'</textarea>
							</div>
						</div>
						'.($type=='_PRODUCT' ? '<div class="form-group">
							<label class="col-md-2 text-right col-form-label">'.$core->get_Lang('Banner').'</label>
							<div class="col-md-10 col-xs-12">
								<div class="input-group">
									<input type="text" class="form-control" name="image_Icon" placeholder="Chọn hình ảnh làm icon" id="isoman_url_image_Icon" value="'.$oneItem['image_Icon'].'">
									<div class="input-group-btn">
										<button class="btn btn-default ajOpenDialog" isoman_for_id="image_Icon" isoman_val="'.$oneItem['image_Icon'].'" isoman_name="image_Icon" style="padding:9px 10px">'.$core->makeIcon('image').'</button>
									</div>	
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 text-right col-form-label">'.$core->get_Lang('Image').'</label>
							<div class="col-md-10 col-xs-12">
								<div class="input-group">
									<input type="text" class="form-control" placeholder="Chọn hình ảnh làm ảnh đại diện" name="image" id="isoman_url_image" value="'.$oneItem['image'].'">
									<div class="input-group-btn">
										<button class="btn btn-default ajOpenDialog" isoman_for_id="image" isoman_val="'.$oneItem['image'].'" isoman_name="image" style="padding:9px 10px">'.$core->makeIcon('image').'</button>
									</div>	
								</div>
							</div>
						</div>' : '').'
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary submitClick clickToSaveCategory" action="'.$action.'" cat_id="'.$cat_id.'" _type="'.$type.'">
							<i class="icon-ok icon-white"></i> '.($cat_id > 0 ? $core->get_Lang('save'):$core->get_Lang('update')).'
						</button> 
						<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">'.$core->get_Lang('Close').'</button>
					</div>
				</form>
			</div>
		</div>';
		echo $html; die();
	} else if($tp=='S'){
		$title = Input::post('title');
		$slug = $core->replaceSpace($title);
		$intro = Input::post('intro');
		$parent_id = (int) Input::post('parent_id',0);
		if($cat_id==0){
			if($clsClassTable->countItem("_type='{$type}' and parent_id='{$parent_id}' and slug='{$slug}'") > 0){
				echo '_EXIST'; 
				die();
			}else{
				$max_id = $clsClassTable->getMaxId();
				$f="cat_id,user_id,parent_id,title,slug,intro,order_no,reg_date,upd_date,_type,image,image_Icon,is_online";
				$v="'{$max_id}','{$user_id}','{$parent_id}','".addslashes($title)."','".addslashes($slug)."'
				,'".addslashes($intro)."','".$clsClassTable->getMaxOrderNo($parent_id,$type)."','".time()."','".time()."'
				,'{$type}','".addslashes(Input::post('image'))."','".addslashes(Input::post('image_Icon'))."','1'";
				if($clsClassTable->insertOne($f,$v)){
					echo '_SUCCESS'; die();	
				}else{
					echo '_ERROR'; die();
				}
			}
		} else{
			$set ="title='".addslashes($title)."'
			,slug='".addslashes($slug)."'
			,intro='".addslashes($intro)."'
			,parent_id='{$parent_id}'
			,upd_date='".time()."'
			,user_id_update='{$user_id}'";
			$image = Input::post('image');
			if($image != '' && $image !='0'){
				$set .= ",image='".addslashes($image)."'";
			}
			$image_Icon = Input::post('image_Icon');
			if($image_Icon != '' && $image_Icon !='0'){
				$set .= ",image_Icon='".addslashes($image_Icon)."'";
			}
			if($clsClassTable->updateOne($cat_id,$set)){
				echo '_UPDATE_SUCCESS'; die();	
			}else{
				echo '_ERROR'; die();
			}
		}
	}
	else if($tp=='D'){
		$clsClassTable->doDelete($cat_id);
		echo($cat_id); die();
	}
}
function default_moveCategoryUpDown(){
	$clsCategory = new Category();
	#
	$type = Input::post('type','_NEWS');
	$direct = Input::post('direct','up');
	$pvalTable = (int) Input::post('pvalTable',0);
	$one=$clsCategory->getOne($pvalTable,"parent_id,order_no");
	$parent_id = $one['parent_id'];
	$order_no = $one['order_no'];
	#
	$cond = "is_trash=0 and parent_id='{$parent_id}' and _type='{$type}'";
	if($direct=='up'){
		$lst = $clsCategory->getAll("{$cond} and order_no<'{$order_no}' order by order_no desc limit 0,1");
		$clsCategory->updateOne($pvalTable,"order_no='".$lst[0]['order_no']."'");
		$clsCategory->updateOne($lst[0][$clsCategory->pkey],"order_no='".$order_no."'");
	}
	else if($direct=='down'){
		$lst = $clsCategory->getAll("{$cond} and order_no>'{$order_no}' order by order_no asc limit 0,1");
		$clsCategory->updateOne($pvalTable,"order_no='".$lst[0]['order_no']."'");
		$clsCategory->updateOne($lst[0][$clsCategory->pkey],"order_no='".$order_no."'");
	}
	#
	echo(1);die();
}
function default_moveCategoryTopBottom(){
	$clsCategory = new Category();
	#
	$type = Input::post('type','_NEWS');
	$direct = Input::post('direct','up');
	$pvalTable = (int) Input::post('pvalTable',0);
	$one=$clsCategory->getOne($pvalTable,"parent_id,order_no");
	$parent_id = $one['parent_id'];
	$order_no = $one['order_no'];
	#
	$cond = "is_trash=0 and parent_id='{$parent_id}' and _type='{$type}'";
	if($direct=='up'){
		$lst = $clsCategory->getAll("{$cond} and order_no<'{$order_no}' order by order_no desc");
		$clsCategory->updateOne($pvalTable,"order_no='".$lst[count($lst)-1]['order_no']."'");
		unset($lst);
		$lst = $clsCategory->getAll("{$cond} and cat_id<>'{$pvalTable}' and order_no <'{$order_no}' order by order_no asc");
		if(!empty($lst)){
			for($i=0;$i<count($lst);$i++) {
				$clsCategory->updateOne($lst[$i][$clsCategory->pkey],"order_no='".($lst[$i]['order_no']+1)."'");	
			}
		}
	} else if($direct=='down'){
		$lst = $clsCategory->getAll("{$cond} and order_no>$order_no order by order_no asc");
		$clsCategory->updateOne($pvalTable,"order_no='".$lst[count($lst)-1]['order_no']."'");
		unset($lst);
		$lst = $clsCategory->getAll("{$cond} and cat_id<>'$pvalTable' and order_no>$order_no order by order_no desc");
		if(!empty($lst)){
			for($i=0;$i<count($lst);$i++) {
				$clsCategory->updateOne($lst[$i][$clsCategory->pkey],"order_no='".($lst[$i]['order_no']-1)."'");	
			}
		}
	}
	echo(1); die();
}
function default_ajmakeSelectBoxOption(){
	$clsCategory = new Category();
	#
	$parent_id = $_POST['parent_id'];
	$html = $clsCategory->makeSelectboxOption($parent_id,'',0);
	echo $html; die();
}
?>