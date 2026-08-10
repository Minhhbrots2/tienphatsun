<?php
/**
*  Created by   :
*  @author		: Technical Group (technical@aboutpro.com)
   @modifier    : Luong Tien Dung (tag@vietiso.com)	
*  @date		: 2009/1/18
*  @version		: 2.1.1
*/ 
class Image extends dbBasic{
	function __construct(){
		$this->pkey = "image_id";
		$this->tbl = DB_PREFIX."image";
	}
	function getMaxOrderNo($table_id, $type){
		$tmp = $this->getAll("table_id='{$table_id}' and type='{$type}' order by order_no desc limit 0,1");
		return !empty($tmp) ? (intval($tmp[0]['order_no'])+1) : 1;
	}
	function getType($table_id){
		$one=$this->getOne($table_id);
		return $one['type'];
	}
	function countImage($type,$table_id){
		$number = 0;
		$all = $this->getAll("is_trash=0 and type='$type' and table_id='$table_id'");
		$number = $all[0][$this->pkey]!=''?count($all):0;
		return $number;
	}
	function getTitle($table_id){
		$one=$this->getOne($table_id);
		if($one['title']!=''){
			return $one['title'];
		}else{
			$image= basename($one['image']); 
			$path_parts = pathinfo($image);
			return $path_parts['filename'];
		}
	}
	function getSlug($table_id){
		global $core;
		$one=$this->getOne($table_id);
		return ($one['title']!='')?$core->replaceSpace($one['title']):'photo-gallery';
	}
	function getImage($pvalTable, $w, $h, $oDataTable=array()){
		global $clsISO;
		if(!isset($oDataTable['image'])){
			$oDataTable = $this->getOne($pvalTable, "image");
		}
		$image = $oDataTable['image'];
		if(!empty($image)){
			return '/files/thumb/'.$w.'/'.$h.'/'.$clsISO->parseImageURL($image);
		}
		return URL_IMAGES.'/noimage.png';
	}
	function deleteFile($path){
		$conn = ftp_connect(ftp_host_info) or die("Could not connect");
		ftp_login($conn,ftp_usr_info,ftp_pwd_info);
		echo ftp_delete($conn,str_replace(ftp_abs_path_info,'',$path));
		ftp_close($conn);
	}
}
?>