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
class QuizAnswers extends dbBasic{
	function __construct(){
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."quiz_answers";
	}	
	public function countTime($seconds){
		global $adminid,$core,$clsISO;
		return gmdate ('H:i:s', $seconds);
	}
	
	public function getOfficeTime($start,$end){
		$retVal = $end-$start;
		$extraTime = 0;
		return $retVal-$extraTime;
	}
	public function getTimeDoInSecond($quiz_id){
		global $core, $dbconn;
		$clsQuiz = new Quiz();
		$time = 0;
		$clsIssueCurrent = new IssueCurrent();
		$all = $clsQuiz->getAll("issue_id='{$issue_id}' order by start_date asc", "start_date,due_date");
		if(!empty($all)){
			foreach($all as $item){
				if($item['due_date']=='0'){
					$end = time();
				}else{
					$end = $item['due_date'];
				}
				$time += $this->getOfficeTime($item['start_date'],$end);
			}
			unset($all);
		}
		return $time;
	}
	function getTimeDoingTotal($quiz_id){
		$totalTime = $this->getTimeDoInSecond($quiz_id);
		$allChild = $this->getAll("is_trash=0 and parent_id='{$issue_id}' order by issue_id asc", $this->pkey);
		if(!empty($allChild)){
			foreach($allChild as $issue){
				$totalTime += $this->getTimeDoingTotal($issue['issue_id']);
			}
			unset($allChild);
		}
		return $totalTime;
	}
	function getTimeDo($issue_id){
		$one = $this->getOne($id);
		return $this->countTime($this->getTimeDoingTotal($one["quiz_id"])); 
	}
}