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
class FPoint extends dbBasic{
	function __construct(){
		$this->pkey = 'id';
		$this->tbl = DB_PREFIX.'fpoint';
	}
	function insertPoint($action,$profile_id,$for_id=0,$params=""){
		global $core,$dbconn,$profile_id,$oneProfile;
		$role_id = $oneProfile['role_id'];
		$department_id = $oneProfile['department_id'];
		#- Require library
		require_once(DIR_INCLUDES.'/json_master/autoload.php');
		#- End require
		$fpoint_configs = array();
		$cachedFile = DIR_CACHE_JSON.'/fpoint.json';
		if(@file_exists($cachedFile)){
			$decoder = new Webmozart\Json\JsonDecoder();
			$fpoint_configs = $decoder->decodeFile($cachedFile);
		}
		$score = 0; $content = "";
		if(isset($fpoint_configs[$action]) && !empty($fpoint_configs[$action])){
			$score = $fpoint_configs[$action]['score'];
			$content = $fpoint_configs[$action]['content'];
		}
		#-- Cập nhật tổng FPoint
		$clsProifile = new Profile();
		$total_fpoint = $oneProfile['total_fpoint'];
		$total_fpoint += (int) $score;
		$clsProifile->updateOne($profile_id, array(
			'total_fpoint' => $total_fpoint
		));
		#-- End Cập nhật tổng FPoint
		$content = sprintf($content,$params);
		$this->insert(array(
			// $this->pkey => $this->getMaxId(),
			'profile_id' => $profile_id,
			'act' => $action,
			'score' => $score,
			'for_id' => $for_id,
			'content' => $content,
			'reg_date' => time()
		));
	}
	function get_total_month($start_date, $end_date){
		$start_date = new DateTime(date('Y-m-d', $start_date));
		$end_date = new DateTime(date('Y-m-d', $end_date));
		$months = array();
		// Điều chỉnh ngày kết thúc đến cuối tháng để đảm bảo tính đúng tháng cuối cùng
		$end_date->modify('last day of this month');
		while ($start_date <= $end_date) {
			$months[] = $start_date->format('m/Y');
			$start_date->modify('first day of next month');
		}
		return !empty($months) ? count($months) : 0;
	}
	function insert_LPoint($staff_id, $oDataTable = array(), $last_day=0){
		global $core,$dbconn,$profile_id,$oneProfile,$clsISO;
		$clsProfile = new Profile();
		$clsProperty = new Property();
		#- Require library
		require_once(DIR_INCLUDES.'/json_master/autoload.php');
		#- End require
		$fpoint_configs = array();
		$cachedFile = DIR_CACHE_JSON.'/fpoint.json';
		if(@file_exists($cachedFile)){
			$decoder = new Webmozart\Json\JsonDecoder();
			$fpoint_configs = $decoder->decodeFile($cachedFile);
		}
		$last_day = ($last_day > 0) ? $last_day : strtotime("last day of this month");
		if(empty($oDataTable)){
			$_field = "role_id,department_id,total_Lpoint,start_date,level_id";
			$oDataTable = $clsProfile->getOne($staff_id, $_field);
		}
		$role_id = (int) $oDataTable['role_id'];
		$level_id = (int) $oDataTable['level_id'];
		$department_id = (int) $oDataTable['department_id'];
		$start_date = (int) $oDataTable['start_date']; // Ngày vào CTY
		$total_Lpoint = (int) $oDataTable['total_Lpoint']; // Điểm
		if($start_date == 0 || $start_date > $last_day) return false;
		if($department_id == _DEPARTMENT_DIRECTOR_ID) return false;
		// Khối BO
		if(in_array($department_id, array(_DEPARTMENT_BO_ID, _DEPARTMENT_TECH_ID, _DEPARTMENT_MKT_ID))){
			$action = sprintf('%s_seniority', $level_id);
		} else {
			$action = sprintf('%s_seniority', $role_id);
			if((int) date('n', $last_day) >= 6 && (int) date('Y', $last_day) <= 2024 
				&& $this->get_total_month($start_date, $last_day) <= 2){
				return false;
			} 
		}
		$score =(int) $fpoint_configs[$action]['score'];
		$content = $fpoint_configs[$action]['content'];
		$content = sprintf($content, date('m/Y', $last_day));
		$tmp = $this->getByCond("`ns_type`='Lpoint' AND `act`='{$action}' and `profile_id`='{$staff_id}' and `for_id`='{$last_day}'");
		if(empty($tmp)){
			$total_Lpoint += (int) $score;
			if($clsProfile->updateOne($staff_id, array(
				'total_Lpoint' => $total_Lpoint
			))){
				$this->insert(array(
					'profile_id' => $staff_id,
					'ns_type' => 'Lpoint',
					'act' => $action,
					'score' => $score,
					'for_id' => $last_day,
					'content' => $content,
					'reg_date' => $last_day
				));
			}
		}
	}
	function insert_billing_LPoint($billing_id, $oDataTable = array()){
		global $core,$dbconn,$profile_id,$oneProfile,$clsISO;
		$clsProfile = new Profile();
		$clsBilling = new Billing();
		$clsProperty = new Property();
		if(empty($oDataTable)){
			$field = "`billing_type`,`staff_id`,`stock_code`,`deposit_date`,`more_information`";
			$oDataTable = $clsBilling->getOne($billing_id, $field);
		}		
		// $this->deleteByCond("ns_type='Lpoint'");
		// $clsProfile->updateByCond("1=1", "`total_Lpoint`='0'");
		// die();
		$staff_id = $oDataTable['staff_id'];
		$block_id = $oDataTable['block_id'];
		$stock_code = $oDataTable['stock_code'];
		$billing_type = $oDataTable['billing_type'];
		$deposit_date = $oDataTable['deposit_date'];
		$more_info = $oDataTable['more_information'];
		$more_info = $clsISO->to_array_json($more_info);
		// Nếu là Partner
		if($staff_id == _PROFILE_PARTNER_ID) return false;
		$_field = "`role_id`,`team_id`,`department_id`,`code`
		,`full_name`,`first_name`,`last_name`,`total_Lpoint`";
		$oneStaff = $clsProfile->getOne($staff_id, $_field);
		$role_id = (int) $oneStaff['role_id'];
		$team_id = (int) $oneStaff['team_id'];
		$department_id = (int) $oneStaff['department_id'];
		// Nếu là GD & PGĐ
		if($role_id == _ROLE_GD_MANAGER || $role_id == _ROLE_PGD_MANAGER)
			return false;
		// Nếu là các phòng ban khác Sale
		if(in_array($department_id, array(_DEPARTMENT_BO_ID, _DEPARTMENT_TECH_ID, _DEPARTMENT_MKT_ID)))
			return false;
		$department_id = (int) $oneStaff['department_id'];
		$total_Lpoint = (int) $oneStaff['total_Lpoint']; // Điểm
		$staff_name = sprintf('%s-%s', $oneStaff['code'], $clsProfile->getFullName($staff_id, $oneStaff));
		#- Require library
		require_once(DIR_INCLUDES.'/json_master/autoload.php');
		#- End require
		$fpoint_configs = array();
		$cachedFile = DIR_CACHE_JSON.'/fpoint.json';
		if(@file_exists($cachedFile)){
			$decoder = new Webmozart\Json\JsonDecoder();
			$fpoint_configs = $decoder->decodeFile($cachedFile);
		}
		###
		$score = 0; $content = "";
		$action = sprintf('%s_%s', $role_id, $billing_type);
		if(isset($fpoint_configs[$action]) && !empty($fpoint_configs[$action])){
			$score = $fpoint_configs[$action]['score'];
			$content = $fpoint_configs[$action]['content'];
		}
		if($this->countItem("`ns_type`='Lpoint' and `profile_id`='{$staff_id}' 
			and `act`='{$action}' and `for_id`='{$billing_id}'") > 0){
			return false;
		}
		#-- Cập nhật tổng FPoint
		$total_Lpoint += (int) $score;
		if($clsProfile->updateOne($staff_id, array(
			'total_Lpoint' => $total_Lpoint
		))){
			$fpoint_id = $this->getMaxId();
			$content = sprintf($content, $stock_code);
			if($this->insert(array(
				$this->pkey => $fpoint_id,
				'profile_id' => $staff_id,
				'ns_type' => 'Lpoint',
				'act' => $action,
				'score' => $score,
				'for_id' => $billing_id,
				'content' => $content,
				'reg_date' => $deposit_date
			))){
				// Nếu là GD dự án thì Next.
				if($team_id > 0){
					$more_information = $clsProperty->getOneField('more_information', $team_id);
					$more_information = $clsISO->to_array_json($more_information);
					$head_of_dep_id = isset($more_information['head_of_dep_id']) 
						? (int) $more_information['head_of_dep_id'] : 0;
					if($head_of_dep_id > 0 && $staff_id != $head_of_dep_id){
						$key = sprintf('team_%s', $billing_type);
						$score = $fpoint_configs[$key]['score'];
						$content = $fpoint_configs[$key]['content'];
						$total_Lpoint = $clsProfile->getOneField('total_Lpoint', $head_of_dep_id);
						$total_Lpoint += (int) $score;
						if($clsProfile->updateOne($head_of_dep_id, array(
							'total_Lpoint' => $total_Lpoint
						))){
							$content = sprintf($content, $staff_name, $stock_code);
							$this->insert(array(
								'profile_id' => $head_of_dep_id,
								'ns_type' => 'Lpoint',
								'act' => $key,
								'score' => $score,
								'for_id' => $fpoint_id,
								'content' => $content,
								'reg_date' => $deposit_date
							));
						}
					}
				}
				// Nếu là GD dự án thì Next.
				if(!in_array($role_id, array(_ROLE_GD_SALE, _ROLE_GD_PROJECT))){
					// Giám đốc DA && Giám đốc KD
					$role_arrs = array(_ROLE_GD_PROJECT, _ROLE_GD_SALE);
					$tmp = $clsProfile->getByCond("`is_trash`=0 and `is_active`='1' 
					and `status_id`<>'"._STATUS_STAFF_OFF_ID."' and `department_id`='{$department_id}' 
					and `role_id` in (".implode(',', $role_arrs).")", $clsProfile->pkey);
					if(!empty($tmp)){
						$sale_director_id = $tmp[$clsProfile->pkey];
						$key = sprintf('dept_%s', $billing_type);
						$score = isset($fpoint_configs[$key]['score']) ? $fpoint_configs[$key]['score'] : 0;
						$content = isset($fpoint_configs[$key]['content']) ? $fpoint_configs[$key]['content'] : "";
						$total_Lpoint = $clsProfile->getOneField('total_Lpoint', $sale_director_id);
						$total_Lpoint += (int) $score;
						if($clsProfile->updateOne($sale_director_id, array(
							'total_Lpoint' => $total_Lpoint
						))){
							$content = sprintf($content, $staff_name, $stock_code);
							$this->insert(array(
								'profile_id' => $sale_director_id,
								'ns_type' => 'Lpoint',
								'act' => $key,
								'score' => $score,
								'for_id' => $fpoint_id,
								'content' => $content,
								'reg_date' => $deposit_date
							));
						}
					}
				} 
				// Giám đốc DA
				$more_information = $clsProperty->getOneField('more_information', $billing_type);
				$more_information = $clsISO->to_array_json($more_information);
				$project_director_id = isset($more_information['project_director_id']) 
					? (int) $more_information['project_director_id'] : 0;
				if($project_director_id > 0){
					$key = sprintf('manage_%s', $billing_type);
					$score = $fpoint_configs[$key]['score'];
					$content = $fpoint_configs[$key]['content'];
					$total_Lpoint = $clsProfile->getOneField('total_Lpoint', $project_director_id);
					$total_Lpoint += (int) $score;
					if($clsProfile->updateOne($project_director_id, array(
						'total_Lpoint' => $total_Lpoint
					))){
						$content = sprintf($content, $staff_name, $stock_code);
						$this->insert(array(
							'profile_id' => $project_director_id,
							'ns_type' => 'Lpoint',
							'act' => $key,
							'score' => $score,
							'for_id' => $fpoint_id,
							'content' => $content,
							'reg_date' => $deposit_date
						));
					}
				}
			}
		}
	}
	function cancel_billing_LPoint($billing_id, $oDataTable = array()){
		global $core,$dbconn,$profile_id,$oneProfile,$clsISO;
		$clsProfile = new Profile();
		$billing_id = (int) $billing_id;
		$staff_id = (int) $oDataTable['staff_id'];
		// Chỉ bắt LOG CÁ NHÂN còn hiệu lực của đúng GD: lọc shape act — tránh trúng nhầm log cascade/chuyển tay
		// (for_id của chúng là id LOG CHA, dải số có thể trùng billing_id); is_cancel=0 chặn hủy đúp trừ đúp.
		$_root_cond = "`ns_type`='Lpoint' and `for_id`='{$billing_id}' and `profile_id`='{$staff_id}' and `is_cancel`=0";
		$_root_cond .= " and `act` not like '%seniority' and `act` not like 'dept\\_%' and `act` not like 'team\\_%'";
		$_root_cond .= " and `act` not like 'manage\\_%' and `act` not like 'region\\_director\\_%' and `act` not in ('_minus','_plus')";
		$tmp = $this->getByCond($_root_cond);
		if(!empty($tmp)){
			$this->updateOne($tmp[$this->pkey], array( 'is_cancel' => 1 ));
			$clsProfile->updateOne($staff_id, "`total_Lpoint`=`total_Lpoint`-" . ((float) $tmp['score']));
			# Hủy chuỗi phụ trách móc vào log gốc (chỉ act cascade — tránh trúng log cá nhân GD khác trùng số id)
			$list_others = $this->getAll("`ns_type`='Lpoint' and `is_cancel`=0 and `for_id`='{$tmp[$this->pkey]}' and (`act` like 'dept\\_%' or `act` like 'team\\_%' or `act` like 'manage\\_%' or `act` like 'region\\_director\\_%')");
			if(!empty($list_others)){
				foreach($list_others as $key => $val){
					$score = (float) $val['score'];
					$this->updateOne($val[$this->pkey], array('is_cancel' => 1));
					$clsProfile->updateOne($val['profile_id'], "`total_Lpoint`=`total_Lpoint`-{$score}");
				}
			}
		}
	}
	// Điểm Loyalty theo GIÁ TRỊ giao dịch (thang 'trans_value' trong fpoint.json, đơn vị tỷ VND) — cơ chế 2026:
	// CHỈ người bán (staff_id) được điểm, KHÔNG cascade quản lý. Loại giao dịch theo billing_source_id:
	// _BILLING_RESOURCE_F1_ID (FH/Độc quyền) → exclusive_score; còn lại (Lấy chéo) → cross_score.
	// Idempotent theo (profile, for_id=billing_id, act) — gọi lại không cộng đúp. Hủy GD dùng cancel_billing_LPoint như cũ.
	function insert_billing_LPoint_value($billing_id, $oDataTable = array()){
		global $clsISO;
		$clsISO = is_object($clsISO) ? $clsISO : new ISO();
		$clsProfile = new Profile();
		$clsBilling = new Billing();
		$billing_id = (int) $billing_id;
		if($billing_id <= 0) return false;
		if(empty($oDataTable)){
			$field = "`staff_id`,`totalgrand`,`deposit_date`,`billing_source_id`,`stock_code`,`is_cancel`,`is_trash`";
			$oDataTable = $clsBilling->getOne($billing_id, $field);
		}
		if(empty($oDataTable)) return false;
		if((int) $oDataTable['is_cancel'] == 1 || (int) $oDataTable['is_trash'] == 1) return false;
		$staff_id = (int) $oDataTable['staff_id'];
		if($staff_id <= 0 || $staff_id == _PROFILE_PARTNER_ID) return false;
		// Giá trị giao dịch (VND) → tỷ; không có giá trị thì không chấm
		$totalgrand = $clsISO->processSmartNumber($oDataTable['totalgrand']);
		if($totalgrand <= 0) return false;
		$value_ty = $totalgrand / 1000000000;
		// Back Office / Kỹ thuật / Marketing chỉ có điểm thâm niên — không điểm giao dịch
		$oneStaff = $clsProfile->getOne($staff_id, "`department_id`");
		if(empty($oneStaff)) return false;
		if(in_array((int) $oneStaff['department_id'], array(_DEPARTMENT_BO_ID, _DEPARTMENT_TECH_ID, _DEPARTMENT_MKT_ID))) return false;
		// Loại giao dịch → field điểm + nhãn
		if((int) $oDataTable['billing_source_id'] == _BILLING_RESOURCE_F1_ID){
			$score_field = 'exclusive_score';
			$type_label = 'Độc quyền';
		} else {
			$score_field = 'cross_score';
			$type_label = 'Quỹ chéo';
		}
		// Đọc thang bậc trans_value từ fpoint.json
		require_once(DIR_INCLUDES.'/json_master/autoload.php');
		$fpoint_configs = array();
		$cachedFile = DIR_CACHE_JSON.'/fpoint.json';
		if(@file_exists($cachedFile)){
			$decoder = new Webmozart\Json\JsonDecoder();
			$fpoint_configs = $decoder->decodeFile($cachedFile);
		}
		$tiers = (isset($fpoint_configs['trans_value']) && is_array($fpoint_configs['trans_value'])) ? $fpoint_configs['trans_value'] : array();
		if(empty($tiers)) return false;
		// Dò bậc: from <= giá trị < to; to=0 nghĩa là "trở lên"
		$score = 0;
		foreach($tiers as $tier){
			$tier_from = (float) (isset($tier['from']) ? $tier['from'] : 0);
			$tier_to = (float) (isset($tier['to']) ? $tier['to'] : 0);
			if($value_ty >= $tier_from && ($tier_to == 0 || $value_ty < $tier_to)){
				$score = (float) (isset($tier[$score_field]) ? $tier[$score_field] : 0);
				break;
			}
		}
		if($score <= 0) return false;
		// Idempotent: giao dịch này đã chấm theo cơ chế giá trị thì thôi
		if($this->countItem("`ns_type`='Lpoint' and `profile_id`='{$staff_id}' and `for_id`='{$billing_id}' and `act` in ('exclusive_score','cross_score')") > 0){
			return false;
		}
		$content = sprintf(
			'Giao dịch thành công căn <strong>%s</strong> — %s tỷ, %s (+%s điểm)',
			$oDataTable['stock_code'],
			round($value_ty, 2) * 1,
			$type_label,
			$score * 1
		);
		// Cộng điểm ATOMIC trên SQL (tránh race + tránh lỗi read-modify-write ghi đè số dư như hàm cũ)
		if($clsProfile->updateOne($staff_id, "`total_Lpoint`=`total_Lpoint`+{$score}")){
			$this->insert(array(
				'profile_id' => $staff_id,
				'ns_type' => 'Lpoint',
				'act' => $score_field,
				'score' => $score,
				'for_id' => $billing_id,
				'content' => $content,
				'reg_date' => ((int) $oDataTable['deposit_date'] > 0) ? (int) $oDataTable['deposit_date'] : time()
			));
			return true;
		}
		return false;
	}
	// Điểm Loyalty THÂM NIÊN hàng tháng — cơ chế 2026:
	// Khối Sale: theo VỊ TRÍ → fpoint_configs[{role_id}_seniority][score] (CVKD 51 / GĐ Kinh doanh 48 / GĐ Vùng 11257 ...).
	// Khối BO/TECH/MKT: theo BẬC → fpoint_configs[{level_id}_seniority][score] (Cấp 1/2/3).
	// Vị trí/bậc chưa cấu hình điểm → bỏ qua, KHÔNG ghi log rác. Idempotent: 1 log thâm niên/người/tháng (for_id = mốc 28 của tháng).
	function insert_LPoint_seniority($staff_id, $oDataTable = array(), $last_day = 0){
		$clsProfile = new Profile();
		$staff_id = (int) $staff_id;
		if($staff_id <= 0 || $staff_id == _PROFILE_PARTNER_ID) return false;
		$last_day = ($last_day > 0) ? (int) $last_day : strtotime(date('Y-m-28'));
		if(empty($oDataTable)){
			$oDataTable = $clsProfile->getOne($staff_id, "`role_id`,`level_id`,`department_id`,`start_date`");
		}
		if(empty($oDataTable)) return false;
		$start_date = (int) $oDataTable['start_date'];
		if($start_date == 0 || $start_date > $last_day) return false; // chưa vào công ty tại mốc tháng này
		$department_id = (int) $oDataTable['department_id'];
		if($department_id == _DEPARTMENT_DIRECTOR_ID) return false; // Ban giám đốc không tính
		// Sale theo vị trí (role_id); khối BO/TECH/MKT theo bậc (level_id)
		if(in_array($department_id, array(_DEPARTMENT_BO_ID, _DEPARTMENT_TECH_ID, _DEPARTMENT_MKT_ID))){
			$action = ((int) $oDataTable['level_id']) . '_seniority';
		} else {
			$action = ((int) $oDataTable['role_id']) . '_seniority';
		}
		// Đọc điểm từ fpoint.json
		require_once(DIR_INCLUDES.'/json_master/autoload.php');
		$fpoint_configs = array();
		$cachedFile = DIR_CACHE_JSON.'/fpoint.json';
		if(@file_exists($cachedFile)){
			$decoder = new Webmozart\Json\JsonDecoder();
			$fpoint_configs = $decoder->decodeFile($cachedFile);
		}
		$score = isset($fpoint_configs[$action]['score']) ? (float) $fpoint_configs[$action]['score'] : 0;
		if($score <= 0) return false;
		// Idempotent: mỗi người tối đa 1 log thâm niên/tháng (kể cả đổi vị trí giữa 2 lần chạy)
		if($this->countItem("`ns_type`='Lpoint' and `profile_id`='{$staff_id}' and `for_id`='{$last_day}' and `act` like '%seniority'") > 0){
			return false;
		}
		$content = isset($fpoint_configs[$action]['content']) ? (string) $fpoint_configs[$action]['content'] : '';
		if($content == ''){
			$content = 'Thâm niên tháng <strong>%s</strong>';
		}
		$content = sprintf($content, date('m/Y', $last_day));
		// Cộng điểm ATOMIC trên SQL
		if($clsProfile->updateOne($staff_id, "`total_Lpoint`=`total_Lpoint`+{$score}")){
			$this->insert(array(
				'profile_id' => $staff_id,
				'ns_type' => 'Lpoint',
				'act' => $action,
				'score' => $score,
				'for_id' => $last_day,
				'content' => $content,
				'reg_date' => $last_day
			));
			return true;
		}
		return false;
	}
	// TÍNH LẠI điểm thâm niên cho 1 nhân viên (nút "Tính lại điểm Loyalty" ở /staff.html — dùng sau khi gán vị trí/bậc):
	// gỡ toàn bộ log thâm niên từ 01/2024 của người này (gồm cả log rác 0_seniority) rồi chấm lại từng tháng tới mốc 28 gần nhất.
	// Thang điểm: khối BO/TECH/MKT năm 2024-2025 dùng thang cũ cố định (Cấp 1/2/3 = 30/40/50);
	// từ 2026 đọc fpoint.json hiện hành (cơ chế mới). Khối sale luôn đọc {role_id}_seniority từ json.
	function rebuild_LPoint_seniority_staff($staff_id){
		$clsProfile = new Profile();
		$staff_id = (int) $staff_id;
		$result = array('deleted' => 0, 'sub' => 0, 'inserted' => 0, 'add' => 0);
		if($staff_id <= 0 || $staff_id == _PROFILE_PARTNER_ID) return $result;
		$oneStaff = $clsProfile->getOne($staff_id, "`role_id`,`level_id`,`department_id`,`start_date`");
		if(empty($oneStaff)) return $result;
		$department_id = (int) $oneStaff['department_id'];
		$start_date = (int) $oneStaff['start_date'];
		$is_bo = in_array($department_id, array(_DEPARTMENT_BO_ID, _DEPARTMENT_TECH_ID, _DEPARTMENT_MKT_ID));
		// Thang hiện hành từ fpoint.json
		require_once(DIR_INCLUDES.'/json_master/autoload.php');
		$fpoint_configs = array();
		$cachedFile = DIR_CACHE_JSON.'/fpoint.json';
		if(@file_exists($cachedFile)){
			$decoder = new Webmozart\Json\JsonDecoder();
			$fpoint_configs = $decoder->decodeFile($cachedFile);
		}
		// Thang BO cũ áp cho 2024-2025 (chính sách đổi thang từ 2026 — cố định để tính lại đúng lịch sử)
		$legacy_bo_scores = array(1286 => 30, 1287 => 40, 1288 => 50);
		// 1) Gỡ log thâm niên cũ từ 01/2024
		$from_2024 = strtotime('2024-01-01 00:00:00');
		$_cond_old = "`ns_type`='Lpoint' and `profile_id`='{$staff_id}' and `act` like '%seniority' and `reg_date`>='{$from_2024}'";
		$old_logs = $this->getAll($_cond_old, "{$this->pkey},`score`");
		if(!empty($old_logs)){
			foreach($old_logs as $row){
				$result['sub'] += (float) $row['score'];
			}
			$result['deleted'] = count($old_logs);
			$this->deleteByCond($_cond_old);
		}
		// 2) Chấm lại từng tháng 01/2024 → mốc 28 gần nhất đã qua
		$now = time();
		if($department_id != _DEPARTMENT_DIRECTOR_ID && $start_date > 0){
			for($year = 2024; $year <= (int) date('Y'); $year++){
				for($month = 1; $month <= 12; $month++){
					$last_day = strtotime(sprintf('28-%s-%s', $month, $year));
					// Cho phép chấm mốc THÁNG HIỆN TẠI dù chưa tới ngày 28 (đồng bộ cron app.php vốn chấm sớm khi chạy trong tháng)
					if($last_day > $now && !($year == (int) date('Y') && $month == (int) date('n'))){
						break 2;
					}
					if($start_date > $last_day) continue;
					if($is_bo){
						$level_id = (int) $oneStaff['level_id'];
						$action = $level_id . '_seniority';
						if($year <= 2025){
							$score = isset($legacy_bo_scores[$level_id]) ? (float) $legacy_bo_scores[$level_id] : 0;
						} else {
							$score = isset($fpoint_configs[$action]['score']) ? (float) $fpoint_configs[$action]['score'] : 0;
						}
					} else {
						$action = ((int) $oneStaff['role_id']) . '_seniority';
						$score = isset($fpoint_configs[$action]['score']) ? (float) $fpoint_configs[$action]['score'] : 0;
					}
					if($score <= 0) continue;
					$content = isset($fpoint_configs[$action]['content']) ? (string) $fpoint_configs[$action]['content'] : '';
					if($content == ''){
						$content = 'Thâm niên tháng <strong>%s</strong>';
					}
					$this->insert(array(
						'profile_id' => $staff_id,
						'ns_type' => 'Lpoint',
						'act' => $action,
						'score' => $score,
						'for_id' => $last_day,
						'content' => sprintf($content, date('m/Y', $last_day)),
						'reg_date' => $last_day
					));
					$result['inserted']++;
					$result['add'] += $score;
				}
			}
		}
		// 3) Điều chỉnh tổng điểm 1 lần (atomic; delta âm vẫn hợp lệ nhờ dấu ngoặc)
		$delta = $result['add'] - $result['sub'];
		if($delta != 0){
			$clsProfile->updateOne($staff_id, "`total_Lpoint`=`total_Lpoint`+({$delta})");
		}
		return $result;
	}
	// Đồng bộ CHÍNH XÁC tổng điểm 1 nhân viên từ log: total_Lpoint = SUM(điểm cộng) - SUM(điểm trừ '_minus'), bỏ log đã hủy (is_cancel=1).
	function recompute_total_from_logs($staff_id){
		global $dbconn;
		$clsProfile = new Profile();
		$staff_id = (int) $staff_id;
		$result = array('old' => 0, 'new' => 0, 'changed' => 0);
		if($staff_id <= 0) return $result;
		$result['old'] = (float) $clsProfile->getOneField('total_Lpoint', $staff_id);
		$result['new'] = (float) $dbconn->GetOne("SELECT COALESCE(SUM(CASE WHEN `act`='_minus' THEN -`score` ELSE `score` END),0) FROM `{$this->tbl}` WHERE `ns_type`='Lpoint' AND `is_cancel`=0 AND `profile_id`='{$staff_id}'");
		if($result['old'] != $result['new']){
			$clsProfile->updateOne($staff_id, array('total_Lpoint' => $result['new']));
			$result['changed'] = 1;
		}
		return $result;
	}
	// Điểm Loyalty GIAO DỊCH khối kinh doanh — cơ chế ma trận + cascade theo CÂY TỔ CHỨC (chốt 02/07/2026):
	// 1) Người bán ăn điểm cá nhân fpoint_configs[{role_id}_{billing_type}] (ma trận màn admin fpoint).
	// 2) Leo cây phòng ban từ dept người bán: node có GĐ Kinh doanh (role 48) → +dept_{billing_type} ('Đội ngũ bán');
	//    node Vùng có GĐ Vùng (role 11257) → +region_director_{billing_type} ('Đội ngũ khu vực'). GĐ TỰ BÁN vẫn hưởng cả điểm phụ trách của chính mình.
	// 3) GĐ Dự Án phụ trách dự án của căn → +manage_{billing_type} ('Dự án phụ trách'). Nguồn: dep_logs.project_director_id
	//    trên GD (hay THIẾU) → fallback tra căn: stock(ms_code,project_id) → block; thấp tầng = _PROFILE_PVD_ID, còn lại = project_manager của phân khu.
	// KHÔNG dùng thang giá trị trans_value cho Loyalty (thang đó dành cho phân hạng định danh).
	// Idempotent theo GD (for_id=billing_id); cascade for_id = id log cá nhân → cancel_billing_LPoint hủy được trọn chuỗi.
	function insert_billing_LPoint_role($billing_id, $oDataTable = array()){
		global $clsISO;
		$clsISO = is_object($clsISO) ? $clsISO : new ISO();
		$clsProfile = new Profile();
		$clsBilling = new Billing();
		$clsProperty = new Property();
		$billing_id = (int) $billing_id;
		if($billing_id <= 0) return false;
		if(empty($oDataTable)){
			$field = "`staff_id`,`billing_type`,`deposit_date`,`stock_code`,`project_id`,`more_information`,`is_cancel`,`is_trash`";
			$oDataTable = $clsBilling->getOne($billing_id, $field);
		}
		if(empty($oDataTable)) return false;
		if((int) $oDataTable['is_cancel'] == 1 || (int) $oDataTable['is_trash'] == 1) return false;
		$staff_id = (int) $oDataTable['staff_id'];
		if($staff_id <= 0 || $staff_id == _PROFILE_PARTNER_ID) return false;
		$billing_type = (int) $oDataTable['billing_type'];
		if($billing_type <= 0) return false;
		$oneStaff = $clsProfile->getOne($staff_id, "`role_id`,`department_id`,`code`,`full_name`,`first_name`,`last_name`");
		if(empty($oneStaff)) return false;
		$department_id = (int) $oneStaff['department_id'];
		// Back Office / Kỹ thuật / Marketing chỉ có điểm thâm niên
		if(in_array($department_id, array(_DEPARTMENT_BO_ID, _DEPARTMENT_TECH_ID, _DEPARTMENT_MKT_ID))) return false;
		// Idempotent: GD này đã có LOG CÁ NHÂN còn hiệu lực của người bán thì thôi. Lọc CHÍNH XÁC shape log cá nhân:
		// loại log thâm niên, log cascade (dept_/team_/manage_/region_director_ — for_id của chúng là id LOG CHA,
		// dải số có thể TRÙNG billing_id) và log chuyển tay (_plus/_minus); is_cancel=0 để GD khôi phục sau hủy chấm lại được.
		$_dup_cond = "`ns_type`='Lpoint' and `profile_id`='{$staff_id}' and `for_id`='{$billing_id}' and `is_cancel`=0";
		$_dup_cond .= " and `act` not like '%seniority' and `act` not like 'dept\\_%' and `act` not like 'team\\_%'";
		$_dup_cond .= " and `act` not like 'manage\\_%' and `act` not like 'region\\_director\\_%' and `act` not in ('_minus','_plus')";
		if($this->countItem($_dup_cond) > 0){
			return false;
		}
		// Đọc ma trận điểm
		require_once(DIR_INCLUDES.'/json_master/autoload.php');
		$fpoint_configs = array();
		$cachedFile = DIR_CACHE_JSON.'/fpoint.json';
		if(@file_exists($cachedFile)){
			$decoder = new Webmozart\Json\JsonDecoder();
			$fpoint_configs = $decoder->decodeFile($cachedFile);
		}
		$action = ((int) $oneStaff['role_id']) . '_' . $billing_type;
		$score = isset($fpoint_configs[$action]['score']) ? (float) $fpoint_configs[$action]['score'] : 0;
		if($score <= 0) return false; // vị trí chưa cấu hình điểm loại GD này (vd role_id=0) → chưa chấm, gán vị trí xong tính lại
		$reg_date = ((int) $oDataTable['deposit_date'] > 0) ? (int) $oDataTable['deposit_date'] : time();
		$stock_code = $oDataTable['stock_code'];
		// 1) Điểm cá nhân người bán (atomic)
		$content = isset($fpoint_configs[$action]['content']) ? (string) $fpoint_configs[$action]['content'] : '';
		if($content == '' || substr_count($content, '%s') != 1){
			$content = 'Giao dịch thành công căn <strong>%s</strong>';
		}
		// Ghi log TRƯỚC rồi mới cộng total: nếu đụng id do ghi đồng thời (getMaxId không atomic) thì dừng sạch —
		// không cộng total mồ côi, không cascade nhầm chuỗi; lần tính lại sau sẽ chấm bù.
		$fpoint_id = $this->getMaxId();
		$_ins_ok = $this->insert(array(
			$this->pkey => $fpoint_id,
			'profile_id' => $staff_id,
			'ns_type' => 'Lpoint',
			'act' => $action,
			'score' => $score,
			'for_id' => $billing_id,
			'content' => sprintf($content, $stock_code),
			'reg_date' => $reg_date
		));
		if(empty($_ins_ok)){
			return false;
		}
		$clsProfile->updateOne($staff_id, "`total_Lpoint`=`total_Lpoint`+{$score}");
		$result = array('personal' => 1, 'dept' => 0, 'region' => 0, 'manage' => 0);
		// 2) Cascade phụ trách: leo cây phòng ban tối đa 8 bậc, dừng khi đủ 2 người hoặc hết cây
		$staff_name = sprintf('%s-%s', $oneStaff['code'], $clsProfile->getFullName($staff_id, $oneStaff));
		$_gate = "`is_trash`=0 and `is_active`=1 and `status_id`='" . _STATUS_STAFF_ON_ID . "'";
		$node = $department_id;
		$hops = 0;
		$found_dept = false;
		$found_region = false;
		while($node > 0 && $hops < 8 && (!$found_dept || !$found_region)){
			// GĐ Kinh doanh tại node ('Đội ngũ bán')
			if(!$found_dept){
				$_boss = $clsProfile->getByCond("{$_gate} and `department_id`='{$node}' and `role_id`='" . _ROLE_GD_SALE . "'", "{$clsProfile->pkey}");
				if(!empty($_boss)){
					$found_dept = true;
					$result['dept'] = $this->addCascadePoint((int) $_boss[$clsProfile->pkey], 'dept_' . $billing_type, $fpoint_id, $staff_name, $stock_code, $reg_date, $fpoint_configs);
				}
			}
			// GĐ Vùng tại node ('Đội ngũ khu vực')
			if(!$found_region){
				$_rboss = $clsProfile->getByCond("{$_gate} and `department_id`='{$node}' and `role_id`='" . _ROLE_REGIONAL_DIRECTOR_ID . "'", "{$clsProfile->pkey}");
				if(!empty($_rboss)){
					$found_region = true;
					$result['region'] = $this->addCascadePoint((int) $_rboss[$clsProfile->pkey], 'region_director_' . $billing_type, $fpoint_id, $staff_name, $stock_code, $reg_date, $fpoint_configs);
				}
			}
			$_parent = $clsProperty->getOne($node, '`parent_id`');
			$node = !empty($_parent) ? (int) $_parent['parent_id'] : 0;
			$hops++;
		}
		// 3) GĐ Dự Án phụ trách ('Dự án phụ trách' — manage_{bt}). Ưu tiên snapshot dep_logs trên GD;
		// thiếu thì tra từ căn (cùng nguồn với màn chọn GĐ DA: default_get_project_dir_by_stock).
		$project_director_id = 0;
		$_bill_info = isset($oDataTable['more_information']) ? $clsISO->to_array_json($oDataTable['more_information']) : array();
		$_dep_logs = (isset($_bill_info['dep_logs']) && is_array($_bill_info['dep_logs'])) ? $_bill_info['dep_logs'] : array();
		if(isset($_dep_logs['project_director_id'])){
			$project_director_id = (int) $_dep_logs['project_director_id'];
		}
		if($project_director_id <= 0){
			$_proj_id = (int) (isset($oDataTable['project_id']) ? $oDataTable['project_id'] : 0);
			if($_proj_id > 0 && $stock_code != ''){
				$clsStock = new Stock();
				$_sc = addslashes($stock_code);
				$oStock = $clsStock->getByCond("`project_id`='{$_proj_id}' AND `ms_code`='{$_sc}'", "`block_id`");
				$_block_id = !empty($oStock) ? (int) $oStock['block_id'] : 0;
				if($_block_id > 0){
					$oneBlock = $clsProperty->getOne($_block_id, "`parent_id`,`more_information`");
					if(!empty($oneBlock)){
						if((int) $oneBlock['parent_id'] == _BLOCK_TYPE_LOWFLOOR_SALE){
							$project_director_id = (int) _PROFILE_PVD_ID;
						} else {
							$_blk_info = $clsISO->to_array_json($oneBlock['more_information']);
							$project_director_id = (int) (isset($_blk_info['project_manager']) ? $_blk_info['project_manager'] : 0);
						}
					}
				}
			}
		}
		if($project_director_id > 0){
			// chỉ cộng khi GĐ DA còn làm việc (config/snapshot có thể trỏ người đã nghỉ)
			$_pd = $clsProfile->getByCond("{$_gate} and `{$clsProfile->pkey}`='{$project_director_id}'", "{$clsProfile->pkey}");
			if(!empty($_pd)){
				$result['manage'] = $this->addCascadePoint($project_director_id, 'manage_' . $billing_type, $fpoint_id, $staff_name, $stock_code, $reg_date, $fpoint_configs);
			}
		}
		return $result;
	}
	// Cộng điểm phụ trách cho 1 quản lý (GĐKD/GĐV) — GĐ tự bán VẪN được điểm phụ trách của chính mình; bỏ qua khi chưa cấu hình điểm.
	function addCascadePoint($boss_id, $action, $parent_fpoint_id, $staff_name, $stock_code, $reg_date, $fpoint_configs){
		$clsProfile = new Profile();
		$boss_id = (int) $boss_id;
		if($boss_id <= 0) return 0;
		$score = isset($fpoint_configs[$action]['score']) ? (float) $fpoint_configs[$action]['score'] : 0;
		if($score <= 0) return 0;
		$content = isset($fpoint_configs[$action]['content']) ? (string) $fpoint_configs[$action]['content'] : '';
		if($content == '' || substr_count($content, '%s') != 2){
			$content = '<strong>%s</strong> giao dịch thành công căn <strong>%s</strong>';
		}
		// Ghi log trước, total sau — log fail (đụng id/act tràn cột) thì không cộng điểm mồ côi
		$_ins_ok = $this->insert(array(
			'profile_id' => $boss_id,
			'ns_type' => 'Lpoint',
			'act' => $action,
			'score' => $score,
			'for_id' => (int) $parent_fpoint_id,
			'content' => sprintf($content, $staff_name, $stock_code),
			'reg_date' => (int) $reg_date
		));
		if(empty($_ins_ok)){
			return 0;
		}
		$clsProfile->updateOne($boss_id, "`total_Lpoint`=`total_Lpoint`+{$score}");
		return 1;
	}
}
?>