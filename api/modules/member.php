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
	$app->get('/upload-avatar', function ($request, $response) use ($app) {		
		require_once(DIR_INCLUDES.'/json_master/autoload.php');
		$clsProfile = new Profile();
		$clsISO = new ISO();
		$arr_request = $request->getParsedBody();
		$imgdata = $arr_request['imgdata'];
		$filename = $arr_request['filename'];
		if(!$filename) $filename = $clsISO->getUniqid().'.jpg';
		$profile_id = $arr_request['profile_id'];		
		$oneProfile = $clsProfile->getOne($profile_id,"avatar");
		$data = ["result"	=>	false, "avatar"	=>	""];
		if(!empty($oneProfile)){
			if($imgdata){
				$clsUploadFile = new UploadFile();
				$avatar = $clsUploadFile->base642imagejpeg($imgdata, $filename, "/BROKER/avatar");
			}
			if(!empty($avatar) && file_exists(ROOTPATH.$avatar)){
				$old_avatar = $oneProfile['avatar'];
				if(!empty($old_avatar) && @file_exists(ROOTPATH.$old_avatar)){
					@unlink(ROOTPATH.$old_avatar);
				}
				if($clsProfile->updateOne($profile_id, "`avatar`='".addslashes($avatar)."'")){	
					$data = [
						"result"	=>	true,
						"avatar"	=>	$avatar
					];
					
				}
			}
		}
		echo echoResponse('200', $data);die;
	});
?>