<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| #################################################################### ||
\*======================================================================*/
class Overtime extends dbBasic{
	function Overtime(){
		$this->pkey = "overtime_id";
		$this->tbl = DB_PREFIX."overtime";
	}
	function genCode(){
		$total_record = $this->countItem("`is_trash`=0");
		$total_record += 1;
		if($total_record<10) 
			return sprintf('TC0000%s', $total_record);
		if($total_record >= 10 && $total_record<100) 
			return sprintf('TC000%s', $total_record);
		if($total_record >= 100 && $total_record<1000) 
			return sprintf('TC00%s', $total_record);
		if($total_record >= 1000 && $total_record<10000) 
			return sprintf('TC0%s', $total_record);
		if($total_record >= 10000 && $total_record<100000) 
			return sprintf('TC%s', $total_record);
	}
	function getStatus($status_id, $oDataTable = array()){
		$clsProperty = new Property();
		if(empty($oDataTable)){
			$field = "title,bgcolor,textcolor";
			$oDataTable = $clsProperty->getOne($status_id, $field);
		}
		return sprintf('<span style="color:%s">%s</span>', $oDataTable['textcolor'], $oDataTable['title']);
	}
	function getProgress($issue_id, $oDataTable=array()){
		if(!isset($oDataTable['done_ratio']) || !isset($oDataTable['status_id'])){
			$oDataTable = $this->getOne($issue_id,"done_ratio,status_id");
		}
		$status_id = $oDataTable['status_id'];
		$done_ratio = $oDataTable['done_ratio'];
		if($status_id==ISSUE_COMPLETED_ID){
			$done_ratio = 100;
		}
		$html = '<div class="progress w-px-75 text-center">
			<div class="progress-bar" style="width:'.$done_ratio.'%;">
				<span>'.$done_ratio.'%</span>
			</div>
		</div>';
		return $html;
	}
	public function getPriority($priority_id, $label=true){
		global $core, $dbconn, $clsISO;
		$clsProperty = new Property();
		$oProperty = $clsProperty->getOne($priority_id, "title,image");
		$text= ($label==true) ? $oProperty['title']: '';
		return '<span style="width:100px;">
			 <img src="'.$oProperty['image'].'" width="16" height="16" align="absmiddle" title="'.$oProperty['title'].'"> '.$text.'
		</span>';
	}
	function getHour($start_date, $due_date){
		return floor(abs($due_date - $start_date)/3600);
	}
	function getScore($profile_id, $current_year){
		$score = 0;
		$field = "{$this->pkey},`start_date`,`due_date`,`time_off`,`is_fullday`";
		$list_items = $this->getAll("`is_trash`=0 and `is_confirmed`=1 and `profile_id`='{$profile_id}' and FROM_UNIXTIME(`regis_date`,'%Y')='".$current_year."' order by `regis_date` ASC", $field);
		if(!empty($list_items)){
			foreach($list_items as $key => $val){
				if(isset($val['is_fullday']) && $val['is_fullday'] == 1){
					$score += _SCORE_OVERTIME_FULLDAY;
				} else {
					$start_date = $val['start_date'];
					$due_date = $val['due_date'];
					$hour = $this->getHour($start_date, $due_date);
					if($hour >= 2){
						$score += 1;
					} else if($hour >= 1 && $hour < 2){
						$score += 0.5;
					}
				}
			}
		}
		return $score;
	}
	function checkHaveAction($overtime_id, $action = "approve", $oDataTable = array()){
		global $core, $clsISO, $profile_id;
		$list_approver_id = $oDataTable['list_approver_id'];
		$list_confirmed_id = $oDataTable['list_confirmed_id'];
		$list_approver_id = !empty($list_approver_id) 
			? $clsISO->getArrayByTextSlash($list_approver_id) : array();
		$list_confirmed_id = !empty($list_confirmed_id) 
			? $clsISO->getArrayByTextSlash($list_confirmed_id) : array();
		if($action == 'confirmed'){
			return !empty($list_confirmed_id) && @in_array($profile_id, $list_confirmed_id) ? 1 : 0;
		} else {
			return !empty($list_approver_id) && @in_array($profile_id, $list_approver_id) ? 1 : 0;
		}
	}
}