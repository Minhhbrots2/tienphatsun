<?php

class Service extends dbBasic{

	function Service(){

		global $_LANG_ID;

		$this->pkey = "service_id";

		$this->tbl = DB_PREFIX."service";

	}

	function getTitle($pvalTable, $_args= array()){

		if(is_array($_args) && $_args['title'] != ''){

			return $_args['title'];

		}else{

			$one=$this->getOne($pvalTable);

			return $one['title'];

		}

	}

	function getSlug($pvalTable, $_args= array()){

		if(is_array($_args) && $_args['slug'] != ''){

			return $_args['slug'];

		}else{

			$one=$this->getOne($pvalTable);

			return $one['slug'];

		}

	}

	function getBySlug($slug) {

        $res = $this->getAll("is_trash=0 and slug='$slug'");

        return $res[0][$this->pkey];

    }

	function getIntro($pvalTable, $_args= array()){

		if(is_array($_args) && $_args['intro'] != ''){

			return html_entity_decode($_args['intro']);

		}else{

			$one=$this->getOne($pvalTable);

			return html_entity_decode($one['intro']);

		}

	}

	function getContent($pvalTable, $_args= array()){

		if(is_array($_args) && $_args['content'] != ''){

			return html_entity_decode($_args['content']);

		}else{

			$one=$this->getOne($pvalTable);

			return html_entity_decode($one['content']);

		}

	}

	function getStripIntro($pvalTable){

		$one = $this->getOne($pvalTable);

		if(!empty($one['intro'])) {

			return strip_tags(html_entity_decode($one['intro']));

		} else {

			return strip_tags(html_entity_decode($one['content']));

		}

	}

	function getLink($pvalTable, $type = ''){

		global $extLang, $_LANG_ID;

		return DOMAIN_URL.'/dv/'.$this->getSlug($pvalTable);

	}

	function getImage($pvalTable, $w, $h){

		global $clsISO;

		$oneTable = $this->getOne($pvalTable, "image");

		if($oneTable['image']!=''){

			$image = $oneTable['image'];

			return '/files/thumb/'.$w.'/'.$h.'/'.$clsISO->parseImageURL($image);

		}

		return URL_IMAGES.'/noimage.png';

	}

	function getImageUrl($pvalTable){

		$one = $this->getOne($pvalTable);

		return $one['image'];

	}

	function getRegDate($pvalTable) {

		global $clsISO;

		$one=$this->getOne($pvalTable);

		return $clsISO->formatDate($one['reg_date'],3);

	}

	function getUpdDate($pvalTable) {

		global $clsISO;

		$one=$this->getOne($pvalTable);

		return $clsISO->formatDate($one['upd_date'],3);

	}

	function getListByCat($cat_id, $limit = '') {

		global $core;

		#

		$cond = "is_trash=0 and is_online=1";

		if(intval($cat_id) > 0) {$cond.= " and (cat_id = '$cat_id' or list_cat_id like '%|".$cat_id."|%')";}

		$cond.= " order by order_no desc";

		if(intval($limit) > 0) {$cond.= " limit 0,".$limit;}

		$res = $this->getAll($cond, $this->pkey);

		return $res;

	}

	function doDelete($service_id){

		// Delete

		$this->deleteOne($service_id);

		return 1;

	}

}

class ServiceImage extends dbBasic{

	function ServiceImage(){

		$this->pkey = "service_image_id";

		$this->tbl = DB_PREFIX."service_image";

	}

	function getMaxOrderNo($table_id){

		$res=$this->getAll("is_trash=0 and table_id='$table_id' order by order_no desc limit 0,1");

		return intval($res[0]['order_no'])+1;

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

	function getImage($pvalTable, $w, $h){

		global $clsISO;

		$oneTable = $this->getOne($pvalTable, "image");

		if($oneTable['image']!=''){

			$image = $oneTable['image'];

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