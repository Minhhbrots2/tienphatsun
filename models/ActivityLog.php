<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2014-2015 by Future Group.         # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
class ActivityLog extends dbBasic{
	function __construct(){
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."activitylog";
	}
	function addActivityLog($tbl,$action,$data=array()){
		global $profile_id,$oneProfile,$clsISO,$core;
		if(IS_ADMIN_PAGE == 0) {
			$_from = "front";
			$user_id = $profile_id;
		}else{
			$_from = "admin";
			$user_id = $core->_USER['user_id'];
			if($user_id == 57) {
				return 1;
			}
		}
		$content = $this->getContentLog($tbl,$data,$action);
//		$clsISO->print_pre($data);die;
		$title = $this->genTitle($tbl,$data);
		if(!empty($content)) {
			$this->insert(array(
				'tbl' 			=> $tbl,
				'content' 		=> $content,
				'profile_id' 	=> $user_id,
				'user_ip' 		=> $_SERVER['REMOTE_ADDR'],
				'_from'			=> $_from,
				'title' 		=> $title,
				'action'		=> $action,
				'reg_date' 		=> time()
			));
		}		
	}
	function getTitleProperty($data) {
		switch ($data['property_type']) {
			case "BANK_ACCOUNT" : 
				$title = "Tài khoản quỹ";
				$func_name = "tài khoản quỹ";
				break;
			case "BLOCK" : 
				$title = "Phân khu";
				if(!empty($data["title"]) && $data["title"] != ""){
					$func_name = "phân khu <strong>".$data["title"]."</strong>";
					if(!empty($data["title_project"]) && $data["title_project"] != ""){
						$func_name .= ", dự án <strong>".$data["title_project"]."</strong>";
					}
				}else{
					$func_name = "phân khu";
				}
				break;
			case "RANGE" : 
				$title = "Dãy phân khu";
				if(!empty($data["title"]) && $data["title"] != ""){
					$func_name = $type_name." <strong>".$data["title"]."</strong>";
					if(!empty($data["title_project"]) && $data["title_project"] != ""){
						$func_name .= ", phân khu <strong>".$data["title_block"]."</strong>";
					}						
					if(!empty($data["title_project"]) && $data["title_project"] != ""){
						$func_name .= ", dự án <strong>".$data["title_project"]."</strong>";
					}
				}
				break;
			case "_DIRECTION" : 
				$title = "[Cài đặt bảng hàng]Hướng nhà";
				$func_name .= "hướng nhà";
				break;
			case "_VIEW" : 
				$title = "[Cài đặt bảng hàng]Hướng nhìn";
				$func_name .= "hướng nhìn";
				break;
			case "_TYPE" : 
				$title = "[Cài đặt bảng hàng]Loại hình";
				$func_name .= "loại hình";
				break;
			case "_TYPE_VILLA" : 
				$title = "[Cài đặt bảng hàng]Loại hình thấp tầng";
				$func_name .= "loại hình thấp tầng";
				break;
			case "_BLOCK_TYPE" : 
				$title = "[Cài đặt bảng hàng]Loại hình phân khu";
				$func_name .= "loại hình phân khu";
				break;
			case "_BEDROOM" : 
				$title = "[Cài đặt bảng hàng]Số phòng ngủ";
				$func_name .= "số phòng ngu";
				break;
			case "_AGENCY" : 
				$title = "[Cài đặt bảng hàng] Đại lý";
				$func_name .= "đại lý";
				break;
			case "_PARTNER" : 
				$title = "[Cài đặt bảng hàng] đối tác";
				$func_name .= "đối tác";
				break;
			case "_STATUS" : 
				$title = "[Cài đặt bảng hàng]Tình trạng";
				$func_name .= "tình trạng";
				break;
			case "_FLOOR_TYPE" : 
				$title = "[Cài đặt bảng hàng]Loại tầng";
				$func_name .= "loại tầng";
				break;
			case "_STOCK_HOLD" : 
				$title = "[Cài đặt bảng hàng]Loại quỹ thấp tầng";
				$func_name .= "loại quỹ thấp tầng";
				break;
			case "_REGISTER_TYPE_MWF" : 
				$title = "[Cài đặt bảng hàng]Loại hình đăng ký MWF";
				$func_name .= "loại hình đăng ký MWF";
				break;
			case "_STATUS_STOCK_HUG" : 
				$title = "[Cài đặt bảng hàng]Tình trạng quỹ ôm";
				$func_name .= "tình trạng quỹ ôm";
				break;
			case "_STATUS_PAYMENT_PROGRESS" : 
				$title = "[Cài đặt bảng hàng]Tiến độ thanh toán";
				$func_name .= "tiến độ thanh toán";
				break;
			case "_COMPLETED_FLOOR" : 
				$title = "[Cài đặt bảng hàng]Hoàn thiện tầng";
				$func_name .= "hoàn thiện tầng";
				break;
			case "_MANAGEMENT_FEE_INCLUDED" : 
				$title = "[Cài đặt bảng hàng]Phí quản lý";
				$func_name .= "phí quản lý";
				break;
			case "_UTILITIES_PROJECT" : 
				$title = "[Cài đặt bảng hàng]Tiện ích dự án";
				$func_name .= "tiện ích dự án";
				break;
			case "_GROUP_RANGE_VHGG" : 
				$title = "[Cài đặt bảng hàng]Nhóm Phân Khu VHGG";
				$func_name .= "nhóm Phân Khu VHGG";
				break;
			case "THUCTHU" : 
				$title = "[Cài đặt Thu/Chi]Loại khoản thu";
				$func_name .= "loại khoản thu";
				break;
			case "THUCCHI" : 
				$title = "[Cài đặt Thu/Chi]Loại khoản chi";
				$func_name .= "loại khoản chi";
				break;
			case "VAT_TYPE" : 
				$title = "[Cài đặt Thu/Chi]Loại VAT";
				$func_name .= "loại VAT";
				break;
			case "VAT_STATUS" : 
				$title = "[Cài đặt Thu/Chi]Tình trạng VAT";
				$func_name .= "tình trạng VAT";
				break;
			case "COMMISSION_STATUS" : 
				$title = "[Cài đặt Thu/Chi]Tình trạng Hoa Hồng";
				$func_name .= "tình trạng Hoa Hồng";
				break;
			case "COMMISSION_PAYMENT_STATUS" : 
				$title = "[Cài đặt Thu/Chi]Tình trạng thanh toán Hoa Hồng";
				$func_name .= "tình trạng thanh toán Hoa Hồng";
				break;
			case "_OKRS_GROUP" : 
				$title = "[Cài đặt OKRs]Nhóm OKRs";
				$func_name .= "nhóm OKRs";
				break;
			case "_PERIOD" : 
				$title = "[Cài đặt OKRs]Chu kỳ OKRS";
				$func_name .= "chu kỳ OKRs";
				break;
			case "FINANCE" : 
				$title = "[Cài đặt OKRs]Tài chính";
				$func_name .= "tài chính";
				break;
			case "_UNIT" : 
				$title = "[Cài đặt OKRs]Đơn vị tính OKRs";
				$func_name .= "đơn vị tính OKRs";
				break;
			case "_PACKAGE" : 
				$title = "[Cài đặt Người dùng]Gói tài khoản MOC";
				$func_name .= "gói tài khoản MOC";
				break;
			case "_DEPARTMENT" : 
				$title = "[Cài đặt Người dùng]Phòng ban";
				$func_name .= "phòng ban";
				break;
			case "_ROLE" : 
				$title = "[Cài đặt Người dùng]Vai trò";
				$func_name .= "vai trò";
				break;
			case "_STATUS_STAFF" : 
				$title = "[Cài đặt Người dùng]Tình trạng nhân sự";
				$func_name .= "tình trạng nhân sự";
				break;
			case "_LEVEL_STAFF" : 
				$title = "[Cài đặt Người dùng]Cấp bậc nhân viên";
				$func_name .= "cấp bậc nhân viên";
				break;
			case "_LEVEL_STAFF_BO" : 
				$title = "[Cài đặt Người dùng]Cấp bậc nhân viên[BO]";
				$func_name .= "cấp bậc nhân viên[BO]";
				break;
			case "_GENDER" : 
				$title = "[Cài đặt Người dùng]Giới tính";
				$func_name .= "giới tính";
				break;
			case "_GROUP_ULTILITIES" : 
				$title = "[Cài đặt Người dùng]Nhóm tiện ích hệ thống";
				$func_name .= "nhóm tiện ích hệ thống";
				break;
			case "_ULTILITIES" : 
				$title = "[Cài đặt Người dùng]Tiện ích hệ thống";
				$func_name .= "tiện ích hệ thống";
				break;
			case "_ISSUE_TYPE" : 
				$title = "[Cài đặt Công việc]Loại công việc";
				$func_name .= "loại công việc";
				break;
			case "_ISSUE_TYPE_DEV" : 
				$title = "[Cài đặt Công việc]Loại công việc DEV";
				$func_name .= "loại công việc DEV";
				break;
			case "_ISSUE_PRIORITY" : 
				$title = "[Cài đặt Công việc]Loại ưu tiên";
				$func_name .= "loại ưu tiên";
				break;
			case "_ISSUE_STATUS" : 
				$title = "[Cài đặt Công việc]Tình trạng công việc";
				$func_name .= "tình trạng công việc";
				break;
			case "CUSTOMER_STATUS" : 
				$title = "[Cài đặt CRM]Tình trạng khách hàng";
				$func_name .= "tình trạng khách hàng";
				break;
			case "_CUSTOMER_RESOURCES" : 
				$title = "[Cài đặt CRM]Phân loại khách hàng";
				$func_name .= "phân loại khách hàng";
				break;
			case "CUSTOMER_TYPE" : 
				$title = "[Cài đặt CRM]Phân loại khách hàng";
				$func_name .= "phân loại khách hàng";
				break;
			case "FOLLOWUP_TYPE" : 
				$title = "[Cài đặt CRM]Loại Follow-Ups";
				$func_name .= "loại Follow-Ups";
				break;
			case "FOLLOWUP_STATUS" : 
				$title = "[Cài đặt CRM]Trạng thái Follow-Ups";
				$func_name .= "trạng thái Follow-Ups";
				break;
			case "FOLLOWUP_ISSUE" : 
				$title = "[Cài đặt CRM]Loại công việc Follow-Ups";
				$func_name .= "loại công việc Follow-Ups";
				break;
			case "PURPOSE" : 
				$title = "[Cài đặt CRM]Mục đích";
				$func_name .= "mục đích";
				break;
			case "NEED" : 
				$title = "[Cài đặt CRM]Nhu cầu";
				$func_name .= "nhu cầu";
				break;
			case "_STATUS_CONTRACT" : 
				$title = "[Cài đặt Giao dịch]Tình trạng hợp đồng";
				$func_name .= "tình trạng hợp đồng";
				break;
			case "_BANK_GUARANTEE" : 
				$title = "[Cài đặt Giao dịch]Bảo lãnh ngân hàng";
				$func_name .= "bảo lãnh ngân hàng";
				break;
			case "_BILLING_METHOD" : 
				$title = "[Cài đặt Giao dịch]Phương thức thanh toán";
				$func_name .= "phương thức thanh toán";
				break;
			case "_BILLING_TYPE" : 
				$title = "[Cài đặt Giao dịch]Loại hình giao dịch";
				$func_name .= "loại hình giao dịch";
				break;
			case "_TRANSACTION_TYPE" : 
				$title = "[Cài đặt Giao dịch]Loại xác nhận giao dịch";
				$func_name .= "loại xác nhận giao dịch";
				break;
			case "_TRANSACTION_STATUS" : 
				$title = "[Cài đặt Giao dịch]Tình trạng giao dịch";
				$func_name .= "tình trạng giao dịch";
				break;
			case "_TRANSACTION_PROJECT" : 
				$title = "[Cài đặt Giao dịch]Dự án xác nhận giao dịch";
				$func_name .= "dự án xác nhận giao dịch";
				break;
			case "BILLING_SOURCE" : 
				$title = "[Cài đặt Giao dịch]Nguồn quỹ";
				$func_name .= "nguồn quỹ";
				break;
			case "_FEATURE_MOC" : 
				$title = "[Cài đặt MOC]Danh sách tính năng";
				$func_name .= "danh sách tính năng";
				break;
			case "_PRICE_RANGE_SOP" : 
				$title = "[Cài đặt MOC]Khoảng giá chuyển nhượng";
				$func_name .= "khoảng giá chuyển nhượng";
				break;
			case "_PRICE_RANGE_LEASING" : 
				$title = "[Cài đặt MOC]Khoảng giá cho thuê";
				$func_name .= "khoảng giá cho thuê";
				break;
			case "_AREA_RANGE" : 
				$title = "[Cài đặt MOC]Khoảng diện tích";
				$func_name .= "khoảng diện tích";
				break;
			case "_INTERIOR_TYPE" : 
				$title = "[Cài đặt MOC]Nội thất";
				$func_name .= "nội thất";
				break;
			case "_FEE_TYPE" : 
				$title = "[Cài đặt MOC]Phí chuyển nhượng";
				$func_name .= "phí chuyển nhượng";
				break;
			case "_FEE_SERVICES_TYPE" : 
				$title = "[Cài đặt MOC]Phí dịch vụ";
				$func_name .= "phí dịch vụ";
				break;
			case "_STATUS_LEASING" : 
				$title = "[Cài đặt MOC]Tình trạng";
				$func_name .= "tình trạng";
				break;
			case "_BASE_UTENSILS_LEASING" : 
				$title = "[Cài đặt MOC]Đồ cơ bản";
				$func_name .= "đồ cơ bản";
				break;
			case "_PAYMENT_LEASING" : 
				$title = "[Cài đặt MOC]Thanh toán";
				$func_name .= "thanh toán";
				break;
			case "_PAYMENT_ELECTRIC_LEASING" : 
				$title = "[Cài đặt MOC]Thanh toán điện";
				$func_name .= "thanh toán điện";
				break;
			case "_PAYMENT_WATER_LEASING" : 
				$title = "[Cài đặt MOC]Thanh toán nước";
				$func_name .= "thanh toán nước";
				break;
			case "_RENTAL_TERM_LEASING" : 
				$title = "[Cài đặt MOC]Thời hạn thuê";
				$func_name .= "thời hạn thuê";
				break;
			case "_NEED_TYPE" : 
				$title = "[Cài đặt MOC]Nhu cầu phân loại";
				$func_name .= "nhu cầu phân loại";
				break;
			case "_INTERIOR" : 
				$title = "[Cài đặt MOC]Danh mục nội thất";
				$func_name .= "danh mục nội thất";
				break;
			case "_DEVICE" : 
				$title = "[Cài đặt MOC]Danh mục thiết bị";
				$func_name .= "danh mục thiết bị";
				break;
			case "_JURIDICAL" : 
				$title = "[Cài đặt MOC]Danh mục pháp lý";
				$func_name .= "danh mục pháp lý";
				break;
			case "_UTILITIES" : 
				$title = "[Cài đặt MOC]Danh mục tiện ích";
				$func_name .= "danh mục tiện ích";
				break;
			case "_HIDECODE" : 
				$title = "[Cài đặt MOC]Tùy chọn che mã";
				$func_name .= "tùy chọn che mã";
				break;
			case "_HIDECODELOWFLOOR" : 
				$title = "[Cài đặt MOC]Tùy chọn che mã thấp tầng";
				$func_name .= "tùy chọn che mã thấp tầng";
				break;
			case "_CATEGORYFAQS" : 
				$title = "[Cài đặt MOC]Danh mục hỏi đáp";
				$func_name .= "danh mục hỏi đáp";
				break;
			case "_CATEGORYSERVICES" : 
				$title = "[Cài đặt MOC]Danh mục dịch vụ";
				$func_name .= "danh mục dịch vụ";
				break;
			case "_FURNITUREUNIT" : 
				$title = "[Cài đặt MOC]Đơn vị đo nội thất";
				$func_name .= "đơn vị đo nội thất";
				break;
			case "_CATEGORYSFURNITURE" : 
				$title = "[Cài đặt MOC]Danh mục nội thất";
				$func_name .= "danh mục nội thất";
				break;
			case "_CATEGORYSINTERIOR" : 
				$title = "[Cài đặt MOC]Danh mục thiết kế nội thất";
				$func_name .= "danh mục thiết kế nội thất";
				break;
			case "_STYLESINTERIOR" : 
				$title = "[Cài đặt MOC]Phong cách thiết kế nội thất";
				$func_name .= "phong cách thiết kế nội thất";
				break;
			case "_TYPECOMPANY" : 
				$title = "[Cài đặt MOC]Loại đối tác";
				$func_name .= "loại đối tác";
				break;
			case "_GROUPSIZE" : 
				$title = "[Cài đặt MOC]Nhóm thành viên";
				$func_name .= "nhóm thành viên";
				break;
			case "_SOPTYPE" : 
				$title = "[Cài đặt MOC]Loại hình căn hộ";
				$func_name .= "loại hình căn hộ";
				break;
			case "_ADVANTAGE_SOP" : 
				$title = "[Cài đặt MOC]Ưu điểm căn hộ chuyển nhượng";
				$func_name .= "ưu điểm căn hộ chuyển nhượng";
				break;
			case "_ADVANTAGE_LEASING" : 
				$title = "[Cài đặt MOC]Ưu điểm căn hộ cho thuê";
				$func_name .= "ưu điểm căn hộ cho thuê";
				break;
			case "_STATUS_OVERTIME" : 
				$title = "[Cài đặt chung]Tình trạng tăng ca";
				$func_name .= "tình trạng tăng ca";
				break;
			case "_FORM_SHARE" : 
				$title = "[Cài đặt chung]Loại biểu mẫu";
				$func_name .= "loại biểu mẫu";
				break;
			case "_DOCS_SHARE" : 
				$title = "[Cài đặt chung]Loại tài liệu";
				$func_name .= "loại tài liệu";
				break;
			case "_TIME_UNIT" : 
				$title = "[Cài đặt chung]Đơn vị thời gian";
				$func_name .= "đơn vị thời gian";
				break;
			case "_NEWS_CATEGORY" : 
				$title = "[Cài đặt chung]Danh mục bản tin";
				$func_name .= "danh mục bản tin";
				break;
			case "CAT_TAKELEAVE" : 
				$title = "[Cài đặt chung]Loại nghỉ phép";
				$func_name .= "loại nghỉ phép";
				break;
			case "_FAQs" : 
				$title = "[Cài đặt chung]Đào tạo/ Khóa Học";
				$func_name .= "Đào tạo/ Khóa Học";
				break;
			case "_REPORT_TEMPLATE" : 
				$title = "[Cài đặt chung]Mẫu báo cáo";
				$func_name .= "mẫu báo cáo";
				break;
			case "_LEARN_CAT" : 
				$title = "[Cài đặt chung]Kho tài liệu";
				$func_name .= "kho tài liệu";
				break;
			case "_SHOP" : 
				$title = "[Cài đặt chung]Danh mục tiện ích";
				$func_name .= "danh mục tiện ích";
				break;
			case "_CATEGORY_DOCS" : 
				$title = "[Cài đặt chung]Danh mục tài liệu";
				$func_name .= "danh mục tài liệu";
				break;
			case "_DOCUMENT" : 
				$title = "[Cài đặt chung]Danh mục văn bản hệ thống";
				$func_name .= "danh mục văn bản hệ thống";
				break;
			default :
				$title = "Thuộc tính";
				$func_name = "thuộc tính";
		}
		return array(
			"title"	=>	$title,
			"func_name"	=>	$func_name
		);
	}
	function genTitle($tbl,$data) {
		switch($tbl) {
			case "Share" : 
				$share_type = !empty($data['share_type']) ? $data['share_type'] : "share";
				if($share_type == "honor"){
					$title = "Vinh danh bán hàng";
				}else if($share_type == "secret") {
					$title = "Thông tin mật";
				}else if($share_type == "share") {
					$title = "Hoạt động tiếp khách";
				}
				break;
			case "News" : 
				$title = "Bản tin";
				break;
			case "Customer" : 
				$title = "Khách hàng";
				break;
			case "Campaign" : 
				$title = "Chiến dịch";
				break;
			case "FollowUp" : 
				$title = "Follow-ups";
				break;
			case "Stock" : 
				$title = "Bảng hàng";
				break;
			case "Billing" : 
				$title = "Giao dịch chốt";
				break;
			case "Transaction" : 
				$title = "Xác nhận hoa hồng";
				break;
			case "Commission" : 
				$title = "Hoa hồng";
				break;
			case "VAT" : 
				$title = "Tất toán";
				break;
			case "StockHug" : 
				$title = "Quỹ ôm";
				break;
			case "Fund" : 
				$title = "Thu chi nội bộ";
				break;
			case "BankTransfer" : 
				$title = "Giao dịch chuyển quỹ";
				break;
			case "Property" : 
				$arr = $this->getTitleProperty($data);
				$title = $arr['title'];			
				break;
			case "Course" : 
				$title = "Sự kiện & Đào tạo";
				break;
			case "Calendar" : 
				$title = "Lịch họp";
				break;
			case "Today" : 
				$title = "Căn hộ nổi bật";
				break;
			case "Slide" : 
				$title = "Tài liệu học tập đào tạo";
				break;
			case "Overtime" : 
				$title = "Đăng ký tăng ca";
				break;
			case "Issue" : 
				$title = "Quản lý công việc";
				break;
			case "IssueTask" : 
				$title = "Task công việc";
				break;
			case "IssueNote" : 
				$title = "Ghi chú công việc";
				break;
			case "Configuration" : 
				if(!empty($data["field"]) && $data["field"] == "sale_policy") {
					$title = "Mẫu mail xác nhận đặt cọc";
				}else{
					$title = "Config";
				}				
				break;
			case "Okrs" : 
				$title = "OKRS";
				break;
			case "RegisterMWF" : 
				$title = "Tham quan nhà mẫu MWF";
				break;
			case "RequestPTG" : 
				$title = "Phiếu tính giá";
				break;
			case "Project" : 
				$title = "Dự án";
				break;
			case "ProjectMeta" : 
				$title = "Tài liệu dự án";
				break;
			case "Policy" : 
				$title = "Chính sách bán hàng";
				break;
			case "Shop" : 
				$title = "Cửa hàng tiện ích";
				break;
			case "Profile" : 
				$title = "Nhân viên";
				break;
			case "Member" : 
				$title = "Thành viên MOC";
				break;
			case "Order" : 
				$title = "Thông tin thanh toán gói MOC";
				break;
			case "Training" : 
				$title = "Khóa học đào tạo";
				break;
			case "Service" : 
				$title = "Dịch vụ tiện ích";
				break;
			case "Furniture" : 
				$title = "Thiết bị nội thất";
				break;
			case "FurnitureOptions" : 
				$title = "Phương án nội thất";
				break;
			case "Company" : 
				$title = "Công ty nội thất";
				break;
			case "Sop" : 
				$title = "Quản lý chuyển nhượng";
				break;
			case "Leasing" : 
				$title = "Quản lý cho thuê";
				break;
			case "EmailTemplate" : 
				$title = "Config email thông báo";
				break;
			case "User" : 
				$title = "Tài khoản quản trị";
				break;
			case "Cache" : 
				if(!empty($data["field"]) && $data["field"] == "fpoint") {
					$title = "Cấu hình F-Point";
				}elseif(!empty($data["field"]) && $data["field"] == "account_nanonet") {
					$title = "API tài khoản nanonet";
				}else{
					$title = "Cache";	
				}				
				break;
			default:
				$title = "";
				break;
		}
		return $title;
	}
	function getContentLog($tbl,$data,$action){
		global $clsISO,$profile_id,$oneProfile,$core;
		$clsProfile = new Profile();
		$clsProperty = new Property();
		$clsProject = new Project();
		$action_logs = array();	
		$action_name = "thêm mới";
		if($action == 'update') $action_name = "cập nhật";
		if($action == 'delete') $action_name = "xoá";
		if($action == 'cancel') $action_name = "huỷ";
		if($action == 'trash') $action_name = "chuyển vào thùng rác";
		if($action == 'restore') $action_name = "xóa khỏi thùng rác";
		if(IS_ADMIN_PAGE == 0) {
			$f_name = $clsProfile->getFullName($profile_id, $oneProfile);
		}else{
			$f_name = $core->_USER['first_name'] ." ". $core->_USER['last_name'];
		}
		switch($tbl) {
			case "Share" : 
				$share_type = !empty($data['share_type']) ? $data['share_type'] : "share";
				if($share_type == 'honor') {
					$func_name = "vinh danh bán hàng";
				}else if($share_type == "secret") {	
					$func_name = "thông tin mật";
				}else{		
					$func_name = "hoạt động tiếp khách";
				}
				break;
			case "News" :	
				$func_name = "bản tin";
				break;
			case "Customer" :
				$func_name = "khách hàng";
				break;
			case "Campaign" :	
				$func_name = "chiến dịch";
				break;
			case "FollowUp" :
				$arr_followup_type = $clsProperty->getArraySearchByKey("FOLLOWUP_TYPE");
				$func_name = sprintf("lịch hẹn %s",$arr_followup_type[$data['type_id']]["title"]);
				break;
			case "Stock" :
				if(!empty($data['tp']) && $data['tp'] == "PTG") {
					$func_name = sprintf("phiếu tính giá căn <strong>%s</strong>",$data["ms_code"]);
				}else if(!empty($data['tp']) && $data['tp'] == "status"){
					$func_name = sprintf("tình trạng căn <strong>%s</strong>",$data["ms_code"]);
				}else if(!empty($data['tp']) && $data['tp'] == "info"){
					$func_name = sprintf("thông tin căn <strong>%s</strong>",$data["ms_code"]);
				}else{
					$func_name = "thông tin bảng hàng";
				}
				break;
			case "Billing" :
				$func_name = "giao dịch chốt";
				break;
			case "Transaction" :
				$func_name = "xác nhận hoa hồng";
				break;
			case "Commission" :
				$func_name = "hoa hồng";
				break;
			case "VAT" :
				$func_name = "tất toán";
				break;
			case "StockHug" :
				$func_name = "quỹ ôm";
				break;
			case "Fund" :
				if($action == "insert") {
					$func_name = ($data['gr'] == "THUCTHU") ? "phiếu thu" : "phiếu chi";
				}else{
					$func_name = "thu chi nội bộ";
				}				
				break;
			case "BankTransfer" :
				$func_name = "giao dịch chuyển quỹ";
				break;
			case "Property" :
				$arr = $this->getTitleProperty($data);
				$func_name = $arr['func_name'];	
				break;
			case "Course" :
				$func_name = "sự kiện & đào tạo";
				break;
			case "Calendar" :
				$func_name = "lịch họp";
				break;
			case "Today" :
				$func_name = "căn hộ nổi bật";
				break;
			case "Slide" :
				$func_name = "tài liệu học tập đào tạo";
				break;
			case "Overtime" :
				$func_name = "đăng ký tăng ca";
				break;
			case "Issue" :
				if(!empty($data['status'])){
					$func_name = "trạng thái công việc";
				}else{
					$func_name = "công việc";
				}
				break;
			case "IssueTask" :
				$func_name = "task công việc";
				break;
			case "IssueNote" :
				if(!empty($data['tp']) && $data['tp'] == "quote"){
					$func_name = "trả lời ghi chú công việc";
				}else{
					$func_name = "ghi chú công việc";
				}				
				break;
			case "Configuration" :
				if(!empty($data["field"]) && $data["field"] == "sale_policy") {
					$func_name = "mẫu mail xác nhận đặt cọc";
				}elseif(!empty($data["field"]) && $data["field"] == "general") {
					$func_name = "cài đặt chung";
				}elseif(!empty($data["field"]) && $data["field"] == "address") {
					$func_name = "địa chỉ";
				}elseif(!empty($data["field"]) && $data["field"] == "security") {
					$func_name = "bảo mật";
				}elseif(!empty($data["field"]) && $data["field"] == "socical") {
					$func_name = "liên kết mạng xã hội";
				}elseif(!empty($data["field"]) && $data["field"] == "mailconfig") {
					$func_name = "email sender";
				}elseif(!empty($data["field"]) && $data["field"] == "message") {
					$func_name = "message";
				}else{
					$func_name = "config";
				}					
				break;
			case "Okrs" :
				$func_name = "OKRS";
				break;
			case "RegisterMWF" :
				$func_name = "đăng ký tham quan nhà mẫu MWF";
				break;
			case "RequestPTG" :
				if(!empty($data['ms_code'])) {
					if(!empty($data['status']) && $data["status"] == "exist") {
						$func_name = "căn <strong>".$data['ms_code']."</strong> đã có PTG";
					}else{
						$func_name = "yêu cầu PTG căn <strong>".$data['ms_code']."</strong>";
					}
				}else{
					$func_name = "yêu cầu PTG";
				}				
				break;
			case "Project" :	
				$func_name = "";
				if(!empty($data["field"])) {
					if($data["field"] == "payment_calendar") {
						$func_name = "lịch thanh toán ";
					}else if($data["field"] == "form_share") {
						$func_name = "biểu mẫu ";
					}else if($data["field"] == "doc_share") {
						$func_name = "tài liệu ";
					}else if($data["field"] == "utilities") {
						$func_name = "tiện ích ";
					}					
				}
				if(!empty($data["title"]) && $data["title"] != ""){
					$func_name .= "dự án <strong>".$data["title"]."</strong>";
				}else{
					$func_name .= "dự án";
				}
				break;
			case "ProjectMeta" :	
				$func_name .= "tài liệu dự án";				
				break;
			case "Policy" : 
				$func_name = "chính sách bán hàng";
				break;
			case "Shop" : 
				$func_name = "cửa hàng tiện ích";
				break;
			case "Profile" : 
				$func_name = "nhân viên";
				break;
			case "Member" : 
				$func_name = "thành viên MOC";
				break;
			case "Order" : 
				$func_name = "thông tin thanh toán gói MOC";
				break;
			case "Training" : 
				$func_name = "khóa học đào tạo";
				break;
			case "Service" : 
				$func_name = "dịch vụ tiện ích";
				break;
			case "Furniture" : 
				$func_name = "thiết bị nội thất";
				break;
			case "FurnitureOptions" : 
				$func_name = "phương án nội thất";
				break;
			case "Company" : 
				$func_name = "công ty nội thất";
				break;
			case "Sop" :
				$func_name = "chuyển nhượng";				
				break;
			case "Leasing" :
				$func_name = "cho thuê";				
				break;
			case "EmailTemplate" :
				$func_name = "email thông báo";				
				break;
			case "User" :
				$func_name = "tài khoản quản trị";				
				break;
			case "Cache" :
				if(!empty($data["field"]) && $data["field"] == "fpoint") {
					$func_name = "Cấu hình F-Point";
				}elseif(!empty($data["field"]) && $data["field"] == "account_nanonet") {
					$func_name = "api tài khoản nanonet";
				}else{
					$func_name = "cache";	
				}							
				break;
			default:
				return "";
				break;
		}
		$partem = '<strong>%s</strong> đã %s %s vào lúc <strong>%s</strong>';
		$content_log = sprintf($partem, $f_name, $action_name, $func_name, date("d/m/Y H:i"));
		return $content_log;
	}
}