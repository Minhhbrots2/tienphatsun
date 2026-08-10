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

class FAQ extends dbBasic{

	function FAQ(){

		global $_LANG_ID;

		$this->pkey = "faq_id";

		$this->tbl = DB_PREFIX."faq";

	}

	function getTitle($pvalTable, $oDataTable = array()){

		if(!isset($oDataTable['title'])){

			$oDataTable = $this->getOne($pvalTable, "title");

		}

		return $oDataTable['title'];

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

	function check_in_alls($faq_id){

		global $core, $dbconn, $clsISO;

		$clsCheckIn = new CheckIn();

		$clsProfile = new Profile();

		

		$cond = "status_id<>'"._STATUS_STAFF_OFF_ID."'";

		$oneFaq = $this->getOne($faq_id, "is_all_staff,list_profile_id,list_department_id");

		if($oneFaq['is_all_staff'] == 0){

			$list_profile_id = $oneFaq['list_profile_id'];

			$list_department_id = $oneFaq['list_department_id'];

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

				if($clsCheckIn->countItem("faq_id='{$faq_id}' and profile_id='{$staff_id}'") == 0){

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

}

?>