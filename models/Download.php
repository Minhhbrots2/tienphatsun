<?php
class Download extends dbBasic{
	function __construct(){
		$this->pkey = "download_id";
		$this->tbl = DB_PREFIX."download";
	}
	function getTitle($pvalTable, $oDataTable = array()){
		global $core, $dbconn, $_LANG_ID;
		$field = ($_LANG_ID=='vn') ? 'title' : "title_".$_LANG_ID;
		if(!isset($oDataTable[$field])){
			$oDataTable = $this->getOne($pvalTable, $field);
		}
		return $oDataTable[$field];
	}
	function getSlug($pvalTable, $oDataTable = array()){
		global $core, $dbconn, $_LANG_ID;
		$field = ($_LANG_ID=='vn') ? 'slug' : "slug_".$_LANG_ID;
		if(!isset($oDataTable[$field])){
			$oDataTable = $this->getOne($pvalTable, $field);
		}
		return $oDataTable[$field];
	}
	function getRegDate($pvalTable, $oDataTable = array()) {
		if(!isset($oDataTable['reg_date'])){
			$oDataTable = $this->getOne($pvalTable, "reg_date");
		}
		return date('m/d/Y', $oDataTable['reg_date']);
	}
	function getImage($pvalTable, $w, $h, $oDataTable = array()){
		global $core, $dbconn, $clsISO;
		if(!isset($oDataTable['image'])){
			$oDataTable = $this->getOne($pvalTable, "image");
		}
		$image = $oDataTable['image'];
		if(!empty($image)){
			return '/files/thumb/'.$w.'/'.$h.'/'.$clsISO->parseImageURL($image);
		}
		return URL_IMAGES.'/noimage.png';
	}
	function getContent($pvalTable, $oDataTable = array()) {
        global $dbconn, $core, $_LANG_ID;
		$field = ($_LANG_ID=='vn') ? 'content' : "content_".$_LANG_ID;
		if(!isset($oDataTable[$field])){
			$oDataTable = $this->getOne($pvalTable, $field);
		}
		return html_entity_decode($oDataTable[$field]);
    }
	function truncate($string, $width, $etc = ' ..') {
        $wrapped = explode('$trun$', wordwrap($string, $width, '$trun$', false), 2);
        return $wrapped[0] . (isset($wrapped[1]) ? $etc : '');
    }
	function getFileSize($download_id, $oDataTable = array(), $type='KB'){
		global $_LANG_ID;
		if(!isset($oDataTable['attachment_file'])){
			$oDataTable = $this->getOne($download_id, "attachment_file,attachment_url");
		}
		$attachment_file = $oDataTable['attachment_file'];
		$attachment_url = $oDataTable['attachment_url'];
		if($type=='MB'){
			$filesize = number_format(filesize(ABSPATH.$attachment_file)/1024/1024);
		}else{
			$filesize = number_format(filesize(ABSPATH.$attachment_file)/1024);
		}
		return $filesize.' '.$type;
	}
	function getAttachment($download_id, $oDataTable = array()){
		global $_LANG_ID;
		if(!isset($oDataTable['attachment_file'])){
			$oDataTable = $this->getOne($download_id, "attachment_file,attachment_url");
		}
		$attachment_file = $oDataTable['attachment_file'];
		$attachment_url = $oDataTable['attachment_url'];
		if(!empty($attachment_file))
			return html_entity_decode($attachment_file);
		return html_entity_decode($attachment_url);
	}
	function getFileExtension($download_id, $oDataTable = array()){
		global $_LANG_ID;
		if(!isset($oDataTable['attachment_file'])){
			$oDataTable = $this->getOne($download_id, "attachment_file,attachment_url");
		}
		$attachment_file = $oDataTable['attachment_file'];
		$attachment_url = $oDataTable['attachment_url'];
		$attachment_file = !empty($attachment_file) 
			? $attachment_file : $attachment_url;
			
		$arr= explode(".", $attachment_file);
		return end($arr);
	}
	function getTextButton($download_id, $oDataTable = array()){
		global $core;
		$ext = $this->getFileExtension($download_id, $oDataTable);
		$size = $this->getFileSize($download_id, $oDataTable);
		return $core->get_Lang('Download') . ' ('.$ext.' - '.$size.')';
	}
	function doDelete($download_id){
		// Delete News
		$this->deleteOne($download_id);
		return 1;
	}
}
?>