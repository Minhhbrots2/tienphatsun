<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| #################################################################### ||
\*======================================================================*/
class FurnitureOptions extends dbBasic{
	function __construct(){
		$this->pkey = "furniture_option_id";
		$this->tbl = DB_PREFIX."furniture_options";
	}	
	function doDelete($furniture_option_id){
		// Delete
		$this->deleteOne($furniture_option_id);
		return 1;
	}
	function uploadImage($files=[],$pvalTable){
		$upload_file = [];
		if(!empty($files['name']) && $pvalTable >0){ 
			$ii = 0; //Init
			for($i = 0; $i<count($files); $i++){
				$clsUploadFile = new UploadFile();
				$image = array();
				$image["name"] = $files['name'][$i];
				$image["type"] = $files['type'][$i];
				$image["tmp_name"] = $files['tmp_name'][$i];
				$image["error"] = $files['error'][$i];
				$image["size"] = $files['size'][$i];
				if(!empty($image["name"])){
					if(@is_uploaded_file($image['tmp_name'])){
						$clsUploadFile = new UploadFile();
						$upload_file[] = $clsUploadFile->uploadItem($image,"/FURNITURE_OPTIONS",EXTENSION_FILE_UPLOAD);
					}
				}
			}			
		}
		return $upload_file; 
	}
	function getEmbedVideo($url,$w="100%",$h='250'){
		global $core,$dbconn,$clsISO,$deviceType;
		if(preg_match('/^(http(s)?:\/\/)?((w){3}.)?youtu(be|.be)?(\.com)?\/.+$/',$url)){
			preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/",$url,$matches);
			return '<iframe class="radius-3 mb-2 overflow-hidden" width="'.$w.'" height="'.$h.'" src="https://www.youtube.com/embed/'.$matches[1].'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
		}else{
			return '<iframe width="'.$w.'" height="'.$h.'" allowfullscreen frameborder="0" src="'.$url.'&is_stereo=false"></iframe>';
		}
		return "";
	}
}