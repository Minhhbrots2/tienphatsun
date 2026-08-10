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
class Furniture extends dbBasic{
	function __construct(){
		$this->pkey = "furniture_id";
		$this->tbl = DB_PREFIX."furniture";
	}	
	function getTitle($furniture_id,$one=null){
		if(!isset($one['title'])){
			$one = $this->getOne($furniture_id,"title");
		}
		return $one['title'];
	}	
	function doDelete($furniture_id){
		// Delete
		$this->deleteOne($furniture_id);
		return 1;
	}
	
    function getSelectByCat($cat_id,$selected = '',$is_multiple=false,$title = '') {
		global $clsISO;
		$lstFurniture = $this->getAll("is_trash='0' AND is_online='1' AND cat_id like'%|{$cat_id}|%'");
        global $core;
        #
		$html = "";
		if($title != ""){
			$html = '<option value="">-- '.$title.' --</option>';
		}
        
        foreach ($lstFurniture as $key => $val) {
			if($is_multiple){
				$selected_index = $clsISO->checkInArray($selected,$val[$this->pkey])?' selected':'';
			}else{
				$selected_index = ($selected == $val[$this->pkey]) ? 'selected' : '';
			}
            $html .= '<option value="'.$val[$this->pkey].'" '.$selected_index.'>'.$val['title'].'</option>';
        }
        return $html;
    }
	function uploadImageGoogleDriver($files=[],$furniture_id){
		$results = array();
		if(!empty($files['name']) && $furniture_id >0){ 
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
						$upload_file = $clsUploadFile->uploadItem($image,"/BROKER",EXTENSION_FILE_UPLOAD);
						$file_name = $image['name'];
						$file_size = $image['size'];
						// Upload file to google drive
						$clsGoogleUpload = new GoogleUpload(GOOGLE_DRIVE_FOLDER_TTDG_ID, true);
						$folder_id = $clsGoogleUpload->create_folder($furniture_id);
						// $clsISO->print_pre($folder_id); die();
						$createdFile = $clsGoogleUpload->upload($file_name,$mimeType,ROOTPATH.$upload_file,$folder_id);
						$upload_file = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';							
						if(!empty($upload_file)){
							$results[] = "https://drive.google.com/thumbnail?id=".$createdFile->getId()."&sz=w1000";
						}
						@unlink(ROOTPATH . $upload_file);
						// Update to DB
					}
				}
			}			
		}
		return $results; 
	}
	function uploadImage($files=[],$furniture_id){
		$upload_file = [];
		if(!empty($files['name']) && $furniture_id >0){ 
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
						$upload_file[] = $clsUploadFile->uploadItem($image,"/FURNITURE",EXTENSION_FILE_UPLOAD);
					}
				}
			}			
		}
		return $upload_file; 
	}
}