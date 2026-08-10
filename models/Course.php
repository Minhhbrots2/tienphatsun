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

class Course extends dbBasic{

	function Course(){

		global $_LANG_ID;

		$this->pkey = "course_id";

		$this->tbl = DB_PREFIX."course";

	}

	function getTitle($pvalTable, $oDataTable = array()){

		if(!isset($oDataTable['title'])){

			$oDataTable = $this->getOne($pvalTable, "title");

		}

		return $oDataTable['title'];

	}

	function getLink($course_id){

		return sprintf('/su-kien/%s.html', $course_id);

	}

	function getField($pvalTable, $field, $oDataTable = array()){

		if(isset($oDataTable[$field])){

			$oDataTable = $this->getOne($pvalTable, $field);

		}

		return $oDataTable[$field];

	}

	function getSlug($pvalTable){

		$one=$this->getOne($pvalTable);

		return $one['slug'];

	}

	function getContent($pvalTable){

		$one=$this->getOne($pvalTable);

		return html_entity_decode($one['content']);

	}

	function getImage($slide_id, $w, $h, $oDataTable = array()){

		global $clsISO;

		if(!isset($oDataTable['image'])){

			$oDataTable = $this->getOne($slide_id, "image");

		}

		$image = $oDataTable['image'];

		if(!empty($image) && @file_exists(ABSPATH . $image)){

			return '/files/thumb/'.$w.'/'.$h.'/'.$clsISO->parseImageURL($image);

		} else {

			$noimage = URL_IMAGES.'/logo-header.png';

			return '/files/thumb/'.$w.'/'.$h.'/'.$clsISO->parseImageURL($noimage);

		}

	}

	function check_in_alls($course_id){

		global $core, $dbconn, $clsISO;

		$clsCheckIn = new CheckIn();

		$clsProfile = new Profile();

		

		$cond = "status_id<>'"._STATUS_STAFF_OFF_ID."'";

		$oneCourse = $this->getOne($course_id, "is_all_staff,list_profile_id,list_department_id");

		if($oneCourse['is_all_staff'] == 0){

			$list_profile_id = $oneCourse['list_profile_id'];

			$list_department_id = $oneCourse['list_department_id'];

			$arr_profile_ids = !empty($list_profile_id) 

				? $clsISO->getArrayByTextSlash($list_profile_id) : array();

			$arr_department_ids = !empty($list_department_id) 

				? $clsISO->getArrayByTextSlash($list_department_id) : array();

			if(!empty($arr_department_ids) || !empty($arr_profile_ids)){

				$cond.= " and ("; $hasCond = false;

				if(!empty($arr_department_ids)){ $ii = 0;

					$hasCond = true;

					foreach($arr_department_ids as $id){

						$cond.= ($ii==0 ? "": " or ")."(`department_id`='{$id}' or `list_department_id` like '%|{$id}|%')";

						++$ii;

					}

				}

				if(!empty($arr_profile_ids)){

					$cond.= ($hasCond ? " or " : "") . "(`profile_id` in (".implode(',',$arr_profile_ids)."))";

				}

				$cond.= ")";

			}

		}

		$check_all = 1;

		$field = "{$clsProfile->pkey}";

		$list_staffs = $clsProfile->getAll($cond, $field);	

		if(!empty($list_staffs)){

			foreach($list_staffs as $key => $val){

				$staff_id = $val[$clsProfile->pkey];

				if($clsCheckIn->countItem("course_id='{$course_id}' and profile_id='{$staff_id}'") == 0){

					$check_all = 0;

					break;

				}

			}

		}

		return $check_all;

	}

	function doDelete($pvalTable){

		$this->deleteOne($pvalTable);

		return 1;

	}

	function getTimeStartCourse($time){

		global $core;

		$dayOfWeek = date("l",$time);

		$dayOfWeek = $core->get_Lang($dayOfWeek);

		$month = date("F",$time);

		$month = $core->get_Lang($month);

		$day = date("d",$time);

		$hour = date("H:i",$time);

		return $dayOfWeek.", ".$day." ".$month." lúc ".$hour;

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

		$arr_profile_ids = !empty($list_profile_id) 

			? $clsISO->getArrayByTextSlash($list_profile_id) : array();

		$arr_department_ids = !empty($list_department_id) 

			? $clsISO->getArrayByTextSlash($list_department_id) : array();

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

}

?>