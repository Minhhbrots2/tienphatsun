<?php
	error_reporting(0);
	ini_set('display_errors',0);
	define('DS', DIRECTORY_SEPARATOR);
	define('ABSPATH', $_SERVER['DOCUMENT_ROOT']);
	define('DIR_INCLUDES', ABSPATH . DS . 'core');
	define('DIR_LIB', DIR_INCLUDES . DS . 'libraries');
	require(ABSPATH.'/config.php');
	require(DIR_INCLUDES.'/Mysqldump.php');
	require(DIR_LIB . DS. 'File.php');
	try {
		$dump = new Ifsnop\Mysqldump\Mysqldump('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
		$filename = 'backupdb_'.DB_NAME.'_'.date('Y-m-d_H-i-s',time()); 
		$dir = ABSPATH . '/backups/db/'.$filename.'.sql';
		$fp = @fopen($dir,"wb");
		// rmkdir($dir);
		$dump->start($dir);
	} catch (\Exception $e) {
		echo 'mysqldump-php error: ' . $e->getMessage();
	}
	die("DONE!");
	$filename = 'backupdb_'.DB_NAME.'_'.date('Y-m-d_H-i-s',time()); 
	$dir = ABSPATH . '/backups/db/'.$filename.'.sql';
	// exec('mysqldump --user='.DB_USER.' --password='.DB_PASS.' '.DB_NAME.' | gzip > '.ABSPATH.'/backups/db/'.$filename.'.sql');
	exec('mysqldump --user='.DB_USER.' --password='.DB_PASS.' --host='.DB_HOST.' '.DB_NAME.' --result-file='.$dir.' 2>&1');
	$file_url = 'https://ca.futurehomes.vn/backups/db/'.$filename.'.sql';
	header('Content-Type: application/octet-stream');
	header("Content-Transfer-Encoding: Binary"); 
	header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
	readfile($file_url); // do the double-download-dance (dirty but worky)
	echo(1);die();
?>