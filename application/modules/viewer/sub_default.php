<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2014-2015 Future Group.        	  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
function get_files($service, $folderId, $path = '') {
	$resultArray = [];
	$results = $service->files->listFiles([
		'orderBy' => "name",
		'q' => "'".$folderId."' in parents",
		'corpora' => "allDrives",
		'supportsAllDrives' => 'true',
		'includeItemsFromAllDrives' => 'true'
	]);
	foreach ($results->getFiles() as $file) {
		$filePath = $path . '/' . $file->getName();
		$resultArray[$file->getId()] = $filePath;
		if ($file->mimeType == 'application/vnd.google-apps.folder') {
			$resultArray = array_merge($resultArray, get_files($service, $file->getId(), $filePath));
		} 
	} 
	return $resultArray;
}
function default_default(){
	global $smarty,$assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page
	,$description_page,$keyword_page,$extLang,$clsISO,$clsProfile;
	$clsProject = new Project();
	$clsProjectMeta = new ProjectMeta();
	/** Load API */
	require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
	/** Init Client */
	$client = new Google_Client();
	$client->setClientId(GOOGLE_CLIENT_ID);
	$client->setClientSecret(GOOGLE_CLIENT_SECRET);
	$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
	$client->setScopes(Google_Service_Drive::DRIVE);
	$service = new Google_Service_Drive($client);
	##
	$folder_id = Input::get('folder_id');
	$holderG = Input::get('holderG', 'folder');
	//$clsISO->print_pre($holderG); die();
	$list_files = @get_files($service, $folder_id);
	// $clsISO->print_pre($list_files); die();
	$total_files = !empty($list_files) ? count($list_files) : 0;
	$smarty->assign('list_files', $list_files);
	$smarty->assign('total_files', $total_files);
	##
	$smarty->assign('holderG', $holderG);
	$smarty->assign('content', $content);
}
?>