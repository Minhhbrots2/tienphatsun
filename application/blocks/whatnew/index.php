<?php 
	global $core, $clsISO, $smarty, $dbconn, $profile_id;
	$clsNews = new News();
	$smarty->assign('clsNews', $clsNews);
	$one_notify = $clsNews->getByCond("`is_trash`=0 and `is_online`=1 and `post_type`='_whatnew' and FROM_UNIXTIME(`start_date`,'%d/%m/%Y')='".date('d/m/Y')."' and (JSON_EXTRACT(`more_information`,'$.show_website')='all' or JSON_EXTRACT(`more_information`,'$.show_website')='user.fh')");
	// $clsISO->print_pre($one_notify); die();
	if(!empty($one_notify)){
		$images = $one_notify['images'];
		$more_information = $one_notify['more_information'];
		$list_images = $clsISO->to_array_json($images);
		$more_information = $clsISO->to_array_json($more_information);
		if(!empty($list_images)){
			foreach($list_images as $key => $img){
				$one_notify['image'] = $img;
			}
		}
		$list_user_view_id = isset($more_information['list_user_view_id']) 
			? $more_information['list_user_view_id'] : array();
		if(!in_array($profile_id, $list_user_view_id)){
			$list_user_view_id[] = $profile_id;
			$more_information['list_user_view_id'] = $list_user_view_id;
			$clsNews->updateOne($one_notify[$clsNews->pkey], array(
				'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
			));
		} else {
			$one_notify = array();
		}
	}
	$smarty->assign('one_notify', $one_notify);
?>