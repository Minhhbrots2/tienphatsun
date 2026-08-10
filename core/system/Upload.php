<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*=========================================================================*\
|| ######################################################################## ||
|| # MaxxCMS Module Private By Technical Group(buivanthiem.it@gmail.com)  # ||
|| # ----------------------------------------------------------------     # ||
|| # All PHP code in this file is ©2007-2013 by Technical Group           # ||
|| # This file may not be redistributed in whole or significant part.     # ||
|| # ---------------- NOT FREE SOFTWARE ----------------                  # ||
|| ######################################################################## ||
\*=========================================================================*/
class UploadFile{
	function __construct(){}
	function uploadItem($file, $dirname, $allowExt, $configs = array()){
		global $core, $dbconn, $clsISO, $clsConfiguration;
		if($this->checkValidFile($file, "", $allowExt)){
			if(ftp_upload==1){
				$ftp_host = ftp_host_info;
				$ftp_usr = ftp_usr_info;
				$ftp_pwd = ftp_pwd_info;
				$abs_path = ftp_abs_path_info;
				
				$conn_id = ftp_connect($ftp_host) or die ("Cannot connect to host");
				ftp_login($conn_id, $ftp_usr, $ftp_pwd) or die("Cannot login");
				if(ftp_chdir($conn_id,$dirname)) {
					//return 2;//folder exist
				}else
					ftp_mkdir($conn_id, $dirname);

				$time = date('Y-m-d.h.i.s');	
				$name = $dirname.'/'.$time.'-'.$this->replaceSpace($file['name']);
				$upload = ftp_put($conn_id, $name, $file['tmp_name'], FTP_BINARY);
				ftp_close($conn_id);
				return $abs_path.$name;
			}else{
				require_once(DIR_INCLUDES.'/verot/vendor/autoload.php');
				$dirname = ftp_abs_path_info.$dirname;
				// we create an instance of the class, giving as argument the PHP object
				// corresponding to the file field from the form
				// All the uploads are accessible from the PHP object $_FILES
				$handle = new \Verot\Upload\Upload($file);
				//file_name_body_pre prepends to the name body (default: null)
				$handle->file_name_body_pre = sprintf('FutureHomes_%s',time());
				// Sets behaviour if file already exists
				$handle->file_overwrite = true;
				// Automatically creates destination directory if missing
				$handle->dir_auto_create = true;
				// Automatically attempts to chmod the destination directory if not writeable
				$handle->dir_auto_chmod = true;
				// then we check if the file has been uploaded properly
				// in its *temporary* location in the server (often, it is /tmp
				if(!empty($configs)){
					foreach($configs as $key => $val){
						if($key == 'image_watermark_text'){
							 $make_watermark = function($width, $params) {
								$height = $params['height'] ?: 30;
								$text   = $params['image_text'] ?: 'Future Homes';
								$size   = $params['image_text_size'] ?: 14;
								$font   = $params['image_text_font'] ?: (ABSPATH . '/ARIAL.TTF');
								// Tạo ảnh watermark với chiều rộng = ảnh gốc, chiều cao theo config
								$img = imagecreatetruecolor($width, $height);
								// Tắt chế độ blend để giữ alpha đúng
								imagealphablending($img, false);
								// Cho phép lưu kênh alpha (trong suốt)
								imagesavealpha($img, true);
								// Tạo màu nền đen với opacity ~50% (0 = đục, 127 = trong suốt)
								$bg = imagecolorallocatealpha($img, 0, 0, 0, 63);
								// Fill toàn bộ background bằng màu đen mờ
								imagefill($img, 0, 0, $bg);
								// Màu chữ trắng
								$white = imagecolorallocate($img, 255, 255, 255);
								
								// Lấy bounding box của text
								$bbox = imagettfbbox($size, 0, $font, $text);
								// Chiều cao thực của text
								$text_height = abs($bbox[5] - $bbox[1]);
								// Tính Y để text nằm giữa theo chiều dọc
								$y = ($height / 2) + ($text_height / 2);
								// Vẽ text lên ảnh
								imagettftext(
									$img,
									$size, 						// kích thước font
									0,                          // góc xoay (0 = ngang)
									10,                         // vị trí X (cách trái 10px)
									$y,                         // vị trí Y (tính từ baseline, không phải top)
									$white,                     // màu chữ
									$font,                      // font TTF
									$text      					// nội dung text
								);
								// Đường dẫn lưu file watermark tạm
								$path = ABSPATH . ftp_abs_path_info . '/wm_' . uniqid() . '.png';
								// Xuất ảnh ra file PNG (giữ được transparency)
								imagepng($img, $path);
								// Giải phóng bộ nhớ
								imagedestroy($img);
								// Trả về đường dẫn file để dùng cho $handle->image_watermark
								return $path;
							};
							$wm = $make_watermark($handle->image_src_x, $val);
							$handle->image_watermark = $wm;
						} else {
							$handle->$key = $val;
						}
					}
				}
				// $clsISO->print_pre($handle); die();
				if ($handle->uploaded) {
					// yes, the file is on the server
					// now, we start the upload 'process'. That is, to copy the uploaded file
					// from its temporary location to the wanted location
					// It could be something like $handle->Process('/home/www/my_uploads/');
					$handle->Process(ROOTPATH.$dirname);
					// we check if everything went OK
					if($handle->processed){
						return $dirname.'/'.$handle->file_dst_name;
					}else{
						return $handle->error();
					}
					$handle->Clean();
				}
			}
		}else{
			return 0;
		}	
	}
	function makeFolder($dirname, $conn_id=null){
		if(_upload_ftp){
			$lst = explode('/',$dirname);
			$str = '/'.$lst[0];
			for($i=1;$i<count($lst);$i++){
				$str = $str.'/'.$lst[$i];
				if(!ftp_chdir($conn_id,$str)){
					ftp_mkdir($conn_id, $str);
				}
			}
		}else{
			rmkdir(ROOTPATH.$dirname);
		}
		return 1;
	}
	function base642imagejpeg($data, $filename, $dirname){
		global $dbconn, $core, $_LANG_ID;
		$abs_path = ftp_abs_path_info;
		$dirname = $abs_path.$dirname;
		$dirname = str_replace('//','/',$dirname);
		if(!is_dir(ROOTPATH.$dirname)){
			@rmkdir(ROOTPATH.$dirname, 0777);
		}
		$time = date('Y-m-d-h-i-s');				
		$filename = $dirname.'/'.$time.'-'.$this->replaceSpace($filename);
		//$tmp = @explode(";base64,", $data);
		$data = @base64_decode($data);
		//header('Content-Type: image/jpeg');
		@write_file(ROOTPATH.$filename, $data);
		// Return
		return $filename;
	}
	function uploadImageFromUrl($file,$slug){
		$reg_date = time();
		#
		$host = ftp_host_info;
		$usr = ftp_usr_info;
		$pwd = ftp_pwd_info;
		$abs_path = ftp_abs_path_info;
		/*Get File Extension*/
		$path_parts = pathinfo($file);
		$ext = $path_parts['extension'];
		if($ext!='jpg'&&$ext!='png'&&$ext!='gif'){
			$ext = 'jpg';
		}
		/*File Name*/
		$day = date('d',$reg_date);
		$month = date('m',$reg_date);
		$year = date('Y',$reg_date);
		$dirname = '/content/'.$year.'/'.$month.'/'.$day.'/'.$slug;
		#
		$nMn = md5($file);
		$nMn = substr($nMn, 0, 4);
		$name = '/'.$dirname.'/'.$slug.'-'.$nMn.'.'.$ext;
		if(_upload_ftp){
			/*Connect FTP*/
			$conn_id = ftp_connect($host) or die ("Cannot connect to host");
			ftp_login($conn_id, $usr, $pwd) or die("Cannot login");
			
			$res = ftp_size($conn_id, $name);
			//print_r($abs_path.$name);die();
			if($res != -1){
				//return 'available';
				return $abs_path.$name;  
			}
			else{
				$this->makeFolder($dirname, $conn_id);
				list($width_orig, $height_orig) = getimagesize($file);
				#
				$temp_file = ftp_temp_file_info;
				if($ext == "jpg"){
					$image_p = imagecreatetruecolor($width_orig, $height_orig);
					$image = imagecreatefromjpeg($file);
					imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width_orig, $height_orig, $width_orig, $height_orig);
					$temp_file .= $new_name.'.'.$ext;
					imagejpeg($image_p, $temp_file);
								
				}elseif($ext == "png"){
					$image_p = imagecreatetruecolor($width_orig, $height_orig);
					$image = imagecreatefrompng($file);
					imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width_orig, $height_orig, $width_orig, $height_orig);
					$temp_file .= $new_name.'.'.$ext;
					imagepng($image_p, $temp_file);
				}elseif($ext == "gif"){			
					$image_p = imagecreatetruecolor($width_orig, $height_orig);
					$image = imagecreatefromgif($file);
					imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width_orig, $height_orig, $width_orig, $height_orig);
					$temp_file .= $new_name.'.'.$ext;
					imagegif($image_p, $temp_file);
				} else{
					return '';
				}
				
				//===================================================================
				$upload = ftp_put($conn_id, $name, $temp_file, FTP_BINARY);
				//===================================================================			
				imagedestroy($image_p);
				unlink($temp_file);
			}
			ftp_close($conn_id);
		}else{
			$clsDownload = new Download();
			$a = $this->makeFolder($abs_path.$dirname);
			$a = $clsDownload->cURLdownload($file, ROOTPATH.$abs_path.$name);
		}
		return $abs_path.$name;	
	}
	function uploadImageResize($file,$dirname,$allowExt,$w,$h,$new_name){
		$host = ftp_host_info;
		$usr = ftp_usr_info;
		$pwd = ftp_pwd_info;
		$abs_path = ftp_abs_path_info;
		
		if($this->checkValidFile($file, "", $allowExt)){
			$conn_id = ftp_connect($host) or die ("Cannot connect to host");
			ftp_login($conn_id, $usr, $pwd) or die("Cannot login");
			
			if(ftp_chdir($conn_id,$dirname)) {
				//return 2;//folder exist
			}
			else
				ftp_mkdir($conn_id, $dirname);
			$time = date('Y-m-d.h.i.s');
			//===================================================================
			list($width_orig, $height_orig) = getimagesize($file['tmp_name']);
			
			$file_type = $file['type'];
			$temp_file = ftp_temp_file_info;
			if($file_type == "image/pjpeg" || $file_type == "image/jpeg"){
				$ext = '.jpg';
				$image_p = imagecreatetruecolor($w, $h);
				$image = imagecreatefromjpeg($file['tmp_name']);
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $w, $h, $width_orig, $height_orig);
				$temp_file .= $new_name.$ext;
				imagejpeg($image_p, $temp_file);
							
			}elseif($file_type == "image/x-png" || $file_type == "image/png"){
				$ext = '.png';
				$image_p = imagecreatetruecolor($w, $h);
				$image = imagecreatefrompng($file['tmp_name']);
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $w, $h, $width_orig, $height_orig);
				$temp_file .= $new_name.$ext;
				imagepng($image_p, $temp_file);
			}elseif($file_type == "image/gif"){
				$ext = '.gif';					
				$image_p = imagecreatetruecolor($w, $h);
				$image = imagecreatefromgif($file['tmp_name']);
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $w, $h, $width_orig, $height_orig);
				$temp_file .= $new_name.$ext;
				imagegif($image_p, $temp_file);
			}
			else{
				return '';
			}
			$name = $dirname.'/'.$w.'-'.$h.'-'.$new_name.$ext;
			//===================================================================
			$upload = ftp_put($conn_id, $name, $temp_file, FTP_BINARY);
			//===================================================================
			ftp_close($conn_id);
			imagedestroy($image_p);
			return $abs_path.$name;
		}
		return '';		
	}
	
	function uploadImageResize2($file,$dirname,$allowExt,$w,$h,$new_name){
		$host = ftp_host_info_2;
		$usr = ftp_usr_info_2;
		$pwd = ftp_pwd_info_2;
		$abs_path = ftp_abs_path_info_2;
		
		if($this->checkValidFile($file, "", $allowExt)){
			$conn_id = ftp_connect($host) or die ("Cannot connect to host");
			ftp_login($conn_id, $usr, $pwd) or die("Cannot login");
			
			if(ftp_chdir($conn_id,$dirname)) {
				//return 2;//folder exist
			}
			else
				ftp_mkdir($conn_id, $dirname);
			$time = date('Y-m-d.h.i.s');
			//===================================================================
			list($width_orig, $height_orig) = getimagesize($file['tmp_name']);
			
			$file_type = $file['type'];
			$temp_file = ftp_temp_file_info;
			if($file_type == "image/pjpeg" || $file_type == "image/jpeg"){
				$ext = '.jpg';
				$image_p = imagecreatetruecolor($w, $h);
				$image = imagecreatefromjpeg($file['tmp_name']);
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $w, $h, $width_orig, $height_orig);
				$temp_file .= $new_name.$ext;
				imagejpeg($image_p, $temp_file);
							
			}elseif($file_type == "image/x-png" || $file_type == "image/png"){
				$ext = '.png';
				$image_p = imagecreatetruecolor($w, $h);
				$image = imagecreatefrompng($file['tmp_name']);
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $w, $h, $width_orig, $height_orig);
				$temp_file .= $new_name.$ext;
				imagepng($image_p, $temp_file);
			}elseif($file_type == "image/gif"){
				$ext = '.gif';					
				$image_p = imagecreatetruecolor($w, $h);
				$image = imagecreatefromgif($file['tmp_name']);
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $w, $h, $width_orig, $height_orig);
				$temp_file .= $new_name.$ext;
				imagegif($image_p, $temp_file);
			}
			else{
				return '';
			}
			$name = $dirname.'/'.$w.'-'.$h.'-'.$new_name.$ext;
			//===================================================================
			$upload = ftp_put($conn_id, $name, $temp_file, FTP_BINARY);
			//===================================================================
			ftp_close($conn_id);
			imagedestroy($image_p);
			return $abs_path.$name;
		}
		return '';		
	}
	function replaceSpace($str) {	
		$count = 1;
		while($count)
			$str = str_replace(' ', '', $str, $count);		
		return strtolower($str);
	}
	function checkValidFile($imgfile, $max_file_size="", $allowExt=""){
		if ($max_file_size==""){
			$max_file_size = 45485760;
		}
		if($allowExt == EXTENSION_VIDEO_UPLOAD) {
			$max_file_size = 104857600;
		}
		if ($allowExt==""){
			$allowExt="jpeg, png, jpg, gif";
		}
		$file_name = $imgfile["name"];
		$file_tmp = $imgfile['tmp_name'];
		/*$arrListExpExtension = explode("\.",$file_name);
		if(is_array($arrListExpExtension)){
			foreach($arrListExpExtension as $k => $v){
				if(eregi("\.php",$v)) die();
			}
		}*/
		$extension = strtolower(substr(strrchr($file_name,"."),1));
		//check extension
		if (strpos($allowExt, $extension)===false){
			$errNo = 1;//extension is not allow
			return 0;
		}
		//check size
		$size = @filesize($file_tmp);
		if ($size>$max_file_size){
			$errNo = 2;//size is not allow
			return 0;
		}
		//else
		return 1;
	}
}
?>