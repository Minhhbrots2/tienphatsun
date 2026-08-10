<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
class Shop extends dbBasic{
	function __construct(){
		global $core, $clsISO;
		$this->pkey = "shop_id";
		$this->tbl = DB_PREFIX."shop";
	}
	function doDelete($shop_id){
		// Delete
		$this->deleteOne($shop_id);
		return 1;
	}
	function get_files($service, $folderId, $path = '') {
		global $core, $clsISO;
		$resultArray = [];
		$results = $service->files->listFiles([
			'orderBy' => "name",
			'q' => "'".$folderId."' in parents",
			'corpora' => "allDrives",
			'supportsAllDrives' => 'true',
			'includeItemsFromAllDrives' => 'true'
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
					$resultArray[] = $clsISO->genGoogleURL($file->getId());
				}
			}
		} 
		return $resultArray;
	}
	function crawlDriver($content){
		global $core, $clsISO;
		$ret = array();
		$list_files = [];
		if($clsISO->checkContainer($content,"drive.google.com","")){
			/** Load API */
			require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
			/** Init Client */
			$client = new Google_Client();
			$client->setClientId(GOOGLE_CLIENT_ID);
			$client->setClientSecret(GOOGLE_CLIENT_SECRET);
			$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
			$client->setScopes(Google_Service_Drive::DRIVE);
			$service = new Google_Service_Drive($client);
			if(@preg_match('/folders/', $content)){
				@preg_match('/.*[^-\w]([-\w]{25,})[^-\w]?.*/', $content, $matches);
				$folder_id = $matches[1];
				$list_image = $this->get_files($service, $folder_id);
			} else if(preg_match('/file/', $content)){
				preg_match('~/d/\K[^/]+(?=/)~', $content, $matches);
				$gg_id = $matches[0];
				$list_image[] = $clsISO->genGoogleURL($gg_id);
			} 
		}
		return $list_image;
	}
}