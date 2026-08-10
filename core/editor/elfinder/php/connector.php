<?php
error_reporting(false);
ini_set('display_errors',0);

define('DS', DIRECTORY_SEPARATOR);
define('ABSPATH', $_SERVER['DOCUMENT_ROOT']);
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT']);
require_once(ROOTPATH.'/config.php');

include_once dirname(__FILE__).DS.'elFinderConnector.class.php';
include_once dirname(__FILE__).DS.'elFinder.class.php';
include_once dirname(__FILE__).DS.'elFinderVolumeDriver.class.php';
include_once dirname(__FILE__).DS.'elFinderVolumeLocalFileSystem.class.php';
// Required for MySQL storage connector
//include_once dirname(__FILE__).DS.'elFinderVolumeMySQL.class.php';
/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from  '.' (dot)
 * @param  string  $attr  attribute name (read|write|locked|hidden)
 * @param  string  $path  file path relative to volume root directory started with directory separator
 * @return bool
 **/
function access($attr, $path, $data, $volume) {
	return strpos(basename($path), '.') === 0   // if file/folder begins with '.' (dot)
		? !($attr == 'read' || $attr == 'write')  // set read+write to false, other (locked+hidden) set to true
		: ($attr == 'read' || $attr == 'write');  // else set read+write to true, locked+hidden to false
}
$opts = array(
	'debug' => false,
	'roots' => array(
		array(
            'root'          => _elfinder_dir,   
			'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
			'path'          => _elfinder_dir,       // path to files (REQUIRED)
            'URL'           => _elfinder_url, 		// URL to files (REQUIRED)
			'accessControl' => 'access'             // disable and hide dot starting files (OPTIONAL)
		)
	)
);
// run elFinder
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();