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
class GoogleDrive {
	/**
	* Google_Service_Drive instance
	*
	* @var Google_Service_Drive
	*/
	protected $service;
	/**
	* MIME tyoe of directory
	*
	* @var string
	*/
	const DIRMIME = 'application/vnd.google-apps.folder';
	/**
	* Default parameters of each commands
	*
	* @var array
	*/
	private $defaultParams = [];
	
	function __construct(){
		/** Load API */
		require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
		/** Init Client */
		$client = new Google_Client();
		$client->setClientId(GOOGLE_CLIENT_ID);
		$client->setClientSecret(GOOGLE_CLIENT_SECRET);
		$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
		$client->setScopes(Google_Service_Drive::DRIVE);
		// Load previously authorized token from a file,if it exists.
		// The file token.json stores the user's access and refresh tokens,and is
		// created automatically when the authorization flow completes for the first
		$this->service = new Google_Service_Drive($client);
	}
	function create_folder($name, $parentId="root") {
		$result = $this->service->files->listFiles(array("q" => "name='{$name}' and trashed=false"));
		if (count($result->getFiles()) == 0) {
			$file = new Google_Service_Drive_DriveFile([
				'name' => $name,
				'mimeType' => self::DIRMIME
			]);
			$result = $this->service->files->create($file);
			$folder_id = $result->getId();
		} else {
			$folder_id = $result->getFiles()[0]->getId();
		}
		return $folder_id;
    }
	function deleteFile($spreadsheetId) {
		try {
			$this->service->files->delete($spreadsheetId);
		} catch (Exception $e) {
			return 0;
		}
		return 1;
	}
	function upload($title, $mimeType, $filename, $folder_id=""){
		global $core, $dbconn, $clsISO;
		$file = new Google_Service_Drive_DriveFile();
		// Set the metadata
		$file->setName($title);
		// $file->setDescription($description);
		$file->setMimeType($mimeType);
		if(!empty($folder_id)){
			$file->setParents(array($folder_id));
		} else {
			$file->setParents(array(GOOGLE_DRIVE_FOLDER_ID));
		}
		try{
			// Get the contents of the file uploaded
			$data = @file_get_contents($filename);
			// Try to upload the file, you can add the parameters e.g. if you want to 
			// convert a .doc to editable google format, add 'convert' = 'true'
			$createdFile = $this->service->files->create($file, array(
				'data' => $data,
				'mimeType' => $mimeType,
				'uploadType' => 'multipart',
				'supportsAllDrives' => true
			));
			// Return a bunch of data including the link to the file we just uploaded
			return $createdFile;
		} catch (Exception $e) {
			print "An error occurred: " . $e->getMessage();
		}
	}
}
