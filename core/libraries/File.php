<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the ISOCMS                         # ||
|| # ISOCMS 6.0.0 VietISO Techical Team (vanthiembui.it@gmail.com)    # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 VietISO JSC.             # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||
|| #################################################################### ||
\*======================================================================*/
/*
|--------------------------------------------
| Create dir
| @param	string	$path
| @param	string	$mode
| @return	boolen
|---------------------------------------------
|
*/
if ( ! function_exists('rmkdir')){
	function rmkdir($path, $mode = 0777) {
		return is_dir($path) || ( rmkdir(dirname($path), $mode) && _mkdir($path, $mode) );
	}
}
if ( ! function_exists('_mkdir')){
	function _mkdir($path, $mode = 0777) {
		$old = umask(0);
		$res = @mkdir($path, $mode);
		umask($old);
		return $res;
	}
}
if ( ! function_exists('read_file')){
	/*
	|--------------------------------------------
	| Read File
	| Opens the file specified in the path and returns it as a string.
	| @todo	Remove in version 3.1+.
	| @deprecated	3.0.0	It is now just an alias for PHP's native file_get_contents().
	| @param	string	$file	Path to file
	| @return	string	File contents
	|---------------------------------------------
	|
	*/
	function read_file($file){
		$content = FALSE;
		if(function_exists('file_get_contents')){
			$content = @file_get_contents($file);
		}
		if($content===FALSE && function_exists('curl_version')){
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $file);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HEADER, false);
			$content = curl_exec($curl);
			curl_close($curl);
		}
		return $content;
	}	
	function _read_file($file){
		$handle = fopen ($file, "rb");
		$contents = "";
		do {
			$data = fread($handle, 8192);
			if (strlen($data) == 0) {
			   break;
		   }
		   $contents .= $data;
		}
		while(true);
		fclose ($handle);
		return $contents;
	}
}
if ( ! function_exists('save_file')){
	function save_file($file,$content,$append=0,$binary=0){
		return write_file($file, $content);
	}
}
if ( ! function_exists('write_file')){
	/*
	|--------------------------------------------
	| Write File
	| Writes data to the file specified in the path.
	| Creates a new file if non-existent.
	| @param	string	$path	File path
	| @param	string	$data	Data to write
	| @param	string	$mode	fopen() mode (default: 'wb')
	| @return	bool
	|---------------------------------------------
	|
	*/
	function write_file($path, $data, $mode = 'wb'){
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
}
if(!function_exists('get_size')){
	/*
	|--------------------------------------------
	| Get File Size
	| @return	bytes
	|---------------------------------------------
	|
	*/
	function get_size($size) {
		$kb = 1024;
		$mb = 1024 * $kb;
		$gb = 1024 * $mb;
		$tb = 1024 * $gb;
		if ($size < $kb) {
			$file_size = "$size Bytes";
		}elseif ($size < $mb) {
			$final = round($size/$kb,2);
			$file_size = "$final KB";
		}elseif ($size < $gb) {
			$final = round($size/$mb,2);
			$file_size = "$final MB";
		}elseif($size < $tb) {
			$final = round($size/$gb,2);
			$file_size = "$final GB";
		}else {
			$final = round($size/$tb,2);
			$file_size = "$final TB";
		}
		return $file_size;
	}
}
if (!function_exists('file_put_contents')) {
	function file_put_contents($filename="", $str){
		if (is_writable($filename)) {
			$fp = fopen($filename, "w");
			fwrite($fp, $str);
			fclose($fp);
			return 1;
		}else{
			return 0;
		}
	}	
}
if (!function_exists('file_get_contents')) {
	function file_get_contents($filename=""){
		$fp = fopen($filename, "w");
		$str = fread($fp, filesize($filename));
		fclose($fp);
		return $str;
	}	
}
function getDirectory($dir, $pattern=null, &$arr=array()){
	if(is_dir($dir)){
		if ($handle = @opendir($dir)) {
			while (false !== ($file = readdir($handle))) {
				if (is_dir($dir.DS.$file) && $file != "." && $file != ".."){
						getDirectory($dir.DS.$file, $pattern, $arr);
				}else if($file != "." && $file != ".."){
					if(!is_null($pattern) && preg_match($pattern, $file)){
						$arr[] = $file;
					}else if(is_null($pattern)){
						$arr[] = $file;
					}
				}
			}
			@closedir($handle);
		}
	}
	return $arr;
}
if ( ! function_exists('delete_files')){
	/*
	|--------------------------------------------
	| Delete Files
	| Deletes all files contained in the supplied directory path.
	| Files must be writable or owned by the system in order to be deleted.
	| within the supplied base directory will be nuked as well.
	| @param	string	$path		File path
	| @param	bool	$del_dir	Whether to delete any directories found in the path
	| @param	bool	$htdocs		Whether to skip deleting .htaccess and index page files
	| @param	int	$_level		Current directory depth level (default: 0; internal use only)
	| @return	bool
	|---------------------------------------------
	|
	*/
	function delete_files($path, $del_dir = FALSE, $htdocs = FALSE, $_level = 0){
		// Trim the trailing slash
		$path = rtrim($path, '/\\');
		if (!$current_dir = @opendir($path)){
			return FALSE;
		}
		while (FALSE !== ($filename = @readdir($current_dir))){
			if ($filename !== '.' && $filename !== '..'){
				if (is_dir($path.DIRECTORY_SEPARATOR.$filename) && $filename[0] !== '.'){
					delete_files($path.DIRECTORY_SEPARATOR.$filename, $del_dir, $htdocs, $_level + 1);
				}elseif ($htdocs !== TRUE OR ! preg_match('/^(\.htaccess|index\.(html|htm|php)|web\.config|\.cache|\.json)$/i', $filename)){
					@unlink($path.DIRECTORY_SEPARATOR.$filename);
				}
			}
		}
		closedir($current_dir);
		return ($del_dir === TRUE && $_level > 0) ? @rmdir($path) : TRUE;
	}
}
if ( ! function_exists('get_filenames')){
	/*
	|--------------------------------------------
	| Get Filenames
	| Reads the specified directory and builds an array containing the filenames.
	| Any sub-folders contained within the specified path are read as well.
	| @param	string	path to source
	| @param	bool	whether to include the path as part of the filename
	| @param	bool	internal variable to determine recursion status - do not use in calls
	| @return	array
	|---------------------------------------------
	|
	*/
	function get_filenames($source_dir, $include_path = FALSE, $_recursion = FALSE){
		static $_filedata = array();
		if ($fp = @opendir($source_dir)){
			// reset the array and make sure $source_dir has a trailing slash on the initial call
			if ($_recursion === FALSE){
				$_filedata = array();
				$source_dir = rtrim(realpath($source_dir), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
			}
			while (FALSE !== ($file = readdir($fp))){
				if (is_dir($source_dir.$file) && $file[0] !== '.' && $file[0] !== '..'){
					get_filenames($source_dir.$file.DIRECTORY_SEPARATOR, $include_path, TRUE);
				}
				elseif ($file[0] !== '.' && $file[0] !== '..'){
					$_filedata[] = ($include_path === TRUE) ? $source_dir.$file : $file;
				}
			}
			closedir($fp);
			return $_filedata;
		}
		return FALSE;
	}
}
if ( ! function_exists('get_dir_file_info')){
	/*
	|--------------------------------------------
	| Get Directory File Information
	| Reads the specified directory and builds an array containing the filenames,
	| Any sub-folders contained within the specified path are read as well.
	| @param	string	path to source
	| @param	bool	Look only at the top level directory specified?
	| @param	bool	internal variable to determine recursion status - do not use in calls
	| @return	array
	|---------------------------------------------
	|
	*/
	function get_dir_file_info($source_dir, $top_level_only = TRUE, $_recursion = FALSE){
		static $_filedata = array();
		$relative_path = $source_dir;
		if ($fp = @opendir($source_dir)){
			// reset the array and make sure $source_dir has a trailing slash on the initial call
			if ($_recursion === FALSE){
				$_filedata = array();
				$source_dir = rtrim(realpath($source_dir), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
			}
			// Used to be foreach (scandir($source_dir, 1) as $file), but scandir() is simply not as fast
			while (FALSE !== ($file = readdir($fp))){
				if (is_dir($source_dir.$file) && $file[0] !== '.' && $top_level_only === FALSE){
					get_dir_file_info($source_dir.$file.DIRECTORY_SEPARATOR, $top_level_only, TRUE);
				}
				elseif ($file[0] !== '.'){
					$_filedata[$file] = get_file_info($source_dir.$file);
					$_filedata[$file]['relative_path'] = $relative_path;
				}
			}
			closedir($fp);
			return $_filedata;
		}
		return FALSE;
	}
}
if ( ! function_exists('get_file_info')){
	/*
	|--------------------------------------------
	| Get File Info
	| Given a file and path, returns the name, path, size, date modified
	| Second parameter allows you to explicitly declare what information you want returned
	| Options are: name, server_path, size, date, readable, writable, executable, fileperms
	| Returns FALSE if the file cannot be found.
	|
	| @param	string	path to file
	| @param	mixed	array or comma separated string of information returned
	| @return	array
	|---------------------------------------------
	|
	*/
	function get_file_info($file, $returned_values = array('name', 'server_path', 'size', 'date')){
		if ( ! file_exists($file)){
			return FALSE;
		}
		if (is_string($returned_values)){
			$returned_values = explode(',', $returned_values);
		}
		foreach ($returned_values as $key){
			switch ($key){
				case 'name':
					$fileinfo['name'] = basename($file);
					break;
				case 'server_path':
					$fileinfo['server_path'] = $file;
					break;
				case 'size':
					$fileinfo['size'] = filesize($file);
					break;
				case 'date':
					$fileinfo['date'] = filemtime($file);
					break;
				case 'readable':
					$fileinfo['readable'] = is_readable($file);
					break;
				case 'writable':
					$fileinfo['writable'] = is_really_writable($file);
					break;
				case 'executable':
					$fileinfo['executable'] = is_executable($file);
					break;
				case 'fileperms':
					$fileinfo['fileperms'] = fileperms($file);
					break;
			}
		}
		return $fileinfo;
	}
}
if ( ! function_exists('get_mime_by_extension')){
	/*
	|--------------------------------------------
	| Get Mime by Extension
	|
	| Translates a file extension into a mime type based on config/mimes.php.
	| Returns FALSE if it can't determine the type, or open the mime config file
	|
	| Note: this is NOT an accurate way of determining file mime types, and is here strictly as a convenience
	| It should NOT be trusted, and should certainly NOT be used for security
	|
	| @param	string	$filename	File name
	| @return	string
	|---------------------------------------------
	|
	*/
	function get_mimes(){
		return array(	
			'hqx'	=>	'application/mac-binhex40',
			'cpt'	=>	'application/mac-compactpro',
			'csv'	=>	array(
							'text/x-comma-separated-values',
							'text/comma-separated-values',
							'application/octet-stream',
							'application/vnd.ms-excel',
							'text/x-csv', 'text/csv',
							'application/csv',
							'application/excel',
							'application/vnd.msexcel'
						),
			'bin'	=>	'application/macbinary',
			'dms'	=>	'application/octet-stream',
			'lha'	=>	'application/octet-stream',
			'lzh'	=>	'application/octet-stream',
			'exe'	=>	array('application/octet-stream', 'application/x-msdownload'),
			'class'	=>	'application/octet-stream',
			'psd'	=>	'application/x-photoshop',
			'so'	=>	'application/octet-stream',
			'sea'	=>	'application/octet-stream',
			'dll'	=>	'application/octet-stream',
			'oda'	=>	'application/oda',
			'pdf'	=>	array('application/pdf', 'application/x-download'),
			'ai'	=>	'application/postscript',
			'eps'	=>	'application/postscript',
			'ps'	=>	'application/postscript',
			'smi'	=>	'application/smil',
			'smil'	=>	'application/smil',
			'mif'	=>	'application/vnd.mif',
			'xls'	=>	array('application/excel', 'application/vnd.ms-excel', 'application/msexcel'),
			'ppt'	=>	array('application/powerpoint', 'application/vnd.ms-powerpoint'),
			'wbxml'	=>	'application/wbxml',
			'wmlc'	=>	'application/wmlc',
			'dcr'	=>	'application/x-director',
			'dir'	=>	'application/x-director',
			'dxr'	=>	'application/x-director',
			'dvi'	=>	'application/x-dvi',
			'gtar'	=>	'application/x-gtar',
			'gz'	=>	'application/x-gzip',
			'php'	=>	'application/x-httpd-php',
			'php4'	=>	'application/x-httpd-php',
			'php3'	=>	'application/x-httpd-php',
			'phtml'	=>	'application/x-httpd-php',
			'phps'	=>	'application/x-httpd-php-source',
			'js'	=>	'application/x-javascript',
			'swf'	=>	'application/x-shockwave-flash',
			'sit'	=>	'application/x-stuffit',
			'tar'	=>	'application/x-tar',
			'tgz'	=>	array('application/x-tar', 'application/x-gzip-compressed'),
			'xhtml'	=>	'application/xhtml+xml',
			'xht'	=>	'application/xhtml+xml',
			'zip'	=>  array('application/x-zip', 'application/zip', 'application/x-zip-compressed'),
			'mid'	=>	'audio/midi',
			'midi'	=>	'audio/midi',
			'mpga'	=>	'audio/mpeg',
			'mp2'	=>	'audio/mpeg',
			'mp3'	=>	array('audio/mpeg', 'audio/mpg', 'audio/mpeg3', 'audio/mp3'),
			'aif'	=>	'audio/x-aiff',
			'aiff'	=>	'audio/x-aiff',
			'aifc'	=>	'audio/x-aiff',
			'ram'	=>	'audio/x-pn-realaudio',
			'rm'	=>	'audio/x-pn-realaudio',
			'rpm'	=>	'audio/x-pn-realaudio-plugin',
			'ra'	=>	'audio/x-realaudio',
			'rv'	=>	'video/vnd.rn-realvideo',
			'wav'	=>	'audio/x-wav',
			'bmp'	=>	'image/bmp',
			'gif'	=>	'image/gif',
			'jpeg'	=>	array('image/jpeg', 'image/pjpeg'),
			'jpg'	=>	array('image/jpeg', 'image/pjpeg'),
			'jpe'	=>	array('image/jpeg', 'image/pjpeg'),
			'png'	=>	array('image/png',  'image/x-png'),
			'tiff'	=>	'image/tiff',
			'tif'	=>	'image/tiff',
			'css'	=>	'text/css',
			'html'	=>	'text/html',
			'htm'	=>	'text/html',
			'shtml'	=>	'text/html',
			'txt'	=>	'text/plain',
			'text'	=>	'text/plain',
			'log'	=>	array('text/plain', 'text/x-log'),
			'rtx'	=>	'text/richtext',
			'rtf'	=>	'text/rtf',
			'xml'	=>	'text/xml',
			'xsl'	=>	'text/xml',
			'mpeg'	=>	'video/mpeg',
			'mpg'	=>	'video/mpeg',
			'mpe'	=>	'video/mpeg',
			'qt'	=>	'video/quicktime',
			'mov'	=>	'video/quicktime',
			'avi'	=>	'video/x-msvideo',
			'movie'	=>	'video/x-sgi-movie',
			'doc'	=>	'application/msword',
			'docx'	=>	'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'xlsx'	=>	'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'word'	=>	array('application/msword', 'application/octet-stream'),
			'xl'	=>	'application/excel',
			'eml'	=>	'message/rfc822',
			'json'  => 	array('application/json', 'text/json')
		);
	}
	function get_mime_by_extension($filename){
		static $mimes;
		if ( ! is_array($mimes)){
			$mimes = get_mimes();
			if (empty($mimes)){
				return FALSE;
			}
		}
		$extension = strtolower(substr(strrchr($filename, '.'), 1));
		if (isset($mimes[$extension])){
			return is_array($mimes[$extension])
				? current($mimes[$extension]) // Multiple mime types, just give the first one
				: $mimes[$extension];
		}
		return FALSE;
	}
}
if ( ! function_exists('symbolic_permissions')){
	/*
	|--------------------------------------------
	| Symbolic Permissions
	|
	| Takes a numeric value representing a file's permissions and returns
	| standard symbolic notation representing that value
	|
	| @param	int	$perms	Permissions
	| @return	string
	|---------------------------------------------
	|
	*/
	function symbolic_permissions($perms){
		if (($perms & 0xC000) === 0xC000){
			$symbolic = 's'; // Socket
		}
		elseif (($perms & 0xA000) === 0xA000){
			$symbolic = 'l'; // Symbolic Link
		}
		elseif (($perms & 0x8000) === 0x8000){
			$symbolic = '-'; // Regular
		}
		elseif (($perms & 0x6000) === 0x6000){
			$symbolic = 'b'; // Block special
		}
		elseif (($perms & 0x4000) === 0x4000){
			$symbolic = 'd'; // Directory
		}
		elseif (($perms & 0x2000) === 0x2000){
			$symbolic = 'c'; // Character special
		}
		elseif (($perms & 0x1000) === 0x1000){
			$symbolic = 'p'; // FIFO pipe
		}
		else{
			$symbolic = 'u'; // Unknown
		}
		// Owner
		$symbolic .= (($perms & 0x0100) ? 'r' : '-')
			.(($perms & 0x0080) ? 'w' : '-')
			.(($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x' ) : (($perms & 0x0800) ? 'S' : '-'));

		// Group
		$symbolic .= (($perms & 0x0020) ? 'r' : '-')
			.(($perms & 0x0010) ? 'w' : '-')
			.(($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x' ) : (($perms & 0x0400) ? 'S' : '-'));

		// World
		$symbolic .= (($perms & 0x0004) ? 'r' : '-')
			.(($perms & 0x0002) ? 'w' : '-')
			.(($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x' ) : (($perms & 0x0200) ? 'T' : '-'));

		return $symbolic;
	}
}
if ( ! function_exists('octal_permissions')){
	/*
	|--------------------------------------------
	| Octal Permissions
	|
	| Takes a numeric value representing a file's permissions and returns
	| a three character string representing the file's octal permissions
	|
	| @param	int	$perms	Permissions
	| @return	string
	|---------------------------------------------
	|
	*/
	function octal_permissions($perms){
		return substr(sprintf('%o', $perms), -3);
	}
}
?>