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
class Supplier extends dbBasic{
	function __construct(){
		global $_LANG_ID;
		$this->pkey = "supplier_id";
		$this->tbl = DB_PREFIX."supplier";
	}
	function getName($supplier_id){
		global $_LANG_ID;
		$one=$this->getOne($supplier_id,'name');
		return $one['name'];
	}
	function getSlug($provider_id){
		global $_LANG_ID;
		$one=$this->getOne($provider_id, 'slug_'.$_LANG_ID);
		return $one['slug_'.$_LANG_ID];
	}
	function getUpdateTime($provider_id){
		global $_LANG_ID;
		$one = $this->getOne($provider_id,'upd_date');
		return date('d-m-Y h:i', $one['upd_date']);
	}
	function generateProviderCode(){
		$prefix = 'NCC';
		$provider_id = $this->getMaxId();
		$len = strlen($provider_id);
		if($len>=2 && $len<=3){
			return $prefix.$provider_id;
		}elseif($len>1 && $len<=2){
			return $prefix.'0'.$provider_id;
		}else{
			return $prefix.'00'.$provider_id;
		}
	}
	function getImage($pvalTable, $w, $h, $oDataTable=array()){
		global $clsISO;
		if(!isset($oDataTable['image'])){
			$oDataTable = $this->getOne($pvalTable, "image");
		}
		$image = $oDataTable['image'];
		if(!empty($image)){
			$path = '/files/thumb/'.$w.'/'.$h."/".$clsISO->parseImageURL($image);
			return str_replace('//', '/', $path);
		}
		$noimage = URL_IMAGES.'/noimage.png';
		return '/files/thumb/'.$w.'/'.$h.'/'.$clsISO->parseImageURL($noimage);
	}
	function getImageUrl($pvalTable){
		$one = $this->getOne($pvalTable,'image');
		return $one['image'];
	}
	function getOpt($selected=0){
		$html = '<option value="0">Không chọn</option>';
		$tmp = $this->getAll("is_trash=0 order by order_no ASC", "{$this->pkey},name");
		if(!empty($tmp)){
			foreach($tmp as $v){
				$id = $v[$this->pkey];
				$html .= '<option'.($selected==$id?' selected':'').' value="'.$id.'">'.$v['name'].'</option>';
			}
			unset($tmp);
		}
		return $html;
	}
}
?>
