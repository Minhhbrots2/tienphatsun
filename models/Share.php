<?php
class Share extends dbBasic {
    function __construct() {
        $this->pkey = "share_id";
        $this->tbl = DB_PREFIX . "share";
    }
    function getTitle($share_id, $oShare= array()) {
        global $_LANG_ID;
        $one = $this->getOne($share_id, "title");
		return $one['title'];
    }
    function getSlug($post_id) {
        global $_LANG_ID;
        $one = $this->getOne($post_id);
        return $one['slug'];
    }
    function getLink($share_id) {
        return sprintf("/net-dep-lao-dong/%s.html",$share_id);
    }
	function getRegDate($post_id){
		global $_LANG_ID;
		$one = $this->getOne($post_id);
		return date('F, d Y',$one['reg_date']);
	}
	function getImage($pvalTable,$w,$h){
		global $clsISO;
		#
		$oneTable = $this->getOne($pvalTable, "image");
		if($oneTable['image']!=''){
			$image = $oneTable['image'];
			return '/files/thumb/'.$w.'/'.$h.'/'.$clsISO->parseImageURL($image);
		}
		$noimage = URL_IMAGES.'/noimage.png';
		return '/files/thumb/'.$w.'/'.$h.'/'.$clsISO->parseImageURL($noimage);
	}
	function maskPhone($phone){
		if(!empty($phone)){
			$phone = strlen($phone) > 4 ? @substr($phone, -4) : $phone;
			return sprintf('*** %s', $phone);
		}
		return $phone;
	}
	function doDelete($post_id){
		// Delete
		$this->deleteOne($post_id);
		return 1;
	}
	function getImageGrid($post_id, $images){
		global $core,$dbconn,$_LANG_ID;
		$html = '';
		$list_images = @json_decode($images, true);
		if(!empty($list_images)){ $ii = 1; // Init
			$html .= '<div id="gallery-grid-'.$post_id.'"></div>
			<script type="text/javascript" >
				setTimeout(function(){
					$(\'#gallery-grid-'.$post_id.'\').imagesGrid({
						cells:4,
						images: [';
						foreach($list_images as $img){
							$html .="'".$img."'".($ii==count($list_images)?'':',');
							++$ii;
						}
						$html .= '],
						align: true
					});
				},500);
				
			</script>';
		}else{
			$html .= '<div id="gallery-grid-'.$post_id.'"></div>
			<script type="text/javascript" >
				setTimeout(function(){
					$(\'#gallery-grid-'.$post_id.'\').imagesGrid({
						cells:4,
						images: ["'.URL_IMAGES.'/no-image.png"],
						align: true
					});
				},500);
				
			</script>';
		}
		return $html;
	}
	function checkLiked($share_id, $oDataTable = array()){
		global $core,$dbconn,$_LANG_ID,$clsISO,$profile_id;
		if(!isset($oDataTable['liked_json'])){
			$oDataTable = $this->getOne($share_id, "liked_json");
		}
		$liked_json = $oDataTable['liked_json'];
		$liked_json = !empty($liked_json) 
			? json_decode(html_entity_decode($liked_json), true) : array();
		if(in_array($profile_id, $liked_json))
			return 1;
		return 0;
	}
	function checkCanEdit($share_id, $oDataTable=array()){
		if(!isset($oDataTable['reg_date'])){
			$oDataTable = $this->getOne($share_id, "reg_date");
		}
		$reg_date = $oDataTable['reg_date'];
		if($reg_date+5*60 > time())
			return 1;
		return 0;
	}
	function rmkdir($path, $mode = 0777) {
		return is_dir($path) || ( $this->rmkdir(dirname($path), $mode) && $this->_mkdir($path, $mode) );
	}
	function _mkdir($path, $mode = 0777) {
		$old = umask(0);
		$res = @mkdir($path, $mode);
		umask($old);
		return $res;
	}
	function _write_file($path, $data, $mode = 'wb'){
		if ( ! $fp = @fopen($path, $mode)){
			return FALSE;
		}
		@flock($fp, LOCK_EX);
		for ($result = $written = 0, $length = strlen($data); $written < $length; $written += $result){
			if (($result = @fwrite($fp, substr($data, $written))) === FALSE){
				break;
			}
		}
		@flock($fp, LOCK_UN);
		@fclose($fp);
		return is_int($result);
	}
	function replaceSpacei($str) {
		if(!$str) return false;
		$str = str_replace(array('%',"/","\\",'"','?','<','>',"#","^","`","'","=","!",":" ,",,","..","*","&","__","▄",',','/','-',"́","̣","̀","̉","̃",".","–","…","(",")"),array('','','','','','','','','',"","",'','','','','','','','','',' ',' ','','','','','','','','','','',''),html_entity_decode(trim($str))); 
		$unicode = array(
			'a'=>'ä|à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ|Ä|À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ',
			'd'=>'Đ|đ',
			'e'=>'è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ|È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ',
			'i'=>'ì|í|î|ị|ỉ|ĩ|Ì|Í|Ị|Ỉ|Ĩ',
			'o'=>'ö|ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ|Ö|Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ|ớ',
			'u'=>'ü|ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ|Ü|Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ',
			'y'=>'ỳ|ý|ỵ|ỷ|ỹ|Ỳ|Ý|Ỵ|Ỷ|Ỹ'	
		);
		$count = 0;
		foreach($unicode as $nonUnicode=>$uni) {
			$str = preg_replace("/($uni)/i",$nonUnicode,addslashes($str));
			$count++;
		}
		if($count>0)
			for($i=0; $i<$count; $i++)
				$str = stripslashes($str);
		$str = preg_replace("/&([a-z])[a-z]+;/i","$1",$str);
		//$str = preg_replace("/\s+/","-",$str);
		$str = preg_replace("/\s+/","",$str);
		return strtolower($str);
	}
	function convertToNormal($doc) {
		$str = $this->addslash(html_entity_decode($doc));
		$str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/",'a',$str);
		$str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/",'e',$str);
		$str = preg_replace("/(ì|í|ị|ỉ|ĩ)/",'i',$str);
		$str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/",'o',$str);
		$str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/",'u',$str);
		$str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/",'y',$str);
		$str = preg_replace("/(đ)/",'d',$str);
		$str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/",'A',$str);
		$str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/",'E',$str);
		$str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/",'I',$str);
		$str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/",'O',$str);
		$str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/",'U',$str);
		$str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/",'Y',$str);
		$str = preg_replace("/(Đ)/",'D',$str);
		$str = preg_replace("/( )/",'-',$str);
		$str = preg_replace("/(&#39;)/",'',$str);
		$str = preg_replace("/(')/",'',$str);
		$str = preg_replace("/(&#39;|&nbsp;)/",'-',$str);
		$str = $this->stripslash($str);
		return $str;
	}
	function addslash($doc) {
		return addslashes($doc);
	}
	function stripslash($doc) {
		return stripslashes($doc);
	}
	function replaceSpace($doc) {
		$str = $this->convertToNormal($doc);
		return strtolower($str);
	}
	function base64ToJPEG($string, $folder=""){
		global $core, $dbconn, $clsISO;
		if($folder=='' || $string=='') return;
		$dirname = ftp_abs_path_info.DS.$this->replaceSpace($folder);
		$dirname = str_replace('//','/',$dirname);
		if(!is_dir(ABSPATH.$dirname)){
			$this->rmkdir(ABSPATH.$dirname,0777);
		}
		#
		$filename = $dirname.'/fh_'.md5(time().uniqid()).'.jpeg';
		$filename = str_replace('//','/',$filename);
		$data = @base64_decode($string);
		//header('Content-Type: image/jpeg');
		$this->_write_file(ABSPATH.$filename,$data);
		return $filename;
	}
	function get_files($service, $folderId, $path = '') {
		global $profile_id;
		$resultArray = [];
		$results = $service->files->listFiles([
			'orderBy' => "createdTime desc",
			'q' => "'{$folderId}' in parents and trashed = false",
			'corpora' => "allDrives",
			'supportsAllDrives' => 'true',
			'includeItemsFromAllDrives' => 'true',
			'fields' => 'files(id, name, createdTime)'
		]);
		// return $results;
		$files = $results->getFiles();
		foreach ($files as $file) {
			$filePath = $path . '/' . $file->getName();
			if ($file->mimeType == 'application/vnd.google-apps.folder') {
				$resultArray = array_merge($resultArray, $this->get_files(
					$service, $file->getId(), $filePath)
				);
			} else{
				if($file->mimeType != 'application/vnd.google-apps.shortcut'){
					$resultArray[$file->getId()]["filePath"] = $filePath;
					$resultArray[$file->getId()]["createdTime"] = strtotime($file->createdTime);
				}
			}
		} 
		$total = @array_column($resultArray, 'createdTime');
		@array_multisort($total, SORT_DESC, $resultArray);
		return $resultArray;
	}
	/**
	 * ID thư mục Drive của 1 khối, lấy từ "Cấu hình hệ thống".
	 * Không còn hằng số dự phòng: chưa cấu hình thì trả rỗng và getIMG() bỏ qua khối,
	 * để admin đổi thư mục ở đúng một chỗ và không bị hằng số cũ che mất.
	 * @return string
	 */
	function getFolderId($block){
		$value = Configuration::getInstance()->getValue('gdrive_folder_'.$block, '');
		$value = trim($value);
		if($value === ''){
			return '';
		}
		return $this->parseFolderId($value);
	}
	/**
	 * Tách ID khỏi chuỗi admin dán vào: ID trần, link thư mục (…/folders/<id>)
	 * hoặc link dạng tham số (…?id=<id>) đều nhận.
	 * @return string
	 */
	function parseFolderId($value){
		if(preg_match('#/folders/([A-Za-z0-9_-]+)#', $value, $matches)){
			return $matches[1];
		}
		if(preg_match('#[?&]id=([A-Za-z0-9_-]+)#', $value, $matches)){
			return $matches[1];
		}
		return $value;
	}
	/**
	 * Danh sách file trong 1 thư mục Drive, gọi thẳng API (không qua cache).
	 * ID sai, mất quyền chia sẻ hay lỗi mạng đều chỉ trả null: khối ảnh trống
	 * chứ tuyệt đối không để exception thoát ra làm trắng cả trang.
	 * @return array|null
	 */
	function fetchDriveFiles($folder_id){
		try {
			$client = new Google_Client();
			$client->setClientId(GOOGLE_CLIENT_ID);
			$client->setClientSecret(GOOGLE_CLIENT_SECRET);
			$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
			$client->setScopes(Google_Service_Drive::DRIVE);
			$service = new Google_Service_Drive($client);
			return $this->get_files($service, $folder_id);
		} catch(Throwable $e){
			// Bắt Throwable chứ không chỉ Exception: thư viện Google có thể ném Error.
			@error_log('Share::fetchDriveFiles ['.$folder_id.'] '.$e->getMessage());
			return null;
		}
	}
	/**
	 * Nội dung cache JSON của 1 khối. File thiếu/hỏng coi như chưa có cache.
	 * @return array
	 */
	function readCacheFile($cachedFile){
		if(!file_exists($cachedFile)){
			return array();
		}
		try {
			$decoder = new Webmozart\Json\JsonDecoder();
			$data = $decoder->decodeFile($cachedFile);
			return is_array($data) ? $data : array();
		} catch(Throwable $e){
			@error_log('Share::readCacheFile ['.$cachedFile.'] '.$e->getMessage());
			return array();
		}
	}
	/**
	 * Ghi cache kèm thư mục nguồn để lần sau biết cache thuộc thư mục nào.
	 * Ghi hỏng (thiếu quyền, đầy đĩa) chỉ mất cache, không làm gãy trang.
	 * @return void
	 */
	function writeCacheFile($cachedFile, $folder_id, $list_files){
		try {
			$encoder = new Webmozart\Json\JsonEncoder();
			$data = [
				"time"	=>	strtotime("+5 minutes"),
				"folder_id"	=>	$folder_id,
				"lst_file"	=>	$list_files
			];
			$encoder->encodeFile($data, $cachedFile);
		} catch(Throwable $e){
			@error_log('Share::writeCacheFile ['.$cachedFile.'] '.$e->getMessage());
		}
	}
	function getIMG($block='honor'){
		global $core, $dbconn, $clsISO, $profile_id;
		$clsProjectMeta = new ProjectMeta();
		require_once(DIR_INCLUDES.'/json_master/autoload.php');
		require_once(DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
		###
		if($block=='honor'){
			$file_name = 'vinh-danh.json';
			// Vinh danh chưa mở cho admin đổi thư mục — giữ nguyên hằng số.
			$folder_id = GOOGLE_DRIVE_FOLDER_HONOR_ID;
		} else if($block=='birthday'){
			$file_name = 'birthday-cached.json';
			$folder_id = $this->getFolderId('birthday');
		} else if($block=='wellcome'){
			$file_name = 'wellcome-cached.json';
			$folder_id = $this->getFolderId('wellcome');
		} else {
			return [];
		}
		if($folder_id == ''){
			return [];
		}
		$cachedFile = DIR_CACHE_JSON.DS.$file_name;
		$dataCached = $this->readCacheFile($cachedFile);
		$list_files = isset($dataCached['lst_file']) ? $dataCached['lst_file'] : [];
		$cached_folder = isset($dataCached['folder_id']) ? $dataCached['folder_id'] : '';
		$cached_time = isset($dataCached['time']) ? (int) $dataCached['time'] : 0;
		// Đổi thư mục ở màn cấu hình phải thấy ngay, không chờ hết 5 phút cache.
		$is_expired = ($cached_time <= time() || $cached_folder !== $folder_id);
		if($is_expired){
			$fresh_files = $this->fetchDriveFiles($folder_id);
			if($fresh_files !== null){
				$list_files = $fresh_files;
				$this->writeCacheFile($cachedFile, $folder_id, $list_files);
			} else if($cached_folder !== $folder_id){
				// Gọi Drive lỗi ngay sau khi đổi thư mục: cache đang giữ ảnh của thư
				// mục cũ, hiển thị lại sẽ khiến admin tưởng ID mới đã chạy.
				$list_files = [];
			}
		}
		$list_images = [];
		if(!empty($list_files)){
			foreach ($list_files as $key => $val) {
				$list_images[] = $clsISO->genGoogleURL($key,"view",0);
			}
		}
		return $list_images;
	}
}
?>