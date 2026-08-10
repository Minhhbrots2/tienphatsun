<?
/** Image Gallery 
*	Modify by Thiembv	
*/
function default_countImg(){
	$clsImage=new Image();
	$for_id=$_POST['for_id'];
	$type=$_POST['type'];
	$allItem=$clsImage->getAll("is_trash=0 and type='$type' and for_id='$for_id'");
	$count= !empty($allItem)?count($allItem):'0'; 
	
	echo $count; die();
}
function default_removeImg(){
	$clsImage=new Image();
	
	$image_id=$_POST['image_id'];
	if($image_id!=''){
		$one=$clsImage->getOne($image_id);
		$image_path=$_SERVER['DOCUMENT_ROOT'].$one['image'];
		@chmod($image_path,0666);
		@unlink($image_path);
		$clsImage->deleteOne($image_id);
	}
	die();
}
function default_saveTitleImg(){
	$clsImage=new Image();
	
	$image_id=$_POST['image_id'];
	$title=addslashes($_POST['title']);
	
	if($title!='' && $image_id!=''){
		$clsImage->updateOne($image_id,"title='$title'");
	}
	echo $image_id; die();
}
function default_uploadImg(){
	$clsImage=new Image();
	if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
		$up = '';
		if(is_uploaded_file($_FILES['fileimg']['tmp_name'])){
			$clsUploadFile = new UploadFile();
			$up = $clsUploadFile->uploadItem($_FILES["fileimg"],"/ajax","jpg,gif,png");
			if($up!=''&&$up!='0'){
				$clsImage->insertOne('image,for_id,type',"'".addslashes($up)."','".$_POST['for_id']."','".$_POST['type']."'");
			}
		}
	}
	echo 'Success'; die();
}
function default_loadGallery(){
	$clsImage=new Image();
	
	$for_id=$_POST['for_id'];
	$type=$_POST['type'];
	$allItem=$clsImage->getAll("is_trash=0 and type='$type' and for_id='$for_id' order by image_id desc");
	if(!empty($allItem)){
		
		$html='<ul id="imagelist">';
		foreach($allItem as $item){
			$html.='<li>';
			$html.='<div class="oneimage">';
			$html.='<div class="menubar">
                    	<a href="javascript:void();" rel="'.$item[$clsImage->pkey].'" class="fl save savePhoto">Lưu lại</a>
                        <a href="javascript:void();" rel="'.$item[$clsImage->pkey].'" class="fr cancel removePhoto">Xóa</a>
                    </div>';
			$html.='<a class="photo" href="javascript:void();">
                    	<img src="'.$ftp_abs_path_image.$item['image'].'" width="156" height="100" />
                    </a>';
			$html.='<input class="isoTxt" type="text" value="'.$item['title'].'" placeholder="Chưa có tiêu đề ảnh" id="imagetitle-'.$item[$clsImage->pkey].'">';
			$html.'</div>';
			$html.='</li>';
		}
		$html.='</ul>';
	}else{
		$html='';
	}
	echo($html); die();
}
//
function getTablePrice($desk_id){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod , $_LANG_ID, $act, $menu_current, $current_page, $core, $clsModule;
	$clsDesk = new Desk();
	$clsDeskPriceRow = new DeskPriceRow();
	$clsDeskPriceCol = new DeskPriceCol();
	$clsDeskPriceVal = new DeskPriceVal();
	#
	$oneDesk = $clsDesk->getOne($desk_id);
	$allRow = $clsDeskPriceRow->getAll("desk_id='$desk_id' order by desk_price_row_id asc");
	$allCol = $clsDeskPriceCol->getAll("desk_id='$desk_id' order by desk_price_col_id asc");
	#
	$html = '<tr>
            <td rowspan="2"><strong>Price Option</strong></td>
            <td align="center" style="text-align:center !important" colspan="'.count($allCol).'"><div id="label_table_price_title"><strong>'.$oneDesk['table_price_title'].'</strong>&nbsp;<a href="#" id="edit_table_price_title">[Sửa]</a></div><div id="holder_table_price_title" style="display:none;"><input type="text" id="txt_table_price_title" style="width:400px" value="'.$oneDesk['table_price_title'].'" />&nbsp;<a href="#" id="save_table_price_title">[Lưu]</a>&nbsp;<a href="#" id="cancel_table_price_title">[Hủy]</a></div>&nbsp;</td>
            </tr>
            <tr>';
	for($k=0;$k<count($allCol);$k++){		
		$html .='<td><div id="label_desk_price_col_id-'.$allCol[$k]['desk_price_col_id'].'"><strong>'.$allCol[$k]['title'].'</strong><br><a class="editCol" href="#" id="colId-'.$allCol[$k]['desk_price_col_id'].'">[Sửa]</a>&nbsp;<a class="delCol" href="#" id="delId-'.$allCol[$k]['desk_price_col_id'].'">[Xóa]</a>&nbsp;</div><div id="editholder_desk_price_col_id-'.$allCol[$k]['desk_price_col_id'].'" style="display:none;"><input id="txtCol-'.$allCol[$k]['desk_price_col_id'].'" type="text" value="'.$allCol[$k]['title'].'" style="width:80px" />&nbsp;<a href="#" id="save_desk_price_col_id-'.$allCol[$k]['desk_price_col_id'].'" class="saveCol">[Lưu]</a>&nbsp;<a href="#" id="cancel_desk_price_col_id-'.$allCol[$k]['desk_price_col_id'].'" class="cancelCol">[Hủy]</a></div></td>';
	}
	$html .= '</tr>';
	#
	for($i=0;$i<count($allRow);$i++){
		$html .= '<tr>
            <td><div id="label_desk_price_row_id-'.$allRow[$i]['desk_price_row_id'].'"><strong>'.$allRow[$i]['title'].'</strong><br><a class="editRow" href="#" id="rowId-'.$allRow[$i]['desk_price_row_id'].'">[Sửa]</a>&nbsp;<a class="delRow" href="#" id="delId-'.$allRow[$i]['desk_price_row_id'].'">[Xóa]</a>&nbsp;</div><div id="editholder_desk_price_row_id-'.$allRow[$i]['desk_price_row_id'].'" style="display:none;"><input id="txtRow-'.$allRow[$i]['desk_price_row_id'].'" value="'.$allRow[$i]['title'].'" type="text" style="width:80px" />&nbsp;<a href="#" id="save_desk_price_row_id-'.$allRow[$i]['desk_price_row_id'].'" class="saveRow">[Lưu]</a>&nbsp;<a href="#" id="cancel_desk_price_row_id-'.$allRow[$i]['desk_price_row_id'].'" class="cancelRow">[Hủy]</a></div></td>';
		for($k=0;$k<count($allCol);$k++){	
			$html .= '<td>$<input class="priceVal" type="text" style="width:40px" id="priceVal-'.$allRow[$i]['desk_price_row_id'].'-'.$allCol[$k]['desk_price_col_id'].'" value="'.$clsDeskPriceVal->getVal($allRow[$i]['desk_price_row_id'],$allCol[$k]['desk_price_col_id']).'" /></td>';
		}
		$html .= '</tr>';
	}
	#		
	return $html;
}
function resetTablePrice($desk_id){
	global $dbconn, $assign_list, $_CONFIG,  $_SITE_ROOT, $mod , $_LANG_ID, $act, $menu_current, $current_page, $core, $clsModule;
	$clsDesk = new Desk();
	$clsDesk->updateOne($desk_id,"table_price_title='Price per person in USD in the from 1st Oct to 31st Sept'");
	$clsDeskPriceRow = new DeskPriceRow();
	$clsDeskPriceCol = new DeskPriceCol();
	$clsDeskPriceVal = new DeskPriceVal();
	#
	$sql = "DELETE FROM default_desk_price_row WHERE desk_id='$desk_id'";
	$dbconn->Execute($sql);
	$sql = "DELETE FROM default_desk_price_col WHERE desk_id='$desk_id'";
	$dbconn->Execute($sql);
	$sql = "DELETE FROM default_desk_price_val WHERE desk_id='$desk_id'";
	$dbconn->Execute($sql);
	#
	$listRow[] = 'Standard Option 2*';
	$listRow[] = 'Superior Otion 3*';
	$listRow[] = 'Deluxe Option 4*';
	$listRow[] = 'Luxury Option 5*';
	
	$listCol[] = '2p';
	$listCol[] = '3-4';
	$listCol[] = '5-6';
	$listCol[] = '7-8';
	$listCol[] = '9-12p';
	$listCol[] = 'SS';
	#
	for($i=0;$i<count($listRow);$i++){
		$clsDeskPriceRow->insertOne("desk_id,title","'$desk_id','".addslashes($listRow[$i])."'");
	}
	for($i=0;$i<count($listCol);$i++){
		$clsDeskPriceCol->insertOne("desk_id,title","'$desk_id','".addslashes($listCol[$i])."'");
	}
	#
	$allRow = $clsDeskPriceRow->getAll("desk_id='$desk_id' order by desk_price_row_id asc");
	$allCol = $clsDeskPriceCol->getAll("desk_id='$desk_id' order by desk_price_col_id asc");
	for($i=0;$i<count($allRow);$i++){
		for($k=0;$k<count($allCol);$k++){
			$desk_price_row_id = $allRow[$i]['desk_price_row_id'];
			$desk_price_col_id = $allCol[$k]['desk_price_col_id'];
			$clsDeskPriceVal->insertOne("desk_id,desk_price_row_id,desk_price_col_id,price","'$desk_id','$desk_price_row_id','$desk_price_col_id','0'");
		}
	}
	
}
#=====================================Desk---------------------------------------------
function default_loadTablePrice(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod , $_LANG_ID, $act, $menu_current, $current_page, $core, $clsModule;
	#
	$desk_id = $_POST['desk_id'];
	$html = getTablePrice($desk_id);
	echo($html);
	die();
}
function default_resetTablePrice(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod , $_LANG_ID, $act, $menu_current, $current_page, $core, $clsModule;
	#
	$desk_id = $_POST['desk_id'];
	$clsDesk = new Desk();
	#
	resetTablePrice($desk_id);
	$html = getTablePrice($desk_id);
	echo($html);
	die();
}
function default_saveOneTablePrice(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod , $_LANG_ID, $act, $menu_current, $current_page, $core, $clsModule;
	#
	$clsDeskPriceRow = new DeskPriceRow();
	$clsDeskPriceCol = new DeskPriceCol();
	$clsDeskPriceVal = new DeskPriceVal();
	#
	$desk_id = $_POST['desk_id'];
	$desk_price_row_id = $_POST['desk_price_row_id'];
	$desk_price_col_id = $_POST['desk_price_col_id'];
	$price = $_POST['price'];
	#
	$all = $clsDeskPriceVal->getAll("desk_id='$desk_id' and desk_price_row_id='$desk_price_row_id' and desk_price_col_id='$desk_price_col_id'");
	
	$clsDeskPriceVal->updateOne($all[0]['desk_price_val_id'],"price='".intval($price)."'");
	#
	die();
}
function default_save_table_price_title(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod , $_LANG_ID, $act, $menu_current, $current_page, $core, $clsModule;
	#
	$clsDesk = new Desk();
	$desk_id = $_POST['desk_id'];
	$table_price_title = $_POST['table_price_title'];
	#
	$clsDesk->updateOne($desk_id,"table_price_title='".addslashes($table_price_title)."'");
	#
	die();
}
function default_save_desk_price_row_id(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod , $_LANG_ID, $act, $menu_current, $current_page, $core, $clsModule;
	#
	$clsDeskPriceRow = new DeskPriceRow();
	$desk_id = $_POST['desk_id'];
	$desk_price_row_id = $_POST['desk_price_row_id'];
	$title = $_POST['title'];
	#
	$clsDeskPriceRow->updateOne($desk_price_row_id,"title='".addslashes($title)."'");
	#
	die();
}
function default_save_desk_price_col_id(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod , $_LANG_ID, $act, $menu_current, $current_page, $core, $clsModule;
	#
	$clsDeskPriceCol = new DeskPriceCol();
	$desk_id = $_POST['desk_id'];
	$desk_price_col_id = $_POST['desk_price_col_id'];
	$title = $_POST['title'];
	#
	$clsDeskPriceCol->updateOne($desk_price_col_id,"title='".addslashes($title)."'");
	#
	die();
}
function default_new_desk_price_col_id(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod , $_LANG_ID, $act, $menu_current, $current_page, $core, $clsModule;
	#
	$clsDeskPriceRow = new DeskPriceRow();
	$clsDeskPriceCol = new DeskPriceCol();
	$clsDeskPriceVal = new DeskPriceVal();
	#
	$desk_id = $_POST['desk_id'];
	$title = $_POST['title'];
	#
	$desk_price_col_id = $clsDeskPriceCol->getMaxId();
	$clsDeskPriceCol->insertOne("desk_price_col_id,desk_id,title","'$desk_price_col_id','$desk_id','".addslashes($title)."'");
	#
	$allRow = $clsDeskPriceRow->getAll("desk_id='$desk_id' order by desk_price_row_id asc");
	for($i=0;$i<count($allRow);$i++){
		$desk_price_row_id = $allRow[$i]['desk_price_row_id'];
		$clsDeskPriceVal->insertOne("desk_id,desk_price_row_id,desk_price_col_id,price","'$desk_id','$desk_price_row_id','$desk_price_col_id','0'");
	}
	# 
	die();
}
function default_new_desk_price_row_id(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod , $_LANG_ID, $act, $menu_current, $current_page, $core, $clsModule;
	#
	$clsDeskPriceRow = new DeskPriceRow();
	$clsDeskPriceCol = new DeskPriceCol();
	$clsDeskPriceVal = new DeskPriceVal();
	#
	$desk_id = $_POST['desk_id'];
	$title = $_POST['title'];
	#
	$desk_price_row_id = $clsDeskPriceRow->getMaxId();
	$clsDeskPriceRow->insertOne("desk_price_row_id,desk_id,title","'$desk_price_row_id','$desk_id','".addslashes($title)."'");
	#
	$allCol = $clsDeskPriceCol->getAll("desk_id='$desk_id' order by desk_price_col_id asc");
	for($i=0;$i<count($allCol);$i++){
		$desk_price_col_id = $allCol[$i]['desk_price_col_id'];
		$clsDeskPriceVal->insertOne("desk_id,desk_price_row_id,desk_price_col_id,price","'$desk_id','$desk_price_row_id','$desk_price_col_id','0'");
	}
	#
	die();
}
function default_del_desk_price_row_id(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod , $_LANG_ID, $act, $menu_current, $current_page, $core, $clsModule;
	#
	$clsDeskPriceRow = new DeskPriceRow();
	$clsDeskPriceCol = new DeskPriceCol();
	$clsDeskPriceVal = new DeskPriceVal();
	#
	$desk_id = $_POST['desk_id'];
	$desk_price_row_id = $_POST['desk_price_row_id'];
	#
	$allCol = $clsDeskPriceCol->getAll("desk_id='$desk_id' order by desk_price_col_id asc");
	for($i=0;$i<count($allCol);$i++){
		$desk_price_col_id = $allCol[$i]['desk_price_col_id'];
		$desk_price_val_id= $clsDeskPriceVal->getId($desk_price_row_id,$desk_price_col_id);
		$clsDeskPriceVal->deleteOne($desk_price_val_id);
	}
	$clsDeskPriceRow->deleteOne($desk_price_row_id);
	#
	die();
}
function default_del_desk_price_col_id(){
	global $assign_list, $_CONFIG,  $_SITE_ROOT, $mod , $_LANG_ID, $act, $menu_current, $current_page, $core, $clsModule;
	#
	$clsDeskPriceRow = new DeskPriceRow();
	$clsDeskPriceCol = new DeskPriceCol();
	$clsDeskPriceVal = new DeskPriceVal();
	#
	$desk_id = $_POST['desk_id'];
	$desk_price_col_id = $_POST['desk_price_col_id'];
	#
	$allRow = $clsDeskPriceRow->getAll("desk_id='$desk_id' order by desk_price_row_id asc");
	for($i=0;$i<count($allRow);$i++){
		$desk_price_row_id = $allCol[$i]['desk_price_row_id'];
		$desk_price_val_id= $clsDeskPriceVal->getId($desk_price_row_id,$desk_price_col_id);
		$clsDeskPriceVal->deleteOne($desk_price_val_id);
	}
	$clsDeskPriceCol->deleteOne($desk_price_col_id);
	#
	die();
}
function default_upload(){
	$up='';
	if(is_uploaded_file($_FILES['image']['tmp_name'])){
		$clsUploadFile = new UploadFile();
		$up = $clsUploadFile->uploadItem($_FILES["image"],"/images_upload","jpg,gif,png");
	}
	echo $up; die();
}
function default_cropimage(){
	$path_upload= $_SERVER['DOCUMENT_ROOT'].'/uploads//images_upload';
	$file_name=$_POST['url_img'];
	$type=pathinfo($file_name);
	$name=str_replace('-crop', '', $type['filename']);
	$newname=$name."-crop.jpg";
	#
	$ts_width=$_POST['t_width'];
	$ts_height=$_POST['t_height'];
	#
	header('Content-type: image/jpeg');

	list($width_orig, $height_orig) = getimagesize($file_name);
	$ratio_orig = $width_orig/500;
	#
	$width=$ts_width*$ratio_orig;
	$height=$ts_height*$ratio_orig;
	$src_x =$_POST['x_axis']*$ratio_orig;
	$src_y=$_POST['y_axis']*$ratio_orig;
	#
	$t_width = $width;
	$t_height = $height;
	$ratio = ($t_width/$width);
	$nw = $width * $ratio;
	$nh = $height * $ratio;
	#
	$image = imagecreatefromjpeg($file_name);
	$image_p = @imagecreatetruecolor($nw, $nh);
	imagecopyresampled($image_p, $image, 0, 0, $src_x, $src_y, $nw, $nh, $width, $height);
	imagejpeg($image_p, $path_upload.'/'.$newname, 100);
	echo($newname);die();
}
?>