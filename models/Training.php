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
class Training extends dbBasic{
	function __construct() {
        $this->pkey = "training_id";
        $this->tbl = DB_PREFIX."training";
    }
    function getTitle($training_id,$one=null) {
		if(!isset($one["title"])) {
			$one = $this->getOne($training_id);	
		}        
        return $one['title'];
    }
	function getLink($training_id,$one=null){
		if(!isset($one["slug"])) {
			$one = $this->getOne($training_id);	
		}        
        return "/khoa-hoc-dao-tao/".$one["slug"]."-t".$training_id.".html";
	}
	function doDelete($pvalTable){
		$this->deleteOne($pvalTable);
		return 1;
	}
	function getProgress($training_id, $oneTraining=null){
		global $profile_id,$clsISO;
		$clsProfile = new Profile();
		$oneProfile = $clsProfile->getOne($profile_id);
		$more_information = $clsISO->to_array_json($oneProfile['more_information']);
		$training = (!empty($more_information["training"])) ? $more_information["training"] : [];
		if(!empty($oneTraining)) {
			$oneTraining = $this->getOne($training_id);	
		}		
		$lstLesson = $clsISO->to_array_json($oneTraining['lesson']);		
		$total_lesson = count($lstLesson);
		$done_ratio = $total_completed = 0;
		if(isset($training[$training_id])){
			$lesson_complete = !empty($training[$training_id]["lesson_complete"]) ? $training[$training_id]["lesson_complete"] : [];
			$lesson_complete = array_keys($lesson_complete);
			$lesson_complete = array_diff_key($lesson_complete,$lstLesson);
			$total_completed = count($lesson_complete);
			if($total_lesson > 0 && $total_completed > 0) {
				$done_ratio = $total_completed * 100 / $total_lesson;
			}
		}
		
		$html = '<div class="progress w-px-75 text-center" data-bs-toggle="tooltip" title="Hoàn thành '.$total_completed."/".$total_lesson.' bài học">
			<div class="progress-bar" style="width:'.$done_ratio.'%;">
				<span>'.$total_completed."/".$total_lesson.'</span>
			</div>
		</div>';
		return $html;
	}
	function checkComplete($training_id,$lesson_id,$oneTraining=null){
		global $oneProfile,$clsISO;
		$more_information = $oneProfile['more_information'];
		$training = (!empty($more_information["training"])) ? $more_information["training"] : [];
		if(!empty($oneTraining)) {
			$oneTraining = $this->getOne($training_id);	
		}
		$lstLesson = $clsISO->to_array_json($oneTraining['lesson']);
		if(isset($training[$training_id])){
			$lesson_complete = !empty($training[$training_id]["lesson_complete"]) ? $training[$training_id]["lesson_complete"] : [];
			if($clsISO->checkItemInArray($lesson_id,array_keys($lesson_complete))) {
				return 1;
			}
		}
		return 0;
	}
	function getYouTubeVideoDuration($videoId, $apiKey) {
		// URL API của YouTube Data API v3
		$url = "https://www.googleapis.com/youtube/v3/videos?id=$videoId&part=contentDetails&key=$apiKey";
		// Gửi yêu cầu HTTP
		$response = file_get_contents($url);
		$data = json_decode($response, true);

		if (isset($data['items'][0]['contentDetails']['duration'])) {
			$duration = $data['items'][0]['contentDetails']['duration'];

			// Chuyển đổi từ định dạng ISO 8601 sang số giây
			$interval = new DateInterval($duration);
			return ($interval->h * 3600) + ($interval->i * 60) + $interval->s;
		}

		return 0; // Nếu không tìm thấy thông tin
	}
	function calculatorTimeTraining($training_id){
		global $clsISO;
		$oneTraining = $this->getOne($training_id);
		$lesson = $clsISO->to_array_json($oneTraining['lesson']);
		$time = 0;
		foreach ($lesson as $k => $v) {
			if(preg_match('/^(http(s)?:\/\/)?((w){3}.)?youtu(be|.be)?(\.com)?\/.+$/', $v['video'])){
				preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $v['video'], $matches);
				$video_id = $matches[1];
				if($video_id != ""){
					$time += $this->getYouTubeVideoDuration($video_id,YOUTUBE_API_KEY);
				}
			}
		}
		$this->updateOne($training_id,["time_training" => floor($time/60)]);
	}
	function getLinkVideo ($data_video){
		global $clsISO;
		if($data_video["type"] == "youtu.be") {
			$link = "https://www.youtube.com/watch?v=".$data_video["youtu_id"];
		}else {
			$link = $clsISO->getIframeUrl($data_video["link"]);
		}
		return $link;
	}
	function checkLiked($training_id, $oDataTable = array()){
		global $core,$dbconn,$_LANG_ID,$clsISO,$profile_id;
		if(!isset($oDataTable['more_information'])){
			$oDataTable = $this->getOne($training_id, "more_information");
		}
		$more_information = $clsISO->to_array_json($oDataTable["more_information"]);
		$lst_liked = !empty($more_information["lst_liked"]) ? $more_information["lst_liked"] : [];
		if(in_array($profile_id, $lst_liked))
			return 1;
		return 0;
	}
	function getSelectOptions($training_id){
		$html = '';
		$field  = "{$this->pkey},`title`";
		$tmp = $this->getAll("`is_trash`=0 AND `is_online`='1' AND `_from`='_admin' order by `reg_date` DESC", $field);
		if(!empty($tmp)){
			foreach($tmp as $key => $val){
				$html.= '<option'.($training_id==$val[$this->pkey]?' selected':'').' value="'.$val[$this->pkey].'">'.$val['title'].'</option>';
			}
		}
		return $html;
	}
}