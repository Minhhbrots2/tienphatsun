<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxCMS IS NOT FREE SOFTWARE ----------------    # ||
|| #################################################################### ||
\*======================================================================*/
class Quiz extends dbBasic{
	function __construct(){
		$this->pkey = "quiz_id";
		$this->tbl = DB_PREFIX."quiz";
	}	
	function getLstIDProfile($list_department_id=null,$list_profile_id=null,$list_group_profile_id=null){
		global $core,$clsISO;
		$clsProfile = new Profile();		
		$clsGroupProfile = new GroupProfile();	
		if(!empty($list_group_profile_id)){
			$arr_group_profile = $clsISO->getArrayByTextSlash($list_group_profile_id);
			
			$arr_profile_group = [];
			$lstGroupProfile = $clsGroupProfile->getAll("`group_profile_id` IN (".implode(',',$arr_group_profile).")");
			foreach ($lstGroupProfile as $key => $value) {
				$arr_profile_group = array_merge($arr_profile_group,$clsISO->getArrayByTextSlash($value['list_profile_id']));
			}
			$arr_profile_group = array_unique($arr_profile_group);
			$list_profile_id .= !empty($arr_profile_group) ? $clsISO->makeSlashListFromArray($arr_profile_group,'|', true) : "";	
		}
		$arr_profile_ids = $clsISO->getArrayByTextSlash($list_profile_id);
		$arr_department_ids = $clsISO->getArrayByTextSlash($list_department_id);
		$cond = "`status_id`<>'"._STATUS_STAFF_OFF_ID."' and `profile_id` not in(".implode(',',_PROFILE_NOTIN_ID).")";
		if(!empty($arr_department_ids) || !empty($arr_profile_ids)){
			$cond.= " and ("; 
			$hasCond = false;
			if(!empty($arr_department_ids)){ $ii = 0;
				$hasCond = true;
				foreach($arr_department_ids as $id){
					$cond.= ($ii==0 ? "": " or ")."(`department_id`='{$id}' 
						or `list_department_id` like '%|{$id}|%')";
					++$ii;
				}
			}
			if(!empty($arr_profile_ids)){
				$cond.= ($hasCond ? " or " : "") . "(`profile_id` in (".implode(',',$arr_profile_ids)."))";
			}
			$cond.= ")";
		}
		$list_staffs = $clsProfile->getAll($cond, $clsProfile->pkey);	
		$array_profile = [];
		foreach($list_staffs as $key => $value){
			$array_profile[] = $value[$clsProfile->pkey];
		}
		return $array_profile;
	}
	function getLink($quiz_id,$one=null){
		if(!isset($one['slug'])) {
			$one = $this->getOne($quiz_id);
		}
		return "/trac-nghiem/".$one['slug']."-q".$quiz_id.".html";
	}
	function getLinkEdit($quiz_id){
		if(!isset($one['slug'])) {
			$one = $this->getOne($quiz_id);
		}
		return "/trac-nghiem/edit/".$quiz_id;
	}
	function getLinkTest($quiz_id){
		global $core;
		
		return "/trac-nghiem/test/".$core->encryptID($quiz_id).".html";
	}
	function getRefName($one){
		switch ($one['quiz_type']) {
			case 'module':
				if (!empty($one['quiz_type_id'])) {
					$clsCat = new QuizTestCategory();
					return $clsCat->getTitle($one['quiz_type_id']);
				}
				break;
			case 'project':
				if (!empty($one['quiz_type_id'])) {
					$clsProject = new Project();
					return $clsProject->getTitle($one['quiz_type_id']);
				}
				break;
			case 'training':
				if (!empty($one['quiz_type_id'])) {
					$clsTraining = new Training();
					return $clsTraining->getTitle($one['quiz_type_id']);
				}
				break;
			case 'video':
				if (!empty($one['link_video']))
					return 'Video';
				break;
		}
		return '-';
	}
	function getEmbedVideo($url, $w="100%", $h='250', $ctrl=0){
		global $core, $dbconn, $clsISO, $deviceType;
		if(preg_match('/^(http(s)?:\/\/)?((w){3}.)?youtu(be|.be)?(\.com)?\/.+$/', $url)){
			preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);
			return '<iframe class="radius-3 mb-2 overflow-hidden"  width="'.$w.'" height="'.$h.'" src="https://www.youtube.com/embed/'.$matches[1].'?rel=0&controls='.$ctrl.'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen oncontextmenu="return false"></iframe>';
		}else if($clsISO->checkContainer($url,"drive.google.com","")){
			$url_image = $clsISO->genGoogleURL($url,"view");
			$url_video = $clsISO->genGoogleURL($url,"video");
			return '<a class="link awe__doc-link overflow-hidden rounded-3" data-preload="false" data-caption="Video" data-fancybox="" href="'.$url_video.'" data-type="iframe">
				<div class="awe__doc-img position-relative">
					<div class="img-play"><i class="bx bx-play"></i></div>				
					<div class="img-background" style="background-image:url(\''.$url_image.'\')"></div>
				</div>
			</a>';
		} else {
			if($clsISO->checkContainer($url, 'images/QuizTest', "")){
				$url = str_replace(FH_URL,'',$url);
				return '<video class="w-100" width="640" height="300" controls >
					<source src="'.$url.'" type="video/mp4">
				</video>';
			}
		}
		return "";
	}
	function getDataColumnQuestion(){
		$data_select = [
			"stt"			=>	"STT",
			"question"			=>	"Câu hỏi",
			"correct"			=>	"Đáp án đúng"
		];
		$alphabet = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
		foreach ($alphabet as $alpha) {
			$data_select[$alpha] = "Phương án ".$alpha;
		}
		return $data_select;
	}
}