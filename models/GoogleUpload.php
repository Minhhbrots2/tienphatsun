<?php 
class GoogleUpload {
	/**
	* Google_Service_Drive instance
	*
	* @var Google_Service_Drive
	*/
	protected $service;
	/**
	* Fetch fields setting for get
	*
	* @var string
	*/
	const FETCHFIELDS_GET = 'id,name,mimeType,modifiedTime,parents,permissions,size,webContentLink,webViewLink';

	/**
	* Fetch fields setting for list
	*
	* @var string
	*/
	const FETCHFIELDS_LIST = 'files(FETCHFIELDS_GET),nextPageToken';

	/**
	* MIME tyoe of directory
	*
	* @var string
	*/
	const DIRMIME = 'application/vnd.google-apps.folder';
	/**
	* List of fetch field for get
	*
	* @var string
	*/
	private $fetchfieldsGet = '';

	/**
	* List of fetch field for lest
	*
	* @var string
	*/
	private $fetchfieldsList = '';

	/**
	* Additional fetch fields array
	*
	* @var array
	*/
	private $additionalFields = [];
	/**
	* Default options
	*
	* @var array
	*/
    protected static $defaultOptions = [
        'spaces' => 'drive',
        'useHasDir' => false,
        'additionalFetchField' => '',
        'publishPermission' => [
            'type' => 'anyone',
            'role' => 'reader',
            'withLink' => true
        ],
        'appsExportMap' => [
            'application/vnd.google-apps.document' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.google-apps.spreadsheet' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.google-apps.drawing' => 'application/pdf',
            'application/vnd.google-apps.presentation' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/vnd.google-apps.script' => 'application/vnd.google-apps.script+json',
            'default' => 'application/pdf'
        ],
        // Default parameters for each command
        // see https://developers.google.com/drive/v3/reference/files
        // ex. 'defaultParams' => ['files.list' => ['includeTeamDriveItems' => true]]
        'defaultParams' => [],
        // Team Drive Id
        'teamDriveId' => null,
        // Corpora value for files.list with the Team Drive
        'corpora' => 'teamDrive',
        // Delete action 'trash' (Into trash) or 'delete' (Permanently delete)
        'deleteAction' => 'trash'
    ];
	/**
	* Default parameters of each commands
	*
	* @var array
	*/
	private $defaultParams = [];
	/**
	* Google_Client instance
	*
	* @var Google_Client
	*/
    protected $folder_id;
	
	/**
     * Cache of hasDir
     *
     * @var array
     */
    private $cacheFileObjectsByName = [];
    /**
     * Cache of hasDir
     *
     * @var array
     */
    private $cacheHasDirs = [];
    /**
     * Use hasDir function
     *
     * @var bool
     */
    private $useHasDir = false;
	
	public function __construct($root=null, $parent = null, $useHasDir = false){
		global $core, $dbconn;
		if (! $root) $root = 'root';
		$this->root = $root;
		// $this->setPathPrefix($root);
		$this->useHasDir = $useHasDir;
		$this->fetchfieldsGet = self::FETCHFIELDS_GET;
		/** Load API */
		require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
		/** Init Client */
		$client = new Google_Client();
		$client->setClientId(GOOGLE_CLIENT_ID);
		$client->setClientSecret(GOOGLE_CLIENT_SECRET);
		$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
		$client->setScopes(Google_Service_Drive::DRIVE);
		$this->service = new Google_Service_Drive($client);
		// we cache the id to avoid having google creating
		// a new folder on each time we call it,
		// because google drive works with 'id' not 'name'
		// & thats why u could have duplicated folders under the same name
		// $this->folder_id = '1-DsHQ2wnOrCiLz99arGW9l_EkpfUmMnZ';
	}
	 /**
     * Gets the service (Google_Service_Drive)
     *
     * @return object  Google_Service_Drive
     */
    function getService(){
        return $this->service;
    }
	// Liệt kê ảnh/video trong 1 folder Google Drive (cho tiến độ dự án). Trả [] nếu lỗi/không quyền.
	function listFolderMedia($folder_id){
		$result = array();
		if(empty($folder_id)) return $result;
		$pageToken = null;
		do {
			$params = array(
				'q' => "'".$folder_id."' in parents and trashed=false",
				'fields' => 'nextPageToken, files(id,name,mimeType)',
				'orderBy' => 'name',
				'pageSize' => 1000,
				'corpora' => 'allDrives',
				'supportsAllDrives' => true,
				'includeItemsFromAllDrives' => true
			);
			if($pageToken) $params['pageToken'] = $pageToken;
			$response = $this->service->files->listFiles($params);
			foreach($response->getFiles() as $f){
				$mime = $f->getMimeType();
				if(strpos($mime, 'image/') === 0){ $type = 'image'; }
				else if(strpos($mime, 'video/') === 0){ $type = 'video'; }
				else { continue; }
				$result[] = array(
					'id' => $f->getId(),
					'name' => $f->getName(),
					'mimeType' => $mime,
					'type' => $type
				);
			}
			$pageToken = $response->getNextPageToken();
		} while($pageToken);
		return $result;
	}
	 /**
     * Path splits to dirId, fileId or newName
     *
     * @param string $path
     *
     * @return array [ $dirId , $fileId|newName ]
     */
	function splitPath($path, $getParentId = true){
        $useSlashSub = defined('EXT_FLYSYSTEM_SLASH_SUBSTITUTE');
        if ($path === '' || $path === '/') {
            $fileName = $this->root;
            $dirName = '';
        } else {
            if ($useSlashSub) {
                $path = str_replace(EXT_FLYSYSTEM_SLASH_SUBSTITUTE, chr(7), $path);
            }
            $paths = explode('/', $path);
            $fileName = array_pop($paths);
            if ($getParentId) {
                $dirName = $paths ? array_pop($paths) : '';
            } else {
                $dirName = join('/', $paths);
            }
            if ($dirName === '') {
                $dirName = $this->root;
            }
        }
        return [ $dirName, $useSlashSub? str_replace(chr(7), '/', $fileName) : $fileName ];
    }
	 /**
     * Get file oblect Google_Service_Drive_DriveFile
     *
     * @param string $path
     *            itemId path
     * @param string $checkDir
     *            do check hasdir
     *
     * @return Google_Service_Drive_DriveFile|null
     */
    function getFileObject($path, $checkDir = false){
        list ($parentId, $itemId) = $this->splitPath($path, true);
        if (isset($this->cacheFileObjects[$itemId])) {
            return $this->cacheFileObjects[$itemId];
        } else if (isset($this->cacheFileObjectsByName[$parentId . '/' . $itemId])) {
            return $this->cacheFileObjectsByName[$parentId . '/' . $itemId];
        }

        $service = $this->service;
        $client = $service->getClient();
        $fileObj = $hasdir = NULL;
        $opts = [
            'fields' => $this->fetchfieldsGet
        ];
        try {
            $fileObj = $service->files->get($itemId, $this->applyDefaultParams($opts, 'files.get'));
            if ($checkDir && $this->useHasDir) {
                $hasdir = $service->files->listFiles($this->applyDefaultParams([
                    'pageSize' => 1,
                    'q' => sprintf('trashed = false and "%s" in parents and mimeType = "%s"', $itemId, self::DIRMIME)
                ], 'files.list'));
            }
        } catch (\Google_Service_Exception $e) {
            if (!$fileObj) {
                if (intVal($e->getCode()) != 404) {
                    return NULL;
                }
            }
        }
        if ($fileObj instanceof Google_Service_Drive_DriveFile) {
            if ($hasdir && $fileObj->mimeType === self::DIRMIME) {
                if ($hasdir instanceof Google_Service_Drive_FileList) {
                    $this->cacheHasDirs[$fileObj->getId()] = (bool) $hasdir->getFiles();
                }
            }
        } else {
            $fileObj = NULL;
        }
        $this->cacheFileObjects[$itemId] = $fileObj;
        return $fileObj;
    }
	/**
     * Create dirctory
     *
     * @param string $name
     * @param string $parentId
     *
     * @return Google_Service_Drive_DriveFile|NULL
     */
    function createDirectory($name, $parentId) {
		global $core, $clsISO;
		try {
			$result = $this->service->files->listFiles(array(
				"q" => "name='{$name}' and mimeType='".self::DIRMIME."' and trashed=false ",
				"supportsAllDrives" => true,
				"includeItemsFromAllDrives" => true
			));
			if (count($result->getFiles()) == 0) {
				$file = new Google_Service_Drive_DriveFile();
				$file->setName($name);
				$file->setParents([ $parentId ]);
				$file->setMimeType(self::DIRMIME);
				$obj = $this->service->files->create($file, $this->applyDefaultParams([
					'supportsAllDrives' => true,
					'fields' => $this->fetchfieldsGet
				], 'files.create'));
				return ($obj instanceof Google_Service_Drive_DriveFile) ? $obj->getId() : false;
			} else {
				// $clsISO->print_pre($result->getFiles()[0]->getId()); die();
				return $result->getFiles()[0]->getId();
			}
		} catch(Exception $e){
			throw new Exception($e->getMessage());
		}
    }
	/**
     * Item name splits to filename and extension
     * This function supported include '/' in item name
     *
     * @param string $name
     *
     * @return array [ 'filename' => $filename , 'extension' => $extension ]
     */
    protected function splitFileExtension($name) {
        $extension = '';
        $name_parts = explode('.', $name);
        if (isset($name_parts[1])) {
            $extension = array_pop($name_parts);
        }
        $filename = join('.', $name_parts);
        return compact('filename', 'extension');
    }
	/**
	 * Create dirctory
	 *
	 * @param string $name
	 * @param string $parentId
	 *
	 * @return Google_Service_Drive_DriveFile|NULL
	 */
	function create_folder($dirname, &$folder_id="") {
		global $core, $dbconn, $clsISO;
		$lst = @explode('/', $dirname);
		foreach($lst as $folder){
			$folder_id = $this->createDirectory($folder, $this->root);
			$this->root = $folder_id;
		}
		return $folder_id;
    }
	function delete_folder($folder_id) {
		global $core, $dbconn, $clsISO;
		/*$file = $this->service->files->get($folder_id, [
			'supportsAllDrives'      => true,
			'fields'                 => 'id, name, trashed'
		]);
		var_dump($file->trashed);die;*/
		try {			
			$this->service->files->delete($folder_id, [
				'supportsAllDrives' => true
			]);
			return 1;
		} catch (Exception $e) {
			$msg_error = $e->getMessage();
			var_dump($msg_error);die;
			return 0;
		}
		return $folder_id;
    }
	function upload($title, $mimeType, $filename, $folder_id="root"){
		global $core, $dbconn, $clsISO;
		$file = new Google_Service_Drive_DriveFile();
		// Set the metadata
		$file->setName($title);
		// $file->setDescription($description);
		$file->setMimeType($mimeType);
		if(!empty($folder_id)){
			$file->setParents(array($folder_id));
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
	function getDownloadUrl($file){
		if (strpos($file->mimeType, 'application/vnd.google-apps') !== 0) {
			// return 'https://www.googleapis.com/drive/v3/files/' . $file->getId() . '?alt=media';
			return 'https://drive.usercontent.google.com/download?id='. $file->getId() .'&export=download';
		} else {
			$mimeMap = $this->options['appsExportMap'];
			if (isset($mimeMap[$file->getMimeType()])) {
				$mime = $mimeMap[$file->getMimeType()];
			} else {
				$mime = $mimeMap['default'];
			}
			$mime = rawurlencode($mime);
			//return 'https://www.googleapis.com/drive/v3/files/' . $file->getId() . '/export?mimeType=' . $mime;
			return 'https://drive.usercontent.google.com/download?id='. $file->getId() .'&export=download&mimeType='.$mime;
			//https://drive.google.com/file/d/1fEg5exAAsiErRkjzQ_ZRBmz31iNedS_n/view
		}
		return false;
	}
	function getUrl($file){
		return 'https://drive.google.com/open?id='.$file->getId();
	}
	 /**
     * Return bytes from php.ini value
     *
     * @param string $iniName
     * @param string $val
     * @return number
     */
    protected function getIniBytes($iniName = '', $val = ''){
        if ($iniName !== '') {
            $val = ini_get($iniName);
            if ($val === false) {
                return 0;
            }
        }
        $val = trim($val, "bB \t\n\r\0\x0B");
        $last = strtolower($val[strlen($val) - 1]);
        $val = (int)$val;
        switch ($last) {
            case 't':
                $val *= 1024;
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $val;
    }
    /**
     * Return the number of memory bytes allocated to PHP
     *
     * @return int
     */
    protected function getMemoryUsedBytes() {
        return memory_get_usage(true);
    }
	 /**
     * Get the size of a file resource
     *
     * @param $resource
     *
     * @return int
     */
    protected function getFileSizeBytes($resource){
        return fstat($resource)['size'];
    }
    /**
     * Get a MediaFileUpload
     *
     * @param $client
     * @param $request
     * @param $mime
     * @param $chunkSizeBytes
     *
     * @return Google_Http_MediaFileUpload
     */
    protected function getMediaFileUpload($client, $request, $mime, $chunkSizeBytes){
        return new Google_Http_MediaFileUpload($client, $request, $mime, null, true, $chunkSizeBytes);
    }
    /**
     * Apply optional parameters for each command
     *
     * @param   array   $params   The parameters
     * @param   string  $cmdName  The command name
     *
     * @return array
     *
     * @see https://developers.google.com/drive/v3/reference/files
     * @see \Google_Service_Drive_Resource_Files
     */
    protected function applyDefaultParams($params, $cmdName){
        if (isset($this->defaultParams[$cmdName]) && is_array($this->defaultParams[$cmdName])) {
            return array_replace($this->defaultParams[$cmdName], $params);
        } else {
            return $params;
        }
    }
    /**
     * Enables Team Drive support by changing default parameters
     *
     * @return void
     *
     * @see https://developers.google.com/drive/v3/reference/files
     * @see \Google_Service_Drive_Resource_Files
     */
    public function enableTeamDriveSupport() {
        $this->defaultParams = array_merge_recursive(
            array_fill_keys([
                'files.copy', 'files.create', 'files.delete',
                'files.trash', 'files.get', 'files.list', 'files.update',
                'files.watch'
            ], ['supportsTeamDrives' => true]),
            $this->defaultParams
        );
    }
	/**
     * Selects Team Drive to operate by changing default parameters
     *
     * @return void
     *
     * @param   string   $teamDriveId   Team Drive id
     * @param   string   $corpora       Corpora value for files.list
     *
     * @see https://developers.google.com/drive/v3/reference/files
     * @see https://developers.google.com/drive/v3/reference/files/list
     * @see \Google_Service_Drive_Resource_Files
     */
    public function setTeamDriveId($teamDriveId, $corpora = 'teamDrive'){
        $this->enableTeamDriveSupport();
        $this->defaultParams = array_merge_recursive($this->defaultParams, [
            'files.list' => [
                'corpora' => $corpora,
                'includeTeamDriveItems' => true,
                'teamDriveId' => $teamDriveId
            ]
        ]);
        if ($this->root === 'root') {
            $this->setPathPrefix($teamDriveId);
            $this->root = $teamDriveId;
        }
    }
} 
?>