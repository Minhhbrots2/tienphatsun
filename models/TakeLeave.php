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
class TakeLeave extends DbBasic{  
	public function __construct(){
		$this->pkey = "takeleave_id";
		$this->tbl = DB_PREFIX."takeleave";
	}
	function getNumberTakeleaveCurrent($user_id,$start_date=null){
		global $core,$clsISO,$dbconn,$profile_id,$oneProfile;
		$clsProfile = new Profile();
		if($user_id==$profile_id){
			$official_member = $oneProfile['official_member'];
		}else{
			$official_member = $clsProfile->getOneField("official_member",$user_id);
		}
		if(empty($official_member))	return 0;
		if(empty($start_date)) $start_date=date("Y-m-d");
		$start_year = $clsISO->getDateCreateFormat($start_date,"Y");
		$start_month = $clsISO->getDateCreateFormat($start_date,"n");//month no zero
		//return $start_month;
		//$start_year = 2021;
		if($start_month>_MONTH_TAKE_LEAVE_RESET){
			$number_takeleave_current = 0;
			if($start_year == _YEAR_TAKE_LEAVE_CURRENT){
				if($user_id==$profile_id){
					$number_takeleave_current = $oneProfile['number_takeleave_current'];
				}else{
					$number_takeleave_current = $clsProfile->getOneField("number_takeleave_current",$user_id);
				}
			}
			$number_takeleave_current += $dbconn->getOne("Select sum(number_day) from {$this->tbl} where is_trash=0 and is_paid_leave=1 and is_approved=1 and user_id={$user_id} and DATE_FORMAT(start_date,'%Y')='{$start_year}' and this_year=1");
		}else{
			$last_year = $start_year-1;
			$number_takeleave_last_year = $this->getNumberTakeleaveCurrent($user_id,$last_year."-12-31");
			$number_takeleave_this_year = $dbconn->getOne("Select sum(number_day) from {$this->tbl} where is_trash=0 and is_paid_leave=1 and is_approved=1 and user_id={$user_id} and DATE_FORMAT(start_date,'%Y')='{$start_year}'");
			$number_takeleave_current = $number_takeleave_last_year + $number_takeleave_this_year;
			//$number_takeleave_current = $number_takeleave_last_year + $start_month-1;
		}
		return $number_takeleave_current;
	}
	function getNumberTakeleaveValid($user_id,$start_date=null){
		global $core,$clsISO,$dbconn,$profile_id,$oneProfile;
		$clsProfile = new Profile();
		$clsConfiguration = new Configuration();
		if($user_id == $profile_id){
			$join_date = $oneProfile['start_date'];
		}else{
			$join_date = $clsProfile->getOneField("start_date",$user_id);
		}
		if(empty($join_date)) return 0;
		$join_date_year = $clsISO->getDateCreateFormat($join_date,"Y");
		$join_date_month = $clsISO->getDateCreateFormat($join_date,"n");//month no zero
		$join_date_day = $clsISO->getDateCreateFormat($join_date,"j");////day no zero
		if(!empty($start_date)){
			$now_year = $clsISO->getDateCreateFormat($start_date,"Y");
			$now_month = $clsISO->getDateCreateFormat($start_date,"n");//month no zero
			$now_day = $clsISO->getDateCreateFormat($start_date,"j");//day no zero
		}else{
			$now_year = date("Y");
			$now_month = date("n");//month no zero	
			$now_day = date("j");//day no zero	
		}
		$takeleave_configs = $clsConfiguration->getValue('takeleave_configs');
		$takeleave_configs = $clsISO->to_array_json($takeleave_configs);
		$leave_days_per_month = isset($takeleave_configs['leave_days_per_month']) 
			? (int) $takeleave_configs['leave_days_per_month'] : 1;
		if($join_date_year > $now_year){//perfect
			return 0;
		}elseif($join_date_year==$now_year){// have SALARY_FIRST
			if($now_month<=$join_date_month){//perfect
				return 0;
			}else{
				$_valid = $now_month - $join_date_month;
				if(_CLOSE_DAY_SALARY==1){
					$_valid--;
				}else{
					if($now_day<_CLOSE_DAY_SALARY){
						$_valid--;
					}
				}
				if(_CLOSE_DAY_SALARY>20 && ($join_date_day+_TOTAL_DAY_SALARY_FIRST)<=_CLOSE_DAY_SALARY){
					$_valid++;
				}
				if($_valid<0) $_valid = 0;
				return $_valid * $leave_days_per_month;
			}
		}else{//$join_date_year < $now_year
			if($now_year <= date("Y")){
				$_valid = $now_month;
				if(_CLOSE_DAY_SALARY==1){
					// $_valid--;
				}else{
					if($now_day<_CLOSE_DAY_SALARY){
						//$_valid--;
					}
				}
				if($_valid<0) $_valid = 0;
				return $_valid * $leave_days_per_month;
			}else{
				return 0;
			}
		}
	}
	function getNumberNoPaidLeave($user_id,$start_date=""){
		global $core,$clsISO,$dbconn,$adminid;
		$clsProfile = new Profile();
		if(empty($start_date)) $start_date = date("Y-m-d");
		$start_year = $clsISO->getDateCreateFormat($start_date,"Y");
		$number_NoPaidLeave = $clsProfile->getNumberDayNoPaidleave($user_id, $start_year);
		$number_NoPaidLeave+= $dbconn->getOne("select sum(number_day_no_paid_leave) from {$this->tbl} 
			where `is_trash`=0 and `is_approved`=1 and `user_id`={$user_id} and DATE_FORMAT(`start_date`,'%Y')='{$start_year}'");
		return !empty($number_NoPaidLeave) ? $number_NoPaidLeave : 0;
	}
	function getInfoTakeleave($user_id,$start_date="",$tp='text'){
		global $core,$clsISO,$dbconn,$profile_id,$clsConfiguration;
		$clsProfile = new Profile();
		if(empty($start_date)) $start_date = date("Y-m-d");
		$start_year = $clsISO->getDateCreateFormat($start_date,"Y");
		$start_month = $clsISO->getDateCreateFormat($start_date,"n");//month no zero
		$takeleave_configs = $clsConfiguration->getValue('takeleave_configs');
		$takeleave_configs = $clsISO->to_array_json($takeleave_configs);
		// return $start_month;
		$info_takeleave = '';
		$takeleave_valid = array();
		if($start_month>_MONTH_TAKE_LEAVE_RESET){
			$next_year = $start_year+1;
			$number_takeleave_current = $clsProfile->getNumberDayTakeleave($user_id,$start_year);
			$number_takeleave_current+= $dbconn->getOne("select sum(number_day_paid_leave_this_year) from {$this->tbl} 
				where is_trash=0 and is_approved=1 and user_id={$user_id} and DATE_FORMAT(start_date,'%Y')='{$start_year}'");
			$number_takeleave_current+= $dbconn->getOne("select sum(number_day_paid_leave_last_year) from {$this->tbl} 
				where is_trash=0 and is_approved=1 and user_id={$user_id} and DATE_FORMAT(start_date,'%Y')='{$next_year}'");
			$number_takeleave_valid = $this->getNumberTakeleaveValid($user_id,$start_date);
			$info_takeleave = '<span class="rate-avg font-16 bold">
				'.$number_takeleave_current.'</span> / <span class="rate-avg font-16 bold">'.$number_takeleave_valid.'</span>';
			$takeleave_valid['takeleave_valid_this_year'] = $number_takeleave_valid-$number_takeleave_current;
		}else{
			if((int) $takeleave_configs['is_leave_carryover'] == 1){
				$last_year = $start_year-1;
				$number_takeleave_last_year = $clsProfile->getNumberDayTakeleave($user_id, $last_year);
				$number_takeleave_last_year+= $dbconn->getOne("select sum(number_day_paid_leave_this_year) from {$this->tbl} 
					where is_trash=0 and is_approved=1 and user_id={$user_id} and DATE_FORMAT(start_date,'%Y')='{$last_year}'");
				$number_takeleave_last_year+= $dbconn->getOne("select sum(number_day_paid_leave_last_year) from {$this->tbl} 
					where is_trash=0 and is_approved=1 and user_id={$user_id} and DATE_FORMAT(start_date,'%Y')='{$start_year}'");
				$number_takeleave_valid_last_year = $this->getNumberTakeleaveValid($user_id,$last_year."-12-31");
				//$number_takeleave_valid_last_year ++;// + ngày nghỉ của tháng 12
				$info_takeleave .= '<p>Năm trước: <span class="rate-avg font-16 fw-bold">'.$number_takeleave_last_year.'</span> / <span class="rate-avg font-16 bold">'.$number_takeleave_valid_last_year.'</span></p>';
				$takeleave_valid['takeleave_valid_last_year'] = $number_takeleave_valid_last_year-$number_takeleave_last_year;
			}
			#
			$number_takeleave_current = $clsProfile->getNumberDayTakeleave($user_id,$start_year);
			$number_takeleave_current+= $dbconn->getOne("SELECT SUM(`number_day_paid_leave_this_year`) FROM {$this->tbl} 
				WHERE `is_trash=0 AND `is_approved`=1 AND `user_id`={$user_id} AND DATE_FORMAT(`start_date`,'%Y')='{$start_year}'");
			$number_takeleave_valid = $this->getNumberTakeleaveValid($user_id, $start_date);
			$info_takeleave .= '<p>Năm nay: <span class="rate-avg font-16 fw-bold">'.$number_takeleave_current.'</span> / <span class="rate-avg font-16 fw-bold">'.$number_takeleave_valid.'</span></p>';
			$takeleave_valid['takeleave_valid_this_year'] = $number_takeleave_valid-$number_takeleave_current;
		}
		if($tp=='text')
			return $info_takeleave;
		return $takeleave_valid;
	}
	function getLinkApproval($takeleave_id,$tp){
		global $clsISO;
		//$tp : curator,director_of_dep,head_of_dep,hrad,director
		return PCMS_URL.'/take-leave/'.$tp.'-approval/'.$clsISO->encryptID($takeleave_id);
	}
	function getLinkApprovalInList($takeleave_id, $oneTakeLeave=array()){
		global $core,$clsISO,$dbconn,$profile_id,$oneProfile;
		$department_id = $oneProfile['department_id'];
		if(!isset($oneTakeLeave['info_leave'])){
			$oneTakeLeave = $this->getOne($takeleave_id,"info_leave");
		}
		$info_leave = !empty($oneTakeLeave['info_leave']) 
			? json_decode(html_entity_decode($oneTakeLeave['info_leave']), true) : array();
		$lstApproval = !empty($info_leave['lstApproval']) ? $info_leave['lstApproval']: array();
		$info_approval = !empty($info_leave['approval']) ? $info_leave['approval']: array();
		
		$html_links = '';
		if(!empty($lstApproval)){
			foreach($lstApproval as $tp => $user_id){
				$color_check = $info_approval[$tp]['is_approval']==1?'color-green':'';
				if($tp=='curator'){
					$tp_title = 'Người phụ trách';
					$tp_text = 'Phụ trách duyệt';
				} elseif($tp=='head_of_dep'){
					$tp_title = 'Trưởng phòng';
					$tp_text = 'TP duyệt';
				}  elseif($tp=='director_of_dep'){
					$clsProfile = new Profile();
					$clsProperty = new Property();
					$department_id = $clsProfile->getOneField('department_id', $user_id);
					$department_name = $clsProperty->getTitle($department_id);
					$tp_title = sprintf('Giám đốc %', $department_name);
					$tp_text = sprintf('Giám đốc %s duyệt', $department_name);
				} elseif($tp=='hrad'){
					$tp_title = 'Hành chính nhân sự';
					$tp_text = 'HCNS duyệt';
				}
				if($user_id==$profile_id){
					$html_links .='<li><a href="javascript:void(0);" class="dropdown-item" onClick="approval_takeleave(this, event)" tp="'.$tp.'" takeleave_id="'.$takeleave_id.'" title="'.$tp_title.' xét duyệt">'.$core->makeIcon('check', $tp_text).$profile_id.'</a></li>';
				}
			}
		}
		if($department_id==_DEPARTMENT_DIRECTOR_ID){
			$html_links.= '<li><a href="javascript:void(0);" class="dropdown-item" onClick="approval_takeleave(this, event)" takeleave_id="'.$takeleave_id.'" tp="director" title="Giám đốc xét duyệt">'.$core->makeIcon('check', 'BGĐ duyệt').'</a></li>';
		}
		return $html_links;
	}
	function checkApprovalBeforeDirector($takeleave_id, $oneTakeLeave = array()){
		if(!isset($oneTakeLeave['info_leave']) || !isset($oneTakeLeave['number_day'])){
			$oneTakeLeave = $this->getOne($takeleave_id,"info_leave,number_day");
		}
		if($oneTakeLeave['number_day']<3){
			return 0;
		} else {
			$info_leave = !empty($oneTakeLeave['info_leave']) ? json_decode($oneTakeLeave['info_leave'],true) : array();
			$lstApproval = isset($info_leave['lstApproval']) ? $info_leave['lstApproval'] : array() ;
			$info_approval = isset($info_leave['approval']) ? $info_leave['approval'] : array();
			
			$is_approval = 1;
			if(!empty($lstApproval)){
				foreach($lstApproval as $tp => $user_id){
					if(empty($info_approval[$tp]) || $info_approval[$tp]['is_approval']==0){
						$is_approval = 0;
						break;
					}
				}
			}
			return $is_approval;
		}
	}
	function checkEdit($takeleave_id,$oneTakeLeave=null){
		if(!isset($oneTakeLeave['info_leave'])){
			$oneTakeLeave = $this->getOne($takeleave_id,"info_leave");
		}
		$info_leave = json_decode($oneTakeLeave['info_leave'],true);
		
		$is_edit = 1;
		$lstApproval = $info_leave['lstApproval'];
		$info_approval = $info_leave['approval'];
		if(!empty($lstApproval)){
			foreach($lstApproval as $tp=>$user_id){
				if(!empty($info_approval[$tp])){//&&$info_approval[$tp]['is_approval']==1
					$is_edit = 0;
					break;
				}
			}
		}
		return $is_edit;
	}
	function checkDel($takeleave_id,$oneTakeLeave=null){
		global $adminid,$clsISO;
		if(in_array($adminid,array(1,31))) return 1;//USER_ID_HCNS
		$LIST_USER_ID_HCNS = $clsISO->getVar('LIST_USER_ID_HCNS');
		$LIST_USER_ID_HCNS = json_decode($LIST_USER_ID_HCNS,true);
		if(in_array($adminid,$LIST_USER_ID_HCNS)) return 1;
		return $this->checkEdit($takeleave_id,$oneTakeLeave);
	}
	function getInfoApproval($takeleave_id, $oneTakeLeave=array()){
		$clsISO = new ISO();
		$clsProfile = new Profile();
		$clsProperty = new Property();
		if(!isset($oneTakeLeave['leave_approver'])){
			$oneTakeLeave = $this->getOne($takeleave_id,"leave_approver");
		}
		$user_id = $oneTakeLeave['user_id'];
		$oneProfile = $clsProfile->getOne($user_id);
		$department_id = !empty($oneProfile['department_id']) 
			? (int) $oneProfile['department_id'] : 0;
		$leave_approver = $oneTakeLeave['leave_approver'];
		$leave_approver_arrs = $clsISO->to_array_json($leave_approver);
		$infoApproval = '<div class="pipeline-small flat">';
		if(!empty($leave_approver_arrs)){
			foreach($leave_approver_arrs as $key => $val){
				if($key=='curator'){
					$tp_title = 'Người phụ trách';
				} elseif($key=='head_of_dep'){
					$tp_title = 'Trưởng phòng';
				} elseif($key=='director_of_dep'){
					$tp_title = 'Giám đốc '.$clsProperty->getTitle($department_id);
				} elseif($key=='hrad'){
					$tp_title = 'Hành chính nhân sự';
				} else{
					$tp_title = 'Ban Giám Đốc';
				}
				if($val['approval_status']==0){
					$infoApproval .= '<a title="'.$tp_title.' chưa duyệt" href="#" class="noselect tipped-top">&nbsp;</a>';
				} elseif ($val['approval_status'] == 1) {
					$infoApproval .= '<a title="'.$tp_title.' đã duyệt" href="#" class="active noselect tipped-top">&nbsp;</a>';
				} elseif ($val['approval_status'] == 2) {
					$infoApproval .= '<a title="'.$tp_title.' không duyệt" href="#" class="noselect tipped-top">&nbsp;</a>';
				}
			}
		}
		$infoApproval .= '</div>';
		return $infoApproval;
	}
	function getTakeLeaveNoApproval(){
		global $profile_id;
		return $this->countItem("`is_trash`=0 and `is_approved`=0 and `user_id`={$profile_id} and DATE_FORMAT(`start_date`,'%Y')='".date("Y")."'");
	}
	function getTakeLeaveApproval(){
		global $profile_id;
		return $this->countItem("`is_trash`=0 and `is_approved`=1 and `user_id`={$profile_id} and DATE_FORMAT(`start_date`,'%Y')='".date("Y")."'");
	}
	function getTotalTakeLeaveApproval(){
		global $profile_id;
		$totalApproval = $this->getTakeLeaveApproval();
		$totalNoApproval = $this->getTakeLeaveNoApproval();
		return '<div class="pretty font-18 p-jelly p-icon mr-0 gotoLink" title="Đã duyệt" link="'.PCMS_URL.'/take-leave-staff-approval/">
			<input type="checkbox" checked class="preventDefault">
			<div class="state p-success">
				<i class="icon material-icons">done</i>
				<label class="bold">'.$totalApproval.'</label>
			</div>
		</div>
		<div class="pretty font-18 p-jelly p-icon mr-0 gotoLink" title="Không duyệt" link="'.PCMS_URL.'/take-leave-staff-not-approval/">
			<input type="checkbox" checked class="preventDefault">
			<div class="state p-danger">
				<i class="icon material-icons">clear</i>
				<label class="bold">'.$totalNoApproval.'</label>
			</div>
		</div>';
	}
	function getTotalTakeLeave(){
		global $profile_id;
		$total =  $this->countItem("is_trash=0 and user_id={$profile_id} and DATE_FORMAT(start_date,'%Y')='".date("Y")."'");
		return '<a class="gotoLink" link="'.PCMS_URL.'/take-leave-staff/"><strong>Cá nhân: '.$total.'</strong></a>';
	}
	function getTotalTakeLeaveCurator(){
		global $profile_id;
		$total = $this->countItem("is_trash=0 and curator_user_id={$profile_id} and DATE_FORMAT(start_date,'%Y')='".date("Y")."'");
		return '<a class="gotoLink" link="'.PCMS_URL.'/take-leave-curator/"><strong>Phụ trách: '.$total.'</strong></a>';
	}
	function getTotalTakeLeaveCuratorApproval(){
		global $core,$clsISO,$dbconn,$profile_id;
		$curatorApproval = $curatorNotApproval = $curatorUnApproved = 0;
		$tmp = $this->getAll("is_trash=0 and curator_user_id={$profile_id} and DATE_FORMAT(start_date,'%Y')='".date("Y")."'","info_leave");
		if(!empty($tmp)){
			foreach($tmp as $k=>$oneTakeLeave){
				$info_leave = json_decode($oneTakeLeave['info_leave'],true);
				$lstApproval = $info_leave['lstApproval'];
				$info_approval = $info_leave['approval'];
				if(!empty($lstApproval)){
					foreach($lstApproval as $tp=>$user_id){
						if($tp=='curator' && $user_id==$profile_id){
							if(empty($info_approval[$tp])){
								$curatorUnApproved ++;
							}else{
								if($info_approval[$tp]['is_approval']==1){
									$curatorApproval ++;
								}else{
									$curatorNotApproval ++;
								}
							}
						}
					}
				}
			}
		}
		return '<div class="pretty font-18 p-jelly p-icon mr-0 gotoLink" title="Đã duyệt" link="'.PCMS_URL.'/take-leave-curator-approval/">
			<input type="checkbox" checked class="preventDefault">
			<div class="state p-success">
				<i class="icon material-icons">done</i>
				<label class="bold">'.$curatorApproval.'</label>
			</div>
		</div>
		<div class="pretty font-18 p-jelly p-icon mr-0 gotoLink" title="Không duyệt" link="'.PCMS_URL.'/take-leave-curator-not-approval/">
			<input type="checkbox" checked class="preventDefault">
			<div class="state p-danger">
				<i class="icon material-icons">clear</i>
				<label class="bold">'.$curatorNotApproval.'</label>
			</div>
		</div>
		<div class="pretty font-18 p-jelly p-icon mr-0 gotoLink" title="Chưa duyệt" link="'.PCMS_URL.'/take-leave-curator-unapproved/">
			<input type="checkbox" class="preventDefault">
			<div class="state p-danger">
				<i class="icon material-icons">done</i>
				<label class="bold">'.$curatorUnApproved.'</label>
			</div>
		</div>';
	}
	function getTotalTakeLeaveCeo(){
		global $adminid;
		$total = $this->countItem("is_trash=0 and DATE_FORMAT(start_date,'%Y')='".date("Y")."'");
		return '<a class="gotoLink" link="'.PCMS_URL.'/take-leave-director/"><strong>Xét duyệt: '.$total.'</strong></a>';
	}
	function getTotalTakeLeaveCeoApproval(){
		global $core,$clsISO,$dbconn,$adminid;
		$tp = 'director';
		$ceoApproval = $ceoNotApproval = $ceoUnApproved = 0;
		$tmp = $this->getAll("is_trash=0 and DATE_FORMAT(start_date,'%Y')='".date("Y")."'","info_leave");
		if(!empty($tmp)){
			foreach($tmp as $k=>$oneTakeLeave){
				$info_leave = json_decode($oneTakeLeave['info_leave'],true);
				$info_approval = $info_leave['approval'];
				if(empty($info_approval[$tp])){
					$ceoUnApproved ++;
				}else{
					if($info_approval[$tp]['is_approval']==1){
						$ceoApproval ++;
					}else{
						$ceoNotApproval ++;
					}
				}
			}
		}
		return '<div class="pretty font-18 p-jelly p-icon mr-0 gotoLink" title="Đã duyệt" link="'.PCMS_URL.'/take-leave-director-approval/">
				<input type="checkbox" checked class="preventDefault">
				<div class="state p-success">
					<i class="icon material-icons">done</i>
					<label class="bold">'.$ceoApproval.'</label>
				</div>
			</div>
			<div class="pretty font-18 p-jelly p-icon mr-0 gotoLink" title="Không duyệt" link="'.PCMS_URL.'/take-leave-director-not-approval/">
				<input type="checkbox" checked class="preventDefault">
				<div class="state p-danger">
					<i class="icon material-icons">clear</i>
					<label class="bold">'.$ceoNotApproval.'</label>
				</div>
			</div>
			<div class="pretty font-18 p-jelly p-icon mr-0 gotoLink" title="Chưa duyệt" link="'.PCMS_URL.'/take-leave-director-unapproved/">
				<input type="checkbox" class="preventDefault">
				<div class="state p-danger">
					<i class="icon material-icons">done</i>
					<label class="bold">'.$ceoUnApproved.'</label>
				</div>
			</div>';
	}
	function getTotalTakeLeaveHrad(){
		global $adminid;
		$total = $this->countItem("is_trash=0 and DATE_FORMAT(start_date,'%Y')='".date("Y")."'");
		return '<a class="gotoLink" link="'.PCMS_URL.'/take-leave-hrad/"><strong>Xét duyệt: '.$total.'</strong></a>';
	}
	function getTotalTakeLeaveHradApproval(){
		global $core,$clsISO,$dbconn,$adminid;
		$tp = 'hrad';
		$hradApproval = $hradNotApproval = $hradUnApproved = 0;
		$tmp = $this->getAll("is_trash=0 and DATE_FORMAT(start_date,'%Y')='".date("Y")."'","info_leave");
		if(!empty($tmp)){
			foreach($tmp as $k=>$oneTakeLeave){
				$info_leave = json_decode($oneTakeLeave['info_leave'],true);
				$info_approval = $info_leave['approval'];
				if(empty($info_approval[$tp])){
					$hradUnApproved ++;
				}else{
					if($info_approval[$tp]['is_approval']==1){
						$hradApproval ++;
					}else{
						$hradNotApproval ++;
					}
				}
			}
		}
		return '<div class="pretty font-18 p-jelly p-icon mr-0 gotoLink" title="Đã duyệt" link="'.PCMS_URL.'/take-leave-hrad-approval/">
			<input type="checkbox" checked class="preventDefault">
			<div class="state p-success">
				<i class="icon material-icons">done</i>
				<label class="bold">'.$hradApproval.'</label>
			</div>
		</div>
		<div class="pretty font-18 p-jelly p-icon mr-0 gotoLink" title="Không duyệt" link="'.PCMS_URL.'/take-leave-hrad-not-approval/">
			<input type="checkbox" checked class="preventDefault">
			<div class="state p-danger">
				<i class="icon material-icons">clear</i>
				<label class="bold">'.$hradNotApproval.'</label>
			</div>
		</div>
		<div class="pretty font-18 p-jelly p-icon mr-0 gotoLink" title="Chưa duyệt" link="'.PCMS_URL.'/take-leave-hrad-unapproved/">
			<input type="checkbox" class="preventDefault">
			<div class="state p-danger">
				<i class="icon material-icons">done</i>
				<label class="bold">'.$hradUnApproved.'</label>
			</div>
		</div>';
	}
	function getTotalTakeLeaveLeader(){
		global $core,$profile_id,$oneProfile,$dbconn;
		$clsProfile = new Profile();
		$cond = "is_trash = 0";
		$department_id = $oneProfile['department_id'];
		$lstUser = $dbconn->getCol("select `profile_id` from {$clsProfile->tbl} where department_id={$department_id}");
		$cond .= " and user_id in (".implode(',',$lstUser).")";
		$total = $this->countItem("{$cond} and DATE_FORMAT(start_date,'%Y')='".date("Y")."'");
		return '<a class="gotoLink" link="'.PCMS_URL.'/take-leave-head_of_dep/"><strong>Xét duyệt: '.$total.'</strong></a>';
	}
	function getTotalTakeLeaveLeaderApproval(){
		global $core,$clsISO,$dbconn,$profile_id,$oneProfile;
		$clsProfile = new Profile();
		$cond = "is_trash = 0";
		$department_id = $oneProfile['department_id'];
		$lstUser = $dbconn->getCol("select {$clsProfile->pkey} from {$clsProfile->tbl} where department_id={$department_id}");
		$cond.= " and user_id in (".implode(',',$lstUser).")";
		$tmp = $this->getAll("{$cond} and DATE_FORMAT(start_date,'%Y')='".date("Y")."'","info_leave");
		$leaderApproval = $leaderNotApproval = $leaderUnApproved = 0;
		if(!empty($tmp)){
			foreach($tmp as $k=>$oneTakeLeave){
				$info_leave = json_decode($oneTakeLeave['info_leave'],true);
				$lstApproval = $info_leave['lstApproval'];
				$info_approval = $info_leave['approval'];
				if(!empty($lstApproval)){
					foreach($lstApproval as $tp=>$user_id){
						if($tp=='head_of_dep' && $user_id==$profile_id){
							if(empty($info_approval[$tp])){
								$leaderUnApproved ++;
							}else{
								if($info_approval[$tp]['is_approval']==1){
									$leaderApproval ++;
								}else{
									$leaderNotApproval ++;
								}
							}
						}
					}
				}
			}
		}
		return '<div class="pretty font-18 p-jelly p-icon mr-0 gotoLink" title="Đã duyệt" link="'.PCMS_URL.'/take-leave-head_of_dep-approval/">
			<input type="checkbox" checked class="preventDefault">
			<div class="state p-success">
				<i class="icon material-icons">done</i>
				<label class="bold">'.$leaderApproval.'</label>
			</div>
		</div>
		<div class="pretty font-18 p-jelly p-icon mr-0 gotoLink" title="Không duyệt" link="'.PCMS_URL.'/take-leave-head_of_dep-not-approval/">
			<input type="checkbox" checked class="preventDefault">
			<div class="state p-danger">
				<i class="icon material-icons">clear</i>
				<label class="bold">'.$leaderNotApproval.'</label>
			</div>
		</div>
		<div class="pretty font-18 p-jelly p-icon mr-0 gotoLink" title="Chưa duyệt" link="'.PCMS_URL.'/take-leave-head_of_dep-unapproved/">
			<input type="checkbox" class="preventDefault">
			<div class="state p-danger">
				<i class="icon material-icons">done</i>
				<label class="bold">'.$leaderUnApproved.'</label>
			</div>
		</div>';
	}
	function getReason($takeleave_id,$oneTakeLeave=null){
		global $core, $clsISO;
		$html = '<a class="btn btn-sm btn-default underline" data-bs-trigger="hover" data-bs-toggle="popover" data-bs-offset="0,14" data-bs-placement="top" data-bs-html="true" data-bs-content="'.$this->getOneField('reason', $takeleave_id).'" href="javascript:void(0)">'.$core->makeIcon('eye').'</a>';
		return $html;
	}
	function getReasonUpdate($takeleave_id,$oneTakeLeave=null){
		if(!isset($oneTakeLeave['reason'])){
			$oneTakeLeave = $this->getOne($takeleave_id,"reason");
		}
		return '<div class="alert alert-success mb-0">'.html_entity_decode($oneTakeLeave['reason']).'</div>';
	}
}
?>