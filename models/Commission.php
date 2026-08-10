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
class Commission extends DbBasic{
	function __construct(){
		$this->pkey = "commission_id";
		$this->tbl = DB_PREFIX."commission";
	}
	function format_h2($commission){
		global $core, $dbconn, $clsISO;
		$commission = @preg_replace('/\./',',', $commission);
		if(!$clsISO->checkContainer($commission, ",", "")){
			return $commission.',00';
		}
		return $commission;
	}
	function crawl($spreadsheetId, $quarter_id, $sheet_name = '', $layout = ''){
		global $clsISO, $core, $dbconn, $profile_id;
		$clsProfile = new Profile();
		$clsProject = new Project();
		$clsBilling = new Billing();
		$clsSetting = new Setting();
		$clsProperty = new Property();
		$msg = "_error";
		if(!empty($spreadsheetId)){
			#- Required Library
			require_once(DIR_INCLUDES.'/json_master/autoload.php');		
			require_once(DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
			#- End Required Library
			$client = new Google_Client();
			$client->setClientId(GOOGLE_CLIENT_ID);
			$client->setClientSecret(GOOGLE_CLIENT_SECRET);
			$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
			$client->setScopes([Google_Service_Drive::DRIVE]);
			$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
			$service = new Google_Service_Sheets($client);
			$drive = new Google_Service_Drive($client);
			// ĐỌC THEO MẪU: chọn mẫu ($layout truyền vào, rỗng thì suy mặc định theo kỳ)
			$layouts = $this->getLayouts();
			if(empty($layout) || !isset($layouts[$layout])){
				$layout = $this->defaultLayoutFor($quarter_id);
			}
			if($layout == '' || !isset($layouts[$layout])){
				// Kỳ dạng quý (Q..) chưa chọn mẫu file → KHÔNG đọc bằng year_std (sai hoàn toàn). Buộc chọn mẫu.
				return '_error_chua_chon_mau_file';
			}
			$def = $layouts[$layout];
			$is_year_layout = ($def['kind'] == 'year') ? 1 : 0; // cờ mẫu-năm (tự suy quý) — dùng lại ở match/sweep/new-keys
			$layout_year = 0;
			if(preg_match('/^Y(\d{4})$/', $quarter_id, $_ym)){
				$layout_year = (int) $_ym[1];
			} else if(preg_match('/^Q[1-4]_(\d{4})$/', $quarter_id, $_qm)){
				$layout_year = (int) $_qm[1];
			}
			// range: sheet_name (nếu nhập) > mẫu tự-dò-tab (__auto_year__) > tên tab cố định của mẫu > tên kỳ
			$auto_tab = ($def['range'] == '__auto_year__') ? 1 : 0;
			$range = trim($sheet_name);
			if($range == '' && $auto_tab == 0){
				$range = ($def['range'] != '') ? $def['range'] : sprintf('%s', $quarter_id);
			}
			// start_row: mẫu 'scan' dò header sau khi đọc; 'fixed' dùng dòng cố định
			$start_row = ($def['start']['mode'] == 'fixed') ? (int) $def['start']['row'] : 0;
			try {
				if($auto_tab == 1 && $range != ''){
					// Tên tab cấu hình sai → không rơi sang nhánh convert Excel, blank để tự dò lại tab
					try {
						$response = $service->spreadsheets_values->get($spreadsheetId, $range);
					} catch(Exception $_exr){
						$range = '';
					}
				}
				if($auto_tab == 1 && $range == ''){
					// Tự dò tab của file năm: ưu tiên tab tên = năm, rồi tab chứa năm, cuối cùng tab đầu
					$_first_title = '';
					$_meta = $service->spreadsheets->get($spreadsheetId);
					foreach($_meta->getSheets() as $_sh){
						$_title = trim($_sh->getProperties()->getTitle());
						if($_first_title == ''){
							$_first_title = $_title;
						}
						if($_title == (string) $layout_year){
							$range = $_title;
							break;
						}
						if($range == '' && strpos($_title, (string) $layout_year) !== false){
							$range = $_title;
						}
					}
					if($range == ''){
						$range = $_first_title;
					}
				}
				if(!isset($response)){
					$response = $service->spreadsheets_values->get($spreadsheetId, $range);
				}
				$tblData = $response->getValues();
			} catch(Exception $ex){
				$msg_error = $ex->getMessage();
				// Đọc file Excel từ Google Drive
				$response = $drive->files->get($spreadsheetId, array(
					'supportsAllDrives' => 'true'
				));
				// Chuyển đổi file Excel thành Google Sheets
				$fileMetadata = new \Google_Service_Drive_DriveFile(array(
					'name' => sprintf('%s', date('d-m-y h:i:s'))
				));
				$fileMetadata->setParents([GOOGLE_DRIVE_PTG_COPY_ID]);
				$fileMetadata->setMimeType('application/vnd.google-apps.spreadsheet');
				$convertedFile = $drive->files->copy($spreadsheetId, $fileMetadata, array(
					'supportsAllDrives' => 'true'
				));
				$spreadsheetId = $convertedFile->getId();
				if($auto_tab == 1 && $range == ''){
					// Tự dò tab của file năm: ưu tiên tab tên = năm, rồi tab chứa năm, cuối cùng tab đầu
					$_first_title = '';
					$_meta = $service->spreadsheets->get($spreadsheetId);
					foreach($_meta->getSheets() as $_sh){
						$_title = trim($_sh->getProperties()->getTitle());
						if($_first_title == ''){
							$_first_title = $_title;
						}
						if($_title == (string) $layout_year){
							$range = $_title;
							break;
						}
						if($range == '' && strpos($_title, (string) $layout_year) !== false){
							$range = $_title;
						}
					}
					if($range == ''){
						$range = $_first_title;
					}
				}
				$response = $service->spreadsheets_values->get($spreadsheetId, $range);
				// $drive->files->delete($spreadsheetId);
				$tblData = $response->getValues();
			}
			$total_insert = $total_update = $total_delete = 0;
			$errors_arrs = $arr_insert = $arr_update = $arr_delete = $list_commission_found = array();
			$found_by_quarter = array();
			$sweep_block = array();
			$sweep_block_all = 0;
			if(!empty($tblData)){
				$total_records = count($tblData);
				if($def['start']['mode'] == 'scan'){
					// Dò dòng header theo marker (vd 'STT') rồi bỏ 'offset' dòng header phụ/tổng
					$_hcol = (int) $def['start']['col'];
					$_hmark = $def['start']['marker'];
					for($h = 0; $h < $total_records; $h++){
						if(isset($tblData[$h][$_hcol]) && trim($tblData[$h][$_hcol]) == $_hmark){
							$start_row = $h + (int) $def['start']['offset'];
							break;
						}
					}
					if($start_row == 0){
						return '_error_khong_thay_header_' . $_hmark;
					}
				}
				for($i=$start_row; $i < $total_records; $i++){
					// Đọc dòng theo MẪU → biến bare (whitelist) + quý + cờ skip/sweep. Khối lưu bên dưới giữ nguyên.
					$_parsed = $this->readRowByLayout($tblData[$i], $def, array('quarter_id' => $quarter_id, 'layout_year' => $layout_year));
					extract($_parsed['vars']);
					$row_quarter_id = $_parsed['row_quarter_id'];
					if($_parsed['block_all']){
						$sweep_block_all = 1;
					}
					if($_parsed['block_sweep'] && $row_quarter_id != ''){
						$sweep_block[$row_quarter_id] = 1;
					}
					if($_parsed['skip']){
						$stock_code = ''; // guard khối chung bỏ qua dòng này
					}
					##
					$agency_id = 0;
					if(!empty($agency_name)){
						$tmp = $clsSetting->getByCond("`_type`='_AGENCY' and `slug`='".$clsISO->replaceSpace($agency_name)."'");
						if(!empty($tmp)){
							$agency_id = $tmp[$clsSetting->pkey];
						} else {
							$agency_id = $clsSetting->getMaxId();
							$clsSetting->insert(array(
								$clsSetting->pkey => $agency_id,
								'_type' => '_AGENCY',
								'title' => $agency_name,
								'slug' => $clsISO->replaceSpace($agency_name),
								'order_no' => $clsSetting->getMaxOrderNo(),
								'reg_date' => time()
							));
						}
					}
					##
					$staff_id = 0;
					if(!empty($staff_name)){
						$tmp = $clsSetting->getByCond("`_type`='_STAFF' and `slug`='".$clsISO->replaceSpace($staff_name)."'");
						if(!empty($tmp)){
							$staff_id = $tmp[$clsSetting->pkey];
						} else {
							$staff_id = $clsSetting->getMaxId();
							$clsSetting->insert(array(
								$clsSetting->pkey => $staff_id,
								'_type' => '_STAFF',
								'title' => $staff_name,
								'slug' => $clsISO->replaceSpace($staff_name),
								'order_no' => $clsSetting->getMaxOrderNo(),
								'reg_date' => time()
							));
						}
					}
					##
					$project_id = $block_id = 0;
					if(!empty($project_name)){
						$tmp = $clsProject->getByCond("(`code`='".$clsISO->replaceSpace($project_name)."' 
							OR `slug`='".$clsISO->replaceSpace($project_name)."')", $clsProject->pkey);
						if(!empty($tmp)){
							$project_id = $tmp[$clsProject->pkey];
						} else {
							$field = "{$clsProperty->pkey},`for_id`";
							$tmp = $clsProperty->getByCond("`property_type`='_BLOCK' AND `property_code`='".$clsISO->replaceSpace($project_name)."'", $field);
							if(!empty($tmp)){
								$project_id = $tmp['for_id'];
								$block_id = $tmp[$clsProperty->pkey];
							}
							unset($tmp);
						}
					}
					##
					$bedroom_id = 0;
					if(!empty($bedroom_name)){
						$tmp = $clsSetting->getByCond("`_type`='_BEDROOM' and `slug`='".$clsISO->replaceSpace($bedroom_name)."'", $clsSetting->pkey);
						if(!empty($tmp)){
							$bedroom_id = $tmp[$clsSetting->pkey];
						} else {
							$bedroom_id = $clsSetting->getMaxId();
							$clsSetting->insert(array(
								$clsSetting->pkey => $bedroom_id,
								'_type' => '_BEDROOM',
								'title' => $bedroom_name,
								'slug' => $clsISO->replaceSpace($bedroom_name),
								'order_no' => $clsSetting->getMaxOrderNo(),
								'reg_date' => time()
							));
						}
					}
					$club_manager_id = 0;
					if(!empty($club_manager_name)){
						$tmp = $clsProfile->getByCond("`full_name_slug`='".$clsISO->replaceSpace($club_manager_name)."'", $clsProfile->pkey);
						$club_manager_id = !empty($tmp) ? $tmp[$clsProfile->pkey] : 0;
					}
					##
					if(!empty($stock_code) && $contract_date > 0){
						$status_leader_id = $status_sale_dir_id = $status_project_dir_id 
						= $status_agent_id = $status_sale_id = $status_club_manager_id = _COMMISSION_UNPAID_STATUS_ID;
						if(!empty($status_agent_name) && $status_agent_name != 'Chưa TT'){
							$tmp = $clsProperty->getByCond("`property_type`='COMMISSION_PAYMENT_STATUS' AND (`property_code`='".strtolower($status_agent_name)."' OR `slug`='{$clsISO->replaceSpace($status_agent_name)}')", $clsProperty->pkey);
							if(!empty($tmp)) $status_agent_id = $tmp[$clsProperty->pkey];
						}
						if(!empty($status_sales_name) && $status_sales_name != 'Chưa TT'){
							$tmp = $clsProperty->getByCond("`property_type`='COMMISSION_PAYMENT_STATUS' AND (`property_code`='".strtolower($status_sales_name)."' OR `slug`='{$clsISO->replaceSpace($status_sales_name)}')", $clsProperty->pkey);
							if(!empty($tmp)) $status_sale_id = $tmp[$clsProperty->pkey];
						}
						if(!empty($status_leader_name) && $status_leader_name != 'Chưa TT'){
							$tmp = $clsProperty->getByCond("`property_type`='COMMISSION_PAYMENT_STATUS' AND (`property_code`='".strtolower($status_leader_name)."' OR `slug`='{$clsISO->replaceSpace($status_leader_name)}')");
							if(!empty($tmp)) $status_leader_id = $tmp[$clsProperty->pkey];
						}
						if(!empty($status_sale_dir_name) && $status_sale_dir_name != 'Chưa TT'){
							$tmp = $clsProperty->getByCond("`property_type`='COMMISSION_PAYMENT_STATUS' AND (`property_code`='".strtolower($status_sale_dir_name)."' OR `slug`='{$clsISO->replaceSpace($status_sale_dir_name)}')");
							if(!empty($tmp)) $status_sale_dir_id = $tmp[$clsProperty->pkey];
						}
						if(!empty($status_project_dir_name) && $status_project_dir_name != 'Chưa TT'){
							$tmp = $clsProperty->getByCond("`property_type`='COMMISSION_PAYMENT_STATUS' AND (`property_code`='".strtolower($status_project_dir_name)."' OR `slug`='{$clsISO->replaceSpace($status_project_dir_name)}')");
							if(!empty($tmp)) $status_project_dir_id = $tmp[$clsProperty->pkey];
						}
						if(!empty($status_club_manager_name) && $status_club_manager_name != 'Chưa TT'){
							$tmp = $clsProperty->getByCond("`property_type`='COMMISSION_PAYMENT_STATUS' AND (`property_code`='".strtolower($status_club_manager_name)."' OR `slug`='{$clsISO->replaceSpace($status_club_manager_name)}')");
							if(!empty($tmp)) $status_club_manager_id = $tmp[$clsProperty->pkey];
						}
						##
						$stock_code = strtoupper($stock_code);
						$stock_code = preg_replace('/\s+/', '', $stock_code);
						$tmp = $this->getByCond("`order_no`='{$order_no}' AND `quarter_id`='{$row_quarter_id}' AND `stock_code`='{$stock_code}'");
						if(empty($tmp) && $is_year_layout == 1){
							// STT trên sheet bị đánh lại (chèn/xóa dòng giữa năm) — kỳ + mã căn còn DUY NHẤT thì vẫn nhận ra dòng cũ
							$_same_cond = "`is_trash`=0 AND `quarter_id`='{$row_quarter_id}' AND `stock_code`='{$stock_code}'";
							if($this->countItem($_same_cond) == 1){
								$tmp = $this->getByCond($_same_cond);
							}
						}
						// $clsISO->print_pre($tmp); die();
						if(!empty($tmp)){
							$list_commission_found[] = $this->pkey;
							$more_information = $tmp['more_information'];
							$more_information = $clsISO->to_array_json($more_information);
							$more_information['contract_date'] = $contract_date;
							$more_information['stock_code'] = $stock_code;
							$more_information['bedroom_id'] = $bedroom_id;
							$more_information['bedroom_name'] = $bedroom_name;
							$more_information['project_id'] = $project_id;
							$more_information['project_name'] = $project_name; 
							$more_information['agency_id'] = $agency_id;
							$more_information['agency_name'] = $agency_name;
							$more_information['contract_total'] = $contract_total;
							$more_information['contract_comm_base'] = $contract_comm_base;
							$more_information['commission_rate'] = $commission_rate;
							$more_information['commission_amount'] = $commission_amount;
							$more_information['sales_bonus_amount_in'] = $sales_bonus_amount_in; // Thưởng sales vào
							$more_information['agent_bonus_amount_in'] = $agent_bonus_amount_in;
							$more_information['vinclub_discount_amount'] = $vinclub_discount_amount;
							$more_information['star_club_bonus_amount'] = $star_club_bonus_amount;
							$more_information['project_dir_bonus_amount_in'] = $project_dir_bonus_amount_in;
							$more_information['marketing_bonus_amount'] = $marketing_bonus_amount;
							$more_information['invest_advance_amount'] = $invest_advance_amount;
							$more_information['total_amount_in'] = $total_amount_in;
							$more_information['status_agent_id'] = $status_agent_id;
							
							$more_information['staff_id'] = $staff_id;
							$more_information['staff_name'] = $staff_name;
							$more_information['fund_name'] = $fund_name;
							$more_information['sales_commission_rate'] = $sales_commission_rate;
							$more_information['sales_commission_amount'] = $sales_commission_amount;
							$more_information['sales_bonus_amount_out'] = $sales_bonus_amount_out;
							$more_information['agent_bonus_amount_out'] = $agent_bonus_amount_out;
							$more_information['sales_total_amount'] = $sales_total_amount;
							$more_information['tax_vat_amount'] = $tax_vat_amount;
							$more_information['personal_tax_amount'] = $personal_tax_amount;
							$more_information['advance_paid_amount'] = $advance_paid_amount;
							$more_information['sales_total_unpaid_amount'] = $sales_total_unpaid_amount;
							$more_information['status_sale_id'] = $status_sale_id;
							# TPKD
							$more_information['leader_commission_rate'] = $leader_commission_rate;
							$more_information['leader_commission_amount'] = $leader_commission_amount;
							$more_information['leader_commission_bonus_amount'] = $leader_commission_bonus_amount;
							$more_information['leader_total_amount'] = $leader_total_amount;
							$more_information['status_leader_id'] = $status_leader_id;
							# GĐKD
							$more_information['sale_dir_commission_rate'] = $sale_dir_commission_rate;
							$more_information['sale_dir_commission_amount'] = $sale_dir_commission_amount;
							$more_information['sale_dir_commission_bonus_amount'] = $sale_dir_commission_bonus_amount;
							$more_information['sale_dir_total_amount'] = $sale_dir_total_amount;
							$more_information['status_sale_dir_id'] = $status_sale_dir_id;
							#- PKD
							$more_information['pkd_commission_rate'] = $pkd_commission_rate;
							$more_information['pkd_commission_amount'] = $pkd_commission_amount;
							$more_information['pkd_support_amount'] = $pkd_support_amount;
							$more_information['pkd_bonus_amount'] = $pkd_bonus_amount;
							$more_information['pkd_total_amount'] = $pkd_total_amount;
							# CN CLB
							$more_information['club_manager_id'] = $club_manager_id;
							$more_information['club_manager_name'] = $club_manager_name;
							$more_information['club_manager_amount'] = $club_manager_amount;
							$more_information['status_club_manager_id'] = $status_club_manager_id;
							// $more_information['receivable_amount'] = $receivable_amount;
							# GĐKD
							$more_information['project_dir_commission_rate'] = $project_dir_commission_rate;
							$more_information['project_dir_commission_amount'] = $project_dir_commission_amount;
							$more_information['project_dir_bonus_amount_out'] = $project_dir_bonus_amount_out;
							$more_information['project_dir_total_amount'] = $project_dir_total_amount;
							$more_information['status_project_dir_id'] = $status_project_dir_id;
							
							$more_information['agent_commision_rate'] = $agent_commision_rate;
							$more_information['agent_commision_amount'] = $agent_commision_amount;
							$more_information['agent_bonus_amount'] = $agent_bonus_amount;
							$more_information['agent_total_amount'] = $agent_total_amount;
							
							$more_information['total_amount_out'] = $total_amount_out;
							$more_information['agent_receivable_amount'] = $agent_receivable_amount;
							$more_information['interest_amount'] = $interest_amount;
							$more_information['extra_commission_rate'] = $extra_commission_rate;
							$more_information['extra_commission_amount'] = $extra_commission_amount;
							
							$more_information['project_id'] = $project_id;
							$more_information['block_id'] = $block_id;
							$more_information['notes'] = $notes;
							if($is_year_layout == 1){
								// Các cột riêng của layout file năm (đối chiếu CĐT, GĐ Vùng, PKD, giữ lại...)
								$more_information['month_no'] = $month_no;
								$more_information['reconcile_status_name'] = $reconcile_status_name;
								$more_information['reconcile_rate'] = $reconcile_rate;
								$more_information['support_amount_in'] = $support_amount_in;
								$more_information['deduct_amount_in'] = $deduct_amount_in;
								$more_information['reconciled_amount_in'] = $reconciled_amount_in;
								$more_information['remain_amount_in'] = $remain_amount_in;
								$more_information['sales_deduct_amount'] = $sales_deduct_amount;
								$more_information['sales_reconciled_amount'] = $sales_reconciled_amount;
								$more_information['regional_dir_commission_rate'] = $regional_dir_commission_rate;
								$more_information['regional_dir_commission_amount'] = $regional_dir_commission_amount;
								$more_information['status_pkd_name'] = $status_pkd_name;
								$more_information['company_hold_amount'] = $company_hold_amount;
								$more_information['reconcile90_status'] = $reconcile90_status;
								$more_information['reconcile90_rate'] = $reconcile90_rate;
							}
							##
							// $clsISO->print_pre($more_information); die();
							$billing_id = 0; $field = "{$clsBilling->pkey}";
							$oneBilling = $clsBilling->getByCond("`is_cancel`=0 AND TRIM(`stock_code`)='{$stock_code}' AND `project_id`='{$project_id}'", $field);
							if(empty($oneBilling)){
								$oneBilling = $clsBilling->getByCond("`is_cancel`=0 AND TRIM(`stock_code`)='{$stock_code}'", $field);
							}
							if(!empty($oneBilling)) $billing_id = $oneBilling[$clsBilling->pkey];
							$_upd_fields = array(
								// 'stock_code' => $stock_code,
								// 'quarter_id' => $quarter_id,
								// 'contract_date' => $contract_date,
								'project_id' => $project_id,
								'billing_id' => $billing_id,
								'status_agent_id' => $status_agent_id,
								'status_sale_id' => $status_sale_id,
								'status_leader_id' => $status_leader_id,
								'status_sale_dir_id' => $status_sale_dir_id,
								'status_project_dir_id' => $status_project_dir_id,
								'status_club_manager_id' => $status_club_manager_id,
								'contract_total' => $contract_total,
								'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
								'upd_date' => time(),
								'user_id_update' => $profile_id
							);
							if($is_year_layout == 1){
								$_upd_fields['order_no'] = $order_no; // STT có thể bị đánh lại trên sheet
							}
							if($this->updateOne($tmp[$this->pkey], $_upd_fields)){
								/*if($stock_code=='PR20204'){
									print_r("xxx"); die();
								}*/
								$msg = "_success";
								if($is_year_layout == 1){
									$found_by_quarter[$row_quarter_id][] = (int) $tmp[$this->pkey];
								}
							} else {
								$errors_arrs[] = $stock_code;
								if($is_year_layout == 1){
									$sweep_block[$row_quarter_id] = 1; // ghi lỗi → không dọn kỳ này, tránh xóa nhầm dòng thật
								}
							}
						} else {
							$more_information = array();
							$more_information['contract_date'] = $contract_date;
							$more_information['stock_code'] = $stock_code;
							$more_information['bedroom_id'] = $bedroom_id;
							$more_information['bedroom_name'] = $bedroom_name;
							$more_information['project_id'] = $project_id;
							$more_information['project_name'] = $project_name; 
							$more_information['agency_id'] = $agency_id;
							$more_information['agency_name'] = $agency_name;
							$more_information['contract_total'] = $contract_total;
							$more_information['contract_comm_base'] = $contract_comm_base;
							$more_information['commission_rate'] = $commission_rate;
							$more_information['commission_amount'] = $commission_amount;
							$more_information['sales_bonus_amount_in'] = $sales_bonus_amount_in; // Thưởng sales vào
							$more_information['agent_bonus_amount_in'] = $agent_bonus_amount_in;
							$more_information['vinclub_discount_amount'] = $vinclub_discount_amount;
							$more_information['star_club_bonus_amount'] = $star_club_bonus_amount;
							$more_information['project_dir_bonus_amount_in'] = $project_dir_bonus_amount_in;
							$more_information['marketing_bonus_amount'] = $marketing_bonus_amount;
							$more_information['invest_advance_amount'] = $invest_advance_amount;
							$more_information['total_amount_in'] = $total_amount_in;
							$more_information['status_agent_id'] = $status_agent_id;
							
							$more_information['staff_id'] = $staff_id;
							$more_information['staff_name'] = $staff_name;
							$more_information['fund_name'] = $fund_name;
							$more_information['sales_commission_rate'] = $sales_commission_rate;
							$more_information['sales_commission_amount'] = $sales_commission_amount;
							$more_information['sales_bonus_amount_out'] = $sales_bonus_amount_out;
							$more_information['agent_bonus_amount_out'] = $agent_bonus_amount_out;
							$more_information['sales_total_amount'] = $sales_total_amount;
							$more_information['tax_vat_amount'] = $tax_vat_amount;
							$more_information['personal_tax_amount'] = $personal_tax_amount;
							$more_information['advance_paid_amount'] = $advance_paid_amount;
							$more_information['sales_total_unpaid_amount'] = $sales_total_unpaid_amount;
							$more_information['status_sale_id'] = $status_sale_id;
							# TPKD
							$more_information['leader_commission_rate'] = $leader_commission_rate;
							$more_information['leader_commission_amount'] = $leader_commission_amount;
							$more_information['leader_commission_bonus_amount'] = $leader_commission_bonus_amount;
							$more_information['leader_total_amount'] = $leader_total_amount;
							$more_information['status_leader_id'] = $status_leader_id;
							# GĐKD
							$more_information['sale_dir_commission_rate'] = $sale_dir_commission_rate;
							$more_information['sale_dir_commission_amount'] = $sale_dir_commission_amount;
							$more_information['sale_dir_commission_bonus_amount'] = $sale_dir_commission_bonus_amount;
							$more_information['sale_dir_total_amount'] = $sale_dir_total_amount;
							$more_information['status_sale_dir_id'] = $status_sale_dir_id;
							#- PKD
							$more_information['pkd_commission_rate'] = $pkd_commission_rate;
							$more_information['pkd_commission_amount'] = $pkd_commission_amount;
							$more_information['pkd_support_amount'] = $pkd_support_amount;
							$more_information['pkd_bonus_amount'] = $pkd_bonus_amount;
							$more_information['pkd_total_amount'] = $pkd_total_amount;
							# CN CLB
							$more_information['club_manager_id'] = $club_manager_id;
							$more_information['club_manager_name'] = $club_manager_name;
							$more_information['club_manager_amount'] = $club_manager_amount;
							$more_information['status_club_manager_id'] = $status_club_manager_id;
							// $more_information['receivable_amount'] = $receivable_amount;
							# GĐKD
							$more_information['project_dir_commission_rate'] = $project_dir_commission_rate;
							$more_information['project_dir_commission_amount'] = $project_dir_commission_amount;
							$more_information['project_dir_bonus_amount_out'] = $project_dir_bonus_amount_out;
							$more_information['project_dir_total_amount'] = $project_dir_total_amount;
							$more_information['status_project_dir_id'] = $status_project_dir_id;
							
							$more_information['agent_commision_rate'] = $agent_commision_rate;
							$more_information['agent_commision_amount'] = $agent_commision_amount;
							$more_information['agent_bonus_amount_out'] = $agent_bonus_amount_out;
							$more_information['agent_total_amount'] = $agent_total_amount;
							$more_information['extra_commission_rate'] = $extra_commission_rate;
							$more_information['extra_commission_amount'] = $extra_commission_amount;
							
							$more_information['total_amount_out'] = $total_amount_out;
							$more_information['agent_receivable_amount'] = $agent_receivable_amount;
							$more_information['interest_amount'] = $interest_amount;
								
							$more_information['project_id'] = $project_id;
							$more_information['block_id'] = $block_id;
							$more_information['notes'] = $notes;
							if($is_year_layout == 1){
								// Các cột riêng của layout file năm (đối chiếu CĐT, GĐ Vùng, PKD, giữ lại...)
								$more_information['month_no'] = $month_no;
								$more_information['reconcile_status_name'] = $reconcile_status_name;
								$more_information['reconcile_rate'] = $reconcile_rate;
								$more_information['support_amount_in'] = $support_amount_in;
								$more_information['deduct_amount_in'] = $deduct_amount_in;
								$more_information['reconciled_amount_in'] = $reconciled_amount_in;
								$more_information['remain_amount_in'] = $remain_amount_in;
								$more_information['sales_deduct_amount'] = $sales_deduct_amount;
								$more_information['sales_reconciled_amount'] = $sales_reconciled_amount;
								$more_information['regional_dir_commission_rate'] = $regional_dir_commission_rate;
								$more_information['regional_dir_commission_amount'] = $regional_dir_commission_amount;
								$more_information['status_pkd_name'] = $status_pkd_name;
								$more_information['company_hold_amount'] = $company_hold_amount;
								$more_information['reconcile90_status'] = $reconcile90_status;
								$more_information['reconcile90_rate'] = $reconcile90_rate;
							}
							#
							$billing_id = 0; $field = "{$clsBilling->pkey}";
							$oneBilling = $clsBilling->getByCond("`is_cancel`=0 AND TRIM(`stock_code`)='{$stock_code}' AND `project_id`='{$project_id}'", $field);
							if(empty($oneBilling)){
								$oneBilling = $clsBilling->getByCond("`is_cancel`=0 AND TRIM(`stock_code`)='{$stock_code}'", $field);
							}
							if(!empty($oneBilling)) $billing_id = $oneBilling[$clsBilling->pkey];
							// Insert
							$commission_id = $this->getMaxId();
							if($this->insert(array(
								$this->pkey => $commission_id,
								'stock_code' => $stock_code,
								'quarter_id' => $row_quarter_id,
								'order_no' => $order_no,
								'contract_date' => $contract_date,
								'project_id' => $project_id,
								'billing_id' => $billing_id,
								'status_agent_id' => $status_agent_id,
								'status_sale_id' => $status_sale_id,
								'status_leader_id' => $status_leader_id,
								'status_sale_dir_id' => $status_sale_dir_id,
								'status_project_dir_id' => $status_project_dir_id,
								'club_manager_id' => $club_manager_id,
								'status_club_manager_id' => $status_club_manager_id,
								'contract_total' => $contract_total,
								'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE),
								'reg_date' => time(),
								'upd_date' => time(),
								'user_id' => $profile_id,
								'user_id_update' => $profile_id
							))){
								$msg = "_success";
								$list_commission_found[] = $commission_id;
								if($is_year_layout == 1){
									$found_by_quarter[$row_quarter_id][] = (int) $commission_id;
								}
							} else {
								$errors_arrs[] = $stock_code;	
								if($is_year_layout == 1){
									$sweep_block[$row_quarter_id] = 1; // ghi lỗi → không dọn kỳ này, tránh xóa nhầm dòng thật
								}
							}
						}
					}
				}
			}
			// Xoá dòng không nằm trong danh sách
			if($is_year_layout == 1){
				// File năm: dọn theo TỪNG KỲ có trong dữ liệu — đồng thời tự dọn dòng parse sai của lần crawl layout cũ
				foreach($found_by_quarter as $_qk => $_ids){
					if($sweep_block_all == 0 && empty($sweep_block[$_qk]) && !empty($_ids)){
						$this->deleteByCond("`is_trash`=0 AND `quarter_id`='{$_qk}' AND `{$this->pkey}` NOT IN (".implode(',', $_ids).")");
					}
				}
			} else if(!empty($list_commission_found)){
				$this->deleteByCond("`is_trash`=0 AND `quarter_id`='{$quarter_id}' 
					AND `{$this->pkey}` NOT IN (".implode(',',$list_commission_found).")");
			}
		}
		// Return
		return $msg;
	}

	// ==== CRAWL BCHH: tổng hoa hồng theo NHÂN VIÊN (tab 'BCHH': Mã NV|Tên NV|Tổng tiền|Đã trả|Còn tồn) ====
	// 2 việc: (1) bổ sung NV đang làm PKD còn thiếu vào sheet (Mã+Tên) để kế toán điền số;
	// (2) đọc 3 số kế toán đã điền theo MÃ NV → lưu default_profile.more_information['commission_summary'].
	// Số do kế toán điền tay (KHÔNG tự tính). $dry=true chỉ liệt kê, không ghi sheet/DB.
	function crawlStaffCommissionSummary($spreadsheetId, $sheet_name = 'BCHH', $dry = false){
		global $clsISO;
		$clsProfile = new Profile();
		if(empty($spreadsheetId)){
			return array('msg' => '_error_thieu_spreadsheetId');
		}
		require_once(DIR_INCLUDES.'/json_master/autoload.php');
		require_once(DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
		$client = new Google_Client();
		$client->setClientId(GOOGLE_CLIENT_ID);
		$client->setClientSecret(GOOGLE_CLIENT_SECRET);
		$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
		$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
		$service = new Google_Service_Sheets($client);
		// Đọc sheet hiện có
		$resp = $service->spreadsheets_values->get($spreadsheetId, $sheet_name);
		$rows = $resp->getValues();
		if(empty($rows)){
			return array('msg' => '_error_sheet_rong');
		}
		$total_rows = count($rows);
		// Map MÃ đã có trong sheet (bỏ header dòng 0)
		$existing = array();
		for($i = 1; $i < $total_rows; $i++){
			$code = isset($rows[$i][0]) ? trim($rows[$i][0]) : '';
			if($code != ''){
				$existing[$code] = 1;
			}
		}
		// (1) BỔ SUNG: NV đang làm PKD chưa có mã trong sheet
		$field = "`{$clsProfile->pkey}`,`code`,`full_name`,`first_name`,`last_name`";
		$gate = "`is_trash`=0 and `is_active`=1 and `status_id`='" . _STATUS_STAFF_ON_ID . "' and `code`<>''";
		$gate .= " and (`list_department_id` like '%|" . _DEPARTMENT_SALE_ID . "|%' or `department_id`='" . _DEPARTMENT_SALE_ID . "')";
		$staffs = $clsProfile->getAll($gate . " ORDER BY `full_name` ASC", $field);
		$to_append = array();
		if(!empty($staffs)){
			foreach($staffs as $s){
				$code = trim($s['code']);
				if($code == '' || isset($existing[$code])){
					continue;
				}
				$ten = preg_replace('/\s+/', ' ', trim($clsProfile->getFullName((int) $s[$clsProfile->pkey], $s)));
				$to_append[] = array($code, $ten);
				$existing[$code] = 1; // tránh trùng nếu code lặp trong DS
			}
		}
		if(!empty($to_append) && !$dry){
			// Ghi thêm vào cuối sheet (chỉ cột A+B; cột tiền để trống cho kế toán nhập)
			$range_append = $sheet_name . '!A' . ($total_rows + 1);
			$body = new Google_Service_Sheets_ValueRange(array('values' => $to_append));
			$service->spreadsheets_values->update($spreadsheetId, $range_append, $body, array('valueInputOption' => 'RAW'));
			// Đọc lại để bước 2 gồm cả dòng mới (số trống)
			$resp = $service->spreadsheets_values->get($spreadsheetId, $sheet_name);
			$rows = $resp->getValues();
			$total_rows = count($rows);
		}
		// (2) CẬP NHẬT DB: đọc 3 số theo MÃ → profile.more_information['commission_summary']
		$updated = 0;
		$notfound = array();
		$now = time();
		for($i = 1; $i < $total_rows; $i++){
			$row = $rows[$i];
			$code = isset($row[0]) ? trim($row[0]) : '';
			if($code == ''){
				continue;
			}
			$opening = $clsISO->processSmartNumber(isset($row[2]) ? trim($row[2]) : '', 0); // Số dư đầu kỳ (cột C)
			$total = $clsISO->processSmartNumber(isset($row[3]) ? trim($row[3]) : '', 0);
			$paid = $clsISO->processSmartNumber(isset($row[4]) ? trim($row[4]) : '', 0);
			$remain = $clsISO->processSmartNumber(isset($row[5]) ? trim($row[5]) : '', 0);
			$one = $clsProfile->getByCond("`is_trash`=0 and `code`='" . addslashes($code) . "'", "`{$clsProfile->pkey}`,`more_information`");
			if(empty($one)){
				$notfound[] = $code;
				continue;
			}
			if($dry){
				$updated++;
				continue;
			}
			$pid = (int) $one[$clsProfile->pkey];
			$mi = $clsISO->to_array_json($one['more_information']);
			if(!is_array($mi)){
				$mi = array();
			}
			$mi['commission_summary'] = array(
				'opening' => $opening,
				'total' => $total,
				'paid' => $paid,
				'remain' => $remain,
				'updated' => $now
			);
			$clsProfile->updateOne($pid, array('more_information' => json_encode($mi, JSON_UNESCAPED_UNICODE)));
			$updated++;
		}
		return array(
			'msg' => '_success',
			'dry' => $dry ? 1 : 0,
			'appended' => count($to_append),
			'append_list' => $to_append,
			'updated' => $updated,
			'notfound' => $notfound
		);
	}
	// ==== ENGINE ĐỌC THEO MẪU FILE (layout-template) — thay 3 nhánh if quarter_id ====
	// Mỗi mẫu = data thuần: header/range/dup/quarter + fields{col,type} + computed(recipe). Thêm định dạng mới = thêm 1 entry.
	function getLayouts(){
		return array(
			// Mẫu-NĂM: file 1 tab cả năm (2025 CS, 2026 CS, và file-năm sau). Tự suy quý theo cột Tháng.
			'year_std' => array(
				'title' => 'File năm (2025 CS / 2026 CS)',
				'kind' => 'year',
				'start' => array('mode' => 'scan', 'marker' => 'STT', 'col' => 0, 'offset' => 4),
				'range' => '__auto_year__',
				'dup' => array('col' => 3, 'equals' => '1'),
				'quarter' => array('mode' => 'by_date', 'date_key' => 'contract_date'), // suy quý theo NGÀY KÝ (cột Tháng thưa ở 2025 CS)
				'year_clamp' => 1, // file NĂM chỉ chứa đúng năm kỳ — dòng năm khác bị bỏ (chống sweep xóa nhầm data năm khác)
				'fields' => array(
					'order_no' => array('col' => 0, 'type' => 'text'),
					'month_no' => array('col' => 1, 'type' => 'int'),
					'contract_date' => array('col' => 2, 'type' => 'date'),
					'stock_code' => array('col' => 4, 'type' => 'text'),
					'bedroom_name' => array('col' => 5, 'type' => 'text'),
					'project_name' => array('col' => 6, 'type' => 'text'),
					'fund_name' => array('col' => 7, 'type' => 'text'),
					'agency_name' => array('col' => 8, 'type' => 'text'),
					'reconcile_status_name' => array('col' => 9, 'type' => 'text'),
					'reconcile_rate' => array('col' => 10, 'type' => 'rate'),
					'contract_total' => array('col' => 11, 'type' => 'money'),
					'contract_comm_base' => array('col' => 12, 'type' => 'money'),
					'commission_rate' => array('col' => 13, 'type' => 'rate'),
					'sales_bonus_amount_in' => array('col' => 14, 'type' => 'money'),
					'agent_bonus_amount_in' => array('col' => 15, 'type' => 'money'),
					'support_amount_in' => array('col' => 16, 'type' => 'money'),
					'deduct_amount_in' => array('col' => 17, 'type' => 'money'),
					'reconciled_amount_in' => array('col' => 18, 'type' => 'money'),
					'total_amount_in' => array('col' => 19, 'type' => 'money'),
					'invest_advance_amount' => array('col' => 20, 'type' => 'money'),
					'remain_amount_in' => array('col' => 21, 'type' => 'money'),
					'status_agent_name' => array('col' => 22, 'type' => 'status'),
					'staff_name' => array('col' => 23, 'type' => 'text'),
					'sales_commission_rate' => array('col' => 24, 'type' => 'rate'),
					'sales_bonus_amount_out' => array('col' => 25, 'type' => 'money'),
					'agent_bonus_amount_out' => array('col' => 26, 'type' => 'money'),
					'sales_deduct_amount' => array('col' => 27, 'type' => 'money'),
					'sales_reconciled_amount' => array('col' => 28, 'type' => 'money'),
					'sales_total_amount' => array('col' => 29, 'type' => 'money'),
					'advance_paid_amount' => array('col' => 30, 'type' => 'money'),
					'sales_total_unpaid_amount' => array('col' => 31, 'type' => 'money'),
					'status_sales_name' => array('col' => 32, 'type' => 'status'),
					'sale_dir_commission_rate' => array('col' => 33, 'type' => 'rate'),
					'sale_dir_commission_amount' => array('col' => 34, 'type' => 'money'),
					'status_sale_dir_name' => array('col' => 35, 'type' => 'status'),
					'regional_dir_commission_rate' => array('col' => 36, 'type' => 'rate'),
					'regional_dir_commission_amount' => array('col' => 37, 'type' => 'money'),
					'pkd_total_amount' => array('col' => 38, 'type' => 'money'),
					'status_pkd_name' => array('col' => 39, 'type' => 'text'),
					'project_dir_commission_rate' => array('col' => 40, 'type' => 'rate'),
					'project_dir_commission_amount' => array('col' => 41, 'type' => 'money'),
					'project_dir_bonus_amount_out' => array('col' => 42, 'type' => 'money'),
					'project_dir_total_amount' => array('col' => 43, 'type' => 'money'),
					'status_project_dir_name' => array('col' => 44, 'type' => 'status'),
					'total_amount_out' => array('col' => 45, 'type' => 'money'),
					'company_hold_amount' => array('col' => 46, 'type' => 'money'),
					'reconcile90_status' => array('col' => 48, 'type' => 'text'),
					'reconcile90_rate' => array('col' => 49, 'type' => 'rate')
				),
				'computed' => array(
					'sale_dir_total_amount' => array('add' => array('sale_dir_commission_amount'))
				)
			),
			// Mẫu-NĂM 2024: file lũy kế 1 tab, cột theo cặp tỷ-lệ→thành-tiền, 1 cột tình trạng. Suy quý theo ngày.
			'cumulative_2024' => array(
				'title' => 'File 2024 (lũy kế)',
				'kind' => 'year',
				'start' => array('mode' => 'scan', 'marker' => 'STT', 'col' => 0, 'offset' => 1),
				'range' => '__auto_year__',
				'dup' => null,
				'quarter' => array('mode' => 'by_date', 'date_key' => 'contract_date'),
				'staff_strip_code' => 1,
				'fields' => array(
					'order_no' => array('col' => 0, 'type' => 'text'),
					'contract_date' => array('col' => 1, 'type' => 'date'),
					'staff_name' => array('col' => 2, 'type' => 'text'),
					'stock_code' => array('col' => 3, 'type' => 'text'),
					'agency_name' => array('col' => 4, 'type' => 'text'),
					'contract_total' => array('col' => 5, 'type' => 'money'),
					'contract_comm_base' => array('col' => 6, 'type' => 'money'),
					'commission_rate' => array('col' => 7, 'type' => 'rate'),
					'commission_amount' => array('col' => 8, 'type' => 'money'),
					'sales_bonus_amount_in' => array('col' => 9, 'type' => 'money'),
					'agent_bonus_amount_in' => array('col' => 10, 'type' => 'money'),
					'support_amount_in' => array('col' => 11, 'type' => 'money'),
					'tax_vat_amount' => array('col' => 12, 'type' => 'money'),
					'personal_tax_amount' => array('col' => 13, 'type' => 'money'),
					'total_amount_in' => array('col' => 14, 'type' => 'money'),
					'invest_advance_amount' => array('col' => 15, 'type' => 'money'),
					'agent_commision_rate' => array('col' => 18, 'type' => 'rate'),
					'agent_commision_amount' => array('col' => 19, 'type' => 'money'),
					'pkd_commission_rate' => array('col' => 23, 'type' => 'rate'),
					'pkd_commission_amount' => array('col' => 24, 'type' => 'money'),
					'sales_commission_rate' => array('col' => 28, 'type' => 'rate'),
					'sales_commission_amount' => array('col' => 29, 'type' => 'money'),
					'sales_bonus_amount_out' => array('col' => 30, 'type' => 'money'),
					'leader_commission_rate' => array('col' => 32, 'type' => 'rate'),
					'leader_commission_amount' => array('col' => 33, 'type' => 'money'),
					'project_dir_commission_rate' => array('col' => 36, 'type' => 'rate'),
					'project_dir_commission_amount' => array('col' => 37, 'type' => 'money'),
					'bedroom_name' => array('col' => 40, 'type' => 'text'),
					'advance_paid_amount' => array('col' => 41, 'type' => 'money'),
					'status_sales_name' => array('col' => 42, 'type' => 'status'),
					'notes' => array('col' => 43, 'type' => 'text')
				),
				'computed' => array(
					'sales_total_amount' => array('add' => array('sales_commission_amount', 'sales_bonus_amount_out')),
					'leader_total_amount' => array('add' => array('leader_commission_amount')),
					'pkd_total_amount' => array('add' => array('pkd_commission_amount')),
					'project_dir_total_amount' => array('add' => array('project_dir_commission_amount')),
					'total_amount_out' => array('add' => array('sales_total_amount', 'leader_total_amount', 'project_dir_total_amount'))
				)
			)
		);
	}
	// Nhãn mẫu theo loại (year/quarter) cho dropdown — lọc theo mode.
	function getLayoutOptions($kind){
		$out = array();
		foreach($this->getLayouts() as $name => $def){
			if($def['kind'] == $kind){
				$out[$name] = $def['title'];
			}
		}
		return $out;
	}
	// Suy mẫu mặc định khi slot chưa chọn: mode year → year_std; mode quarter → '' (chưa có mẫu-quý).
	function defaultLayoutFor($quarter_id){
		return (strpos((string) $quarter_id, 'Y') === 0) ? 'year_std' : '';
	}
	// Danh sách biến khối chung crawl dùng (whitelist cho extract) — default 0/''.
	function _crawlVarDefaults(){
		$keys_text = array('order_no','stock_code','staff_name','agency_name','project_name','bedroom_name','fund_name',
			'status_agent_name','status_sales_name','status_leader_name','status_sale_dir_name','status_project_dir_name',
			'status_club_manager_name','status_pkd_name','club_manager_name','reconcile_status_name','reconcile90_status','notes');
		$keys_num = array('contract_date','month_no','contract_total','contract_comm_base','commission_rate','commission_amount',
			'sales_bonus_amount_in','agent_bonus_amount_in','vinclub_discount_amount','star_club_bonus_amount','project_dir_bonus_amount_in',
			'marketing_bonus_amount','invest_advance_amount','support_amount_in','deduct_amount_in','reconciled_amount_in','remain_amount_in',
			'total_amount_in','sales_commission_rate','sales_commission_amount','sales_bonus_amount_out','agent_bonus_amount_out',
			'sales_deduct_amount','sales_reconciled_amount','sales_total_amount','tax_vat_amount','personal_tax_amount','advance_paid_amount',
			'sales_total_unpaid_amount','leader_commission_rate','leader_commission_amount','leader_commission_bonus_amount','leader_total_amount',
			'sale_dir_commission_rate','sale_dir_commission_amount','sale_dir_commission_bonus_amount','sale_dir_total_amount',
			'regional_dir_commission_rate','regional_dir_commission_amount','pkd_commission_rate','pkd_commission_amount','pkd_support_amount',
			'pkd_bonus_amount','pkd_total_amount','club_manager_amount','project_dir_commission_rate','project_dir_commission_amount',
			'project_dir_bonus_amount_out','project_dir_total_amount','agent_commision_rate','agent_commision_amount','agent_bonus_amount',
			'agent_total_amount','total_amount_out','agent_receivable_amount','interest_amount','extra_commission_rate','extra_commission_amount',
			'reconcile_rate','reconcile90_rate','company_hold_amount');
		$vars = array();
		foreach($keys_text as $k){ $vars[$k] = ''; }
		foreach($keys_num as $k){ $vars[$k] = 0; }
		return $vars;
	}
	// Đánh giá công thức phái sinh (giữ parity màn acc): add/sub/base_pct/receivable. Đánh giá theo thứ tự khai báo.
	function evalComputed($recipes, $vars, $base){
		if(empty($recipes) || !is_array($recipes)){
			return $vars;
		}
		foreach($recipes as $key => $rc){
			if(isset($rc['const'])){
				$vars[$key] = $rc['const'];
			} else if(isset($rc['base_pct'])){
				$r = isset($vars[$rc['base_pct']]) ? (float) $vars[$rc['base_pct']] : 0;
				$vars[$key] = $base * $r / 100;
			} else if(isset($rc['receivable'])){
				$vars[$key] = round(((float) $vars['total_amount_in'] / 1.1) - (float) $vars['total_amount_out']);
			} else {
				$v = 0;
				if(!empty($rc['add'])){ foreach($rc['add'] as $f){ $v += isset($vars[$f]) ? (float) $vars[$f] : 0; } }
				if(!empty($rc['sub'])){ foreach($rc['sub'] as $f){ $v -= isset($vars[$f]) ? (float) $vars[$f] : 0; } }
				$vars[$key] = $v;
			}
		}
		return $vars;
	}
	// Đọc 1 dòng theo mẫu → trả các biến bare (whitelist) + quý + cờ skip/sweep. Khối chung crawl extract & lưu như cũ.
	function readRowByLayout($row, $def, $ctx){
		global $clsISO;
		$clsISO = is_object($clsISO) ? $clsISO : new ISO();
		$vars = $this->_crawlVarDefaults();
		foreach($def['fields'] as $key => $f){
			$c = (int) $f['col'];
			$raw = isset($row[$c]) ? trim($row[$c]) : '';
			$t = $f['type'];
			if($t == 'money'){
				$vars[$key] = $clsISO->processSmartNumber($raw, 0);
			} else if($t == 'rate'){
				$vars[$key] = $clsISO->convertToNumber($raw, 0);
			} else if($t == 'int'){
				$vars[$key] = (int) $raw;
			} else if($t == 'date'){
				$vars[$key] = !empty($raw) ? $clsISO->toTime($raw) : 0;
			} else {
				$vars[$key] = $raw;
			}
		}
		// Tách mã NV khỏi tên (vd '2024': 'FH0044-Đào Duy Kiên' → 'Đào Duy Kiên')
		if(!empty($def['staff_strip_code']) && $vars['staff_name'] != ''){
			if(preg_match('/^[A-Za-z0-9]+\s*-\s*(.+)$/u', $vars['staff_name'], $mm)){
				$vars['staff_name'] = trim($mm[1]);
			}
		}
		$vars = $this->evalComputed(isset($def['computed']) ? $def['computed'] : array(), $vars, (float) $vars['contract_comm_base']);
		// Suy quý
		$row_quarter_id = $ctx['quarter_id'];
		$block_all = 0;
		$block_sweep = 0;
		$bad_year = 0; // dòng ngày khác năm kỳ (file năm) hoặc năm vô lý → bỏ, KHÔNG tạo kỳ năm khác (chống sweep xóa nhầm)
		$got_quarter = 0;
		$qd = $def['quarter'];
		$has_data = ($vars['order_no'] != '' || $vars['stock_code'] != '');
		if($qd['mode'] == 'by_month'){
			$mo = (int) $vars[$qd['month_key']];
			if($mo >= 1 && $mo <= 12){
				$row_quarter_id = sprintf('Q%s_%s', (int) ceil($mo / 3), (int) $ctx['layout_year']);
				$got_quarter = 1;
			} else if($has_data){
				$block_all = 1; // dòng có data mà thiếu tháng → không dọn (tránh xóa nhầm)
			}
		} else if($qd['mode'] == 'by_date'){
			$ts = (int) $vars[$qd['date_key']];
			if($ts > 0){
				$_ry = (int) date('Y', $ts);
				if($_ry < 2000 || $_ry > 2100){
					$bad_year = 1; // năm vô lý (typo ngày) → bỏ dòng
				} else if(!empty($def['year_clamp']) && (int) $ctx['layout_year'] > 0 && $_ry != (int) $ctx['layout_year']){
					$bad_year = 1; // file NĂM chỉ chứa 1 năm — dòng năm khác là ngoại lai/typo → KHÔNG ghi vào kỳ năm khác
				} else {
					$row_quarter_id = sprintf('Q%s_%s', (int) ceil(((int) date('n', $ts)) / 3), $_ry);
					$got_quarter = 1;
				}
			}
		}
		// Lọc trùng: dòng bị loại chủ đích (dup != equals) → skip, KHÔNG chặn sweep
		$dup_intentional = 0;
		if(!empty($def['dup'])){
			$dv = isset($row[$def['dup']['col']]) ? trim($row[$def['dup']['col']]) : '';
			if($dv != $def['dup']['equals']){
				$dup_intentional = 1;
			}
		}
		$skip = 0;
		if($dup_intentional == 1 || $bad_year == 1 || $vars['stock_code'] == '' || (int) $vars['contract_date'] <= 0 || $got_quarter == 0){
			$skip = 1;
			// Dòng CÓ data nhưng KHÔNG suy được kỳ (ngày lỗi/thiếu, không phải loại chủ đích / ngoại lai năm khác)
			// → không biết thuộc quý nào → CHẶN TOÀN BỘ sweep lần crawl này (an toàn tuyệt đối, tránh xóa nhầm).
			if($dup_intentional == 0 && $bad_year == 0 && $has_data && $got_quarter == 0){
				$block_all = 1;
			}
		}
		return array('vars' => $vars, 'row_quarter_id' => $row_quarter_id, 'skip' => $skip, 'block_sweep' => $block_sweep, 'block_all' => $block_all);
	}
	// ==== Cấu hình crawl gom theo NĂM (mỗi năm 1 kiểu: Cả năm HOẶC 4 quý — không bao giờ cả hai) ====
	// Chống 'loạn': file năm và file quý của cùng 1 năm ghi đè lên cùng quarter_id → mode độc quyền chặn tận gốc.
	function _blankCrawlSlot(){
		return array('is_active' => 0, 'spreadsheetId' => '', 'sheet_name' => '', 'layout' => '');
	}
	function _blankCrawlYear(){
		$q = array();
		for($i = 1; $i <= 4; $i++){
			$q[$i] = $this->_blankCrawlSlot();
		}
		return array('mode' => '', 'year' => $this->_blankCrawlSlot(), 'q' => $q);
	}
	// Nhận cả schema CŨ (khoá phẳng Q1_2026 / Y2026) lẫn MỚI (khoá = năm) → trả mảng gom theo năm, mới→cũ.
	function normalizeConfig($raw){
		$years = array();
		if(empty($raw) || !is_array($raw)){
			return $years;
		}
		foreach($raw as $key => $val){
			if(!is_array($val)){
				continue;
			}
			if(preg_match('/^(\d{4})$/', (string) $key, $m) && isset($val['mode'])){
				$y = (int) $m[1];
				if(!isset($years[$y])){
					$years[$y] = $this->_blankCrawlYear();
				}
				$years[$y]['mode'] = ($val['mode'] == 'quarter') ? 'quarter' : 'year';
				if(isset($val['year']) && is_array($val['year'])){
					$years[$y]['year'] = array_merge($this->_blankCrawlSlot(), $val['year']);
				}
				for($i = 1; $i <= 4; $i++){
					if(isset($val['q'][$i]) && is_array($val['q'][$i])){
						$years[$y]['q'][$i] = array_merge($this->_blankCrawlSlot(), $val['q'][$i]);
					}
				}
			} else if(preg_match('/^Y(\d{4})$/', (string) $key, $m)){
				$y = (int) $m[1];
				if(!isset($years[$y])){
					$years[$y] = $this->_blankCrawlYear();
				}
				$years[$y]['year'] = array_merge($this->_blankCrawlSlot(), $val);
			} else if(preg_match('/^Q([1-4])_(\d{4})$/', (string) $key, $m)){
				$q = (int) $m[1];
				$y = (int) $m[2];
				if(!isset($years[$y])){
					$years[$y] = $this->_blankCrawlYear();
				}
				$years[$y]['q'][$q] = array_merge($this->_blankCrawlSlot(), $val);
			}
		}
		// Năm nạp từ schema cũ (mode rỗng) → suy: có id/bật ở slot năm = 'year', ngược lại nếu quý có gì = 'quarter'
		foreach($years as $y => $yr){
			if($years[$y]['mode'] == ''){
				if((int) $yr['year']['is_active'] == 1 || $yr['year']['spreadsheetId'] != ''){
					$years[$y]['mode'] = 'year';
				} else {
					$any_q = false;
					for($i = 1; $i <= 4; $i++){
						if((int) $yr['q'][$i]['is_active'] == 1 || $yr['q'][$i]['spreadsheetId'] != ''){
							$any_q = true;
							break;
						}
					}
					$years[$y]['mode'] = $any_q ? 'quarter' : 'year';
				}
			}
		}
		krsort($years); // năm mới → cũ
		return $years;
	}
	// Trả các job crawl ĐANG BẬT, tôn trọng mode (năm HOẶC quý, không bao giờ cả hai).
	function resolveCrawlJobs($years){
		$jobs = array();
		if(empty($years) || !is_array($years)){
			return $jobs;
		}
		foreach($years as $y => $yr){
			if($yr['mode'] == 'quarter'){
				for($i = 1; $i <= 4; $i++){
					$slot = $yr['q'][$i];
					if((int) $slot['is_active'] == 1 && !empty($slot['spreadsheetId'])){
						$jobs[] = array(
							'quarter_id' => sprintf('Q%s_%s', $i, $y),
							'spreadsheetId' => $slot['spreadsheetId'],
							'sheet_name' => isset($slot['sheet_name']) ? $slot['sheet_name'] : '',
								'layout' => isset($slot['layout']) ? $slot['layout'] : ''
						);
					}
				}
			} else {
				$slot = $yr['year'];
				if((int) $slot['is_active'] == 1 && !empty($slot['spreadsheetId'])){
					$jobs[] = array(
						'quarter_id' => sprintf('Y%s', $y),
						'spreadsheetId' => $slot['spreadsheetId'],
						'sheet_name' => isset($slot['sheet_name']) ? $slot['sheet_name'] : '',
						'layout' => isset($slot['layout']) ? $slot['layout'] : ''
					);
				}
			}
		}
		return $jobs;
	}
}