<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the ISOCMS                         # ||
|| # ISOCMS 6.0.0 VietISO Techical Team (vanthiembui.it@gmail.com)    # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 VietISO JSC.             # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||
|| #################################################################### ||
\*======================================================================*/
require_once(dirname(__FILE__).DS.'mod.default.php');
function default_default(){
	global $assign_list,$core,$clsConfiguration,$dbconn,$clsISO;
	$clsAdminButton = new AdminButton();
	$assign_list["clsAdminButton"] = $clsAdminButton;  
	$list_settings = $clsAdminButton->getAll("is_active='1' and _type='_SETTING' order by order_no ASC");
	$assign_list["list_settings"] = $list_settings;  
}
function default_media(){
	global $assign_list,$core,$clsConfiguration,$dbconn;
	$clsAdminButton = new AdminButton();
	$assign_list["clsAdminButton"] = $clsAdminButton;  
}
function default_property(){
	global $assign_list,$core,$clsConfiguration,$dbconn;
	$group = Input::get('group','general');
	$agency_id = (int)Input::get('agency',0);
	$property_type = Input::get('property_type',"");
	$assign_list["agency_id"] = $agency_id;  
	$assign_list["group"] = $group;  
	$assign_list["_type"] = $property_type;  
	if($group=='stock'){
		$lstProperty_Type['_INVESTOR'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Chủ đầu tư',
			'description'	=> 'Danh sách CĐT'
		);
		$lstProperty_Type['_DIRECTION'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Hướng nhà',
			'description'	=> 'Danh sách hướng nhà/căn hộ'
		);
		$lstProperty_Type['_VIEW'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Hướng Nhìn',
			'description'	=> 'Danh sách hướng nhìn nhà/căn hộ'
		);
		$lstProperty_Type['_TYPE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Loại hình',
			'description'	=> 'Loại hình nhà/Căn hộ'
		);
		$lstProperty_Type['_TYPE_VILLA'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Loại hình Thấp tầng',
			'description'	=> 'Loại hình nhà/Căn hộ'
		);
		$lstProperty_Type['_BLOCK_TYPE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Loại hình phân khu',
			'description'	=> 'Loại hình phân khu'
		);
		$lstProperty_Type['_BEDROOM'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Số phòng ngủ',
			'description'	=> 'Loại hình nhà/Căn hộ'
		);
		$lstProperty_Type['_AGENCY'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Đại lý',
			'description'	=> 'Đại lý phân phối, liên kết, hợp tác'
		);
		/*$lstProperty_Type['_PARTNER'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Đối tác',
			'description'	=> 'Danh sách đối tác'
		);*/
		$lstProperty_Type['_STATUS'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Tình trạng',
			'description'	=> 'Tình trạng nhà/Căn hộ'
		);
		$lstProperty_Type['_FLOOR_TYPE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Loại tầng',
			'description'	=> 'Loại tầng/căn hộ'
		);
		$lstProperty_Type['_STOCK_HOLD'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Loại quỹ thấp tầng',
			'description'	=> 'Loại quỹ thấp tầng'
		);
		/*$lstProperty_Type['_REGISTER_TYPE_MWF'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Loại hình đăng ký MWF',
			'description'	=> 'Loại hình đăng ký MWF'
		);*/
		$lstProperty_Type['_STATUS_STOCK_HUG'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Tình trạng quỹ ôm',
			'description'	=> 'Tình trạng quỹ ôm'
		);
		$lstProperty_Type['_STATUS_PAYMENT_PROGRESS'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Tiến độ thanh toán',
			'description'	=> 'Tiến độ thanh toán'
		);
		$lstProperty_Type['_COMPLETED_FLOOR'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Hoàn thiện tầng',
			'description'	=> 'Hoàn thiện tầng'
		);
		$lstProperty_Type['_MANAGEMENT_FEE_INCLUDED'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Phí quản lý',
			'description'	=> 'Phí quản lý'
		);
		$lstProperty_Type['_UTILITIES_PROJECT'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Tiện ích dự án',
			'description'	=> 'Tiện ích dự án'
		);
		/*$lstProperty_Type['_GROUP_RANGE_VHGG'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Nhóm Phân Khu VHGG',
			'description'	=> 'Nhóm Phân Khu VHGG'
		);*/
	} else if($group=='fund'){
		$lstProperty_Type['THUCTHU'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Loại khoản thu',
			'description'	=> 'Loại khoản thu'
		);
		$lstProperty_Type['THUCCHI'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Loại khoản chi',
			'description'	=> 'Loại khoản chi'
		);
		$lstProperty_Type['VAT_TYPE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Loại VAT',
			'description'	=> 'Loại VAT'
		);
		$lstProperty_Type['VAT_STATUS'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Tình trạng VAT',
			'description'	=> 'Tình trạng VAT'
		);
		$lstProperty_Type['COMMISSION_STATUS'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Tình trạng Hoa Hồng',
			'description'	=> 'Tình trạng Hoa Hồng'
		);
		$lstProperty_Type['COMMISSION_PAYMENT_STATUS'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Tình trạng thanh toán Hoa Hồng',
			'description'	=> 'Tình trạng thanh toán Hoa Hồng'
		);
	} else if($group=='okrs'){
		$lstProperty_Type['_OKRS_GROUP'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Nhóm OKRs',
			'description'	=> 'Nhóm OKRs'
		);
		$lstProperty_Type['_PERIOD'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Chu kỳ OKRS',
			'description'	=> 'Chu kỳ OKRS'
		);
		$lstProperty_Type['FINANCE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Tài chính',
			'description'	=> 'Tài chính'
		);
		$lstProperty_Type['_UNIT'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Đơn vị tính',
			'description'	=> 'Đơn vị tính OKRs'
		);
	} else if($group=='profile'){
		/*$lstProperty_Type['_PACKAGE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Gói tài khoản',
			'description'	=> 'Gói tài khoản MyOceanCity.vn'
		);*/
		$lstProperty_Type['_DEPARTMENT'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Phòng ban',
			'description'	=> 'Phòng ban nhân viên'
		);
		$lstProperty_Type['_ROLE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Vai trò',
			'description'	=> 'Vai trò trong phòng ban'
		);
		/*$lstProperty_Type['_GROUP_ULTILITIES'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Nhóm tiện ích hệ thống',
			'description'	=> 'Nhóm tiện ích hệ thống'
		);*/
		$lstProperty_Type['_ULTILITIES'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Tiện ích hệ thống',
			'description'	=> 'Tiện ích hệ thống'
		);
		$lstProperty_Type['_STATUS_STAFF'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Tình trạng nhân sự',
			'description'	=> 'Tình trạng nhân sự'
		);
		$lstProperty_Type['_LEVEL_STAFF'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Cấp bậc nhân viên',
			'description'	=> 'Cấp bậc nhân viên'
		);
		$lstProperty_Type['_LEVEL_STAFF_BO'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Cấp bậc nhân viên[BO]',
			'description'	=> 'Cấp bậc nhân viên[BO]'
		);
		$lstProperty_Type['_GENDER'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Giới tính',
			'description'	=> 'Giới tính'
		);
		$lstProperty_Type['_TRAINING_CAT'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Danh mục đào tạo',
			'description'	=> 'Danh mục đào tạo'
		);
	} else if($group=='issue') {
		$lstProperty_Type['_ISSUE_TYPE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Loại công việc',
			'description'	=> '[ISSUE] Loại công việc'
		);
		$lstProperty_Type['_ISSUE_TYPE_DEV'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Loại công việc',
			'description'	=> '[ISSUE] Loại công việc (DEV)'
		);
		$lstProperty_Type['_ISSUE_PRIORITY'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Loại ưu tiên',
			'description'	=> '[ISSUE] Loại ưu tiên'
		);
		$lstProperty_Type['_ISSUE_STATUS'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Tình trạng công việc',
			'description'	=> '[ISSUE] Tình trạng công việc'
		);
	} else if($group=='crm'){
		$lstProperty_Type['CUSTOMER_STATUS'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Tình trạng khách hàng',
			'description'	=> 'Tình trạng khách hàng'
		);
		$lstProperty_Type['DATA_CENTRAL_STATUS'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Tình trạng data cư dân',
			'description'	=> 'Tình trạng data cư dân'
		);
		$lstProperty_Type['_CUSTOMER_RESOURCES'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Phân loại khách hàng',
			'description'	=> 'Phân loại khách hàng'
		);
		$lstProperty_Type['CUSTOMER_TYPE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Phân loại khách hàng',
			'description'	=> 'Phân loại khách hàng'
		);
		$lstProperty_Type['FOLLOWUP_TYPE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Loại Follow-Ups',
			'description'	=> 'Loại Follow-Ups'
		);
		$lstProperty_Type['FOLLOWUP_STATUS'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Trạng thái Follow-Ups',
			'description'	=> 'Trạng thái Follow-Ups'
		);
		$lstProperty_Type['FOLLOWUP_ISSUE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Loại công việc Follow-Ups',
			'description'	=> 'Loại công việc Follow-Ups'
		);
		$lstProperty_Type['FOLLOWUP_ISSUE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Loại công việc Follow-Ups',
			'description'	=> 'Loại công việc Follow-Ups'
		);
		$lstProperty_Type['PURPOSE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Mục đích',
			'description'	=> 'Mục đích'
		);
		$lstProperty_Type['NEED'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Nhu cầu',
			'description'	=> 'Nhu cầu'
		);
		$lstProperty_Type['FIELD_DATA'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Trường dữ liệu khách hàng',
			'description'	=> 'Trường dữ liệu khách hàng'
		);
	} else if($group == 'booking'){
		$lstProperty_Type['_BOOKING_STATE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Trạng thái booking',
			'description'	=> 'Trạng thái booking'
		);
		$lstProperty_Type['_BOOKING_STATUS'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Tình trạng booking',
			'description'	=> 'Tình trạng booking'
		);
	} else if($group=='billing'){
		$lstProperty_Type['_STATUS_CONTRACT'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Tình trạng hợp đồng',
			'description'	=> 'Tình trạng hợp đồng'
		);
		/*$lstProperty_Type['_OPS_COST_CAT'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Danh mục chi phí vận hành',
			'description'	=> 'Danh mục chi phí vận hành'
		);
		$lstProperty_Type['_BANK_GUARANTEE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Bảo lãnh ngân hàng',
			'description'	=> 'Bảo lãnh ngân hàng'
		);*/
		$lstProperty_Type['_BILLING_METHOD'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Phương thức thanh toán',
			'description'	=> 'Phương thức thanh toán'
		);
		$lstProperty_Type['_BILLING_TYPE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Loại hình giao dịch',
			'description'	=> 'Loại hình giao dịch'
		);
		$lstProperty_Type['_TRANSACTION_TYPE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Loại xác nhận giao dịch',
			'description'	=> '[TRANSACTION] Loại xác nhận giao dịch'
		);
		$lstProperty_Type['_TRANSACTION_STATUS'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Tình trạng giao dịch',
			'description'	=> '[TRANSACTION] Tình trạng giao dịch'
		);
		$lstProperty_Type['_TRANSACTION_PROJECT'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Dự án xác nhận giao dịch',
			'description'	=> '[TRANSACTION] Dự án xác nhận giao dịch'
		);
		$lstProperty_Type['BILLING_SOURCE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Nguồn quỹ',
			'description'	=> '[BILING] Tình trạng giao dịch'
		);
		$lstProperty_Type['BILLING_SOURCE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Nguồn quỹ',
			'description'	=> '[BILING] Tình trạng giao dịch'
		);
		$lstProperty_Type['SALE_TYPE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Bán cho',
			'description'	=> '[BILING] Bán cho đại lý'
		);
		$lstProperty_Type['BILLING_STATE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Trạng thái',
			'description'	=> '[BILING] Trạng thái giao dịch'
		);
	} else if($group=='myoceancity.vn'){	
		$lstProperty_Type['_SOURCE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Nguồn/Đại lý',
			'description'	=> 'Nguồn/Đại lý'
		);
		$lstProperty_Type['_PRICE_RANGE_SOP'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Khoảng giá chuyển nhượng',
			'description'	=> 'Khoảng giá chuyển nhượng'
		);
		$lstProperty_Type['_PRICE_RANGE_LEASING'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Khoảng giá cho thuê',
			'description'	=> 'Khoảng giá cho thuê'
		);
		$lstProperty_Type['_AREA_RANGE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Khoảng diện tích',
			'description'	=> 'Khoảng diện tích'
		);
		$lstProperty_Type['_INTERIOR_TYPE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Nội thất',
			'description'	=> 'Danh mục nội thất'
		);
		$lstProperty_Type['_FEE_TYPE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Phí chuyển nhượng',
			'description'	=> 'Phí chuyển nhượng'
		);
		$lstProperty_Type['_FEE_SERVICES_TYPE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Phí dịch vụ',
			'description'	=> 'Phí dịch vụ'
		);
		$lstProperty_Type['_STATUS_LEASING'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Tình trạng thuê',
			'description'	=> 'Tình trạng thuê'
		);
		$lstProperty_Type['_STATUS_TRANSFER'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Tình trạng chuyển nhượng',
			'description'	=> 'Tình trạng chuyển nhượng'
		);
		$lstProperty_Type['_STATUS_VIEWING'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Tình trạng xem nhà',
			'description'	=> 'Tình trạng xem nhà'
		);
		$lstProperty_Type['_BASE_UTENSILS_LEASING'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Đồ cơ bản',
			'description'	=> 'Đồ cơ bản'
		);
		$lstProperty_Type['_PAYMENT_LEASING'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Thanh toán',
			'description'	=> 'Thanh toán'
		);
		$lstProperty_Type['_PAYMENT_ELECTRIC_LEASING'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Thanh toán điện',
			'description'	=> 'Thanh toán điện'
		);
		$lstProperty_Type['_PAYMENT_WATER_LEASING'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Thanh toán nước',
			'description'	=> 'Thanh toán nước'
		);
		$lstProperty_Type['_RENTAL_TERM_LEASING'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Thời hạn thuê',
			'description'	=> 'Thời hạn thuê'
		);
		$lstProperty_Type['_NEED_TYPE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Nhu cầu phân loại',
			'description'	=> 'Nhu cầu phân loại'
		);
		$lstProperty_Type['_INTERIOR'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Danh mục nội thất',
			'description'	=> 'Danh mục nội thất'
		);	
		$lstProperty_Type['_DEVICE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Danh mục thiết bị',
			'description'	=> 'Danh mục thiết bị'
		);
		$lstProperty_Type['_JURIDICAL'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Danh mục pháp lý',
			'description'	=> 'Danh mục pháp lý'
		);
		$lstProperty_Type['_UTILITIES'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Danh mục tiện ích',
			'description'	=> 'Danh mục tiện ích'
		);
		$lstProperty_Type['_HIDECODE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Tùy chọn che mã',
			'description'	=> 'Tùy chọn che mã'
		);
		$lstProperty_Type['_HIDECODELOWFLOOR'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Tùy chọn che mã thấp tầng',
			'description'	=> 'Tùy chọn che mã thấp tầng'
		);
		$lstProperty_Type['_CATEGORYSERVICES'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Danh mục dịch vụ',
			'description'	=> 'Danh mục dịch vụ'
		);
		$lstProperty_Type['_FURNITUREUNIT'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Đơn vị đo nội thất',
			'description'	=> 'Đơn vị đo nội thất'
		);
		$lstProperty_Type['_CATEGORYSFURNITURE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Danh mục nội thất',
			'description'	=> 'Danh mục nội thất'
		);
		$lstProperty_Type['_CATEGORYSINTERIOR'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Danh mục thiết kế nội thất',
			'description'	=> 'Danh mục thiết kế nội thất'
		);
		$lstProperty_Type['_STYLESINTERIOR'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Phong cách thiết kế nội thất',
			'description'	=> 'Phong cách thiết kế nội thất'
		);
		$lstProperty_Type['_TYPECOMPANY'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Loại đối tác',
			'description'	=> 'Loại đối tác'
		);
		$lstProperty_Type['_GROUPSIZE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Nhóm thành viên',
			'description'	=> 'Nhóm thành viên'
		);
		$lstProperty_Type['_SOPTYPE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Loại hình căn hộ',
			'description'	=> 'Loại hình căn hộ'
		);
		$lstProperty_Type['_ADVANTAGE_SOP'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Ưu điểm căn hộ chuyển nhượng',
			'description'	=> 'Ưu điểm căn hộ chuyển nhượng'
		);
		$lstProperty_Type['_ADVANTAGE_LEASING'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Ưu điểm căn hộ cho thuê',
			'description'	=> 'Ưu điểm căn hộ cho thuê'
		);
	} else if($group=='website'){			
		$lstProperty_Type['_CATEGORYFAQS'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Danh mục hỏi đáp',
			'description'	=> 'Danh mục hỏi đáp'
		);
	} else {
		$lstProperty_Type['_Sop'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Tổng quan dự án',
			'description'	=> 'Tổng quan dự án'
		);
		/*$lstProperty_Type['_STATUS_OVERTIME'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Tình trạng tăng ca',
			'description'	=> 'Tình trạng tăng ca'
		);
		$lstProperty_Type['_STATUS_SHARE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Tình trạng Tiếp khách',
			'description'	=> 'Tình trạng Tiếp khách'
		);*/
		$lstProperty_Type['_FORM_SHARE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Loại biểu mẫu',
			'description'	=> 'Loại biểu mẫu dự án'
		);
		$lstProperty_Type['_DOCS_SHARE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Loại tài liệu',
			'description'	=> 'Loại tài liệu dự án'
		);
		$lstProperty_Type['_TIME_UNIT'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Đơn vị thời gian',
			'description'	=> 'Đơn vị thời gian'
		);
		$lstProperty_Type['_NEWS_CATEGORY'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Danh mục bản tin',
			'description'	=> 'Danh mục bản tin'
		);
		$lstProperty_Type['CAT_TAKELEAVE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Loại nghỉ phép',
			'description'	=> 'Loại nghỉ phép'
		);
		$lstProperty_Type['_FAQs'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Đào tạo/ Khóa Học',
			'description'	=> '[COURSE] Đào tạo/ Khóa Học'
		);
		/*$lstProperty_Type['_REPORT_TEMPLATE'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Mẫu báo cáo',
			'description'	=> '[REPORT] Trường dữ liệu trong File Báo cáo'
		);*/
		$lstProperty_Type['_LEARN_CAT'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Kho tài liệu',
			'description'	=> '[REPORT] Danh mục kho tài liệu'
		);
		/*$lstProperty_Type['_SHOP'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Danh mục tiện ích',
			'description'	=> 'Danh mục tiện ích'
		);*/
		$lstProperty_Type['_CATEGORY_DOCS'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Danh mục tài liệu',
			'description'	=> 'Danh mục tài liệu'
		);
		/*$lstProperty_Type['_DOCUMENT'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Danh mục văn bản hệ thông',
			'description'	=> 'Danh mục văn bản hệ thông'
		);
		$lstProperty_Type['BANK_ACCOUNT'] = array(
			'icon'			=> 1,
			'image'			=> 0,
			'color'			=> 0,
			'name'			=> 'Tài khoản ngân hàng',
			'description'	=> 'Tài khoản ngân hàng thu/chi nội bộ'
		);*/
	} 
	$assign_list["lstProperty_Type"] = $lstProperty_Type;  
}
/**
 * Cấu hình hệ thống — màn hình schema-driven.
 * Field/nhóm/tab khai báo trong models/ConfigDeclaration.php: thêm cấu hình mới
 * chỉ sửa file đó, KHÔNG đụng hàm này và KHÔNG đụng general.tpl.
 */
function default_general(){
	global $assign_list,$core,$clsConfiguration,$clsISO,$act;
	$clsMember = new Member();
	$assign_list["clsMember"] = $clsMember;
	$clsDeclaration = new ConfigDeclaration();
	/** Lọc quyền/hidden, sắp theo order, tính sẵn slug + tab active ngay tại đây */
	$configGroups = $clsDeclaration->normalize(config_general_sources());
	/** Lưu theo PRG: gom đúng key có trong schema → upsert → xoá cache → redirect */
	if(Input::method() === 'POST' && Input::post('submit', '') === 'Update'){
		$posted = isset($_POST['config']) && is_array($_POST['config']) ? $_POST['config'] : array();
		$data = $clsDeclaration->collect($configGroups, $posted);
		$isSaved = $clsConfiguration->saveBatch($data, (int) $core->_USER['user_id']);
		$clsActivityLog = new ActivityLog();
		$clsActivityLog->addActivityLog("Configuration","update",['field' =>"general"]);
		$url = PCMS_URL.'?mod=setting&act='.$act.'&message='.($isSaved ? 'updateSuccess' : 'updateFail');
		header('Location:'.$url);
		exit();
	}
	$assign_list["configGroups"] = config_general_bind($configGroups, $clsConfiguration, $clsMember);
}
/**
 * Nguồn option động cho các field khai báo 'source'.
 * Nạp ở controller để .tpl không phải truy vấn và không phải biết model nào.
 */
function config_general_sources(){
	$sources = array(
		'profile'       => array(),
		'profile_admin' => array(),
		'block_type'    => array(),
		'app_template'  => array(),
		'role'          => array(),
		'department'    => array(),
		'office'        => array(),
		'agency'        => array()
	);
	$clsProfile = new Profile();
	$field = "{$clsProfile->pkey},full_name,role_id,status_id";
	$list_profile = $clsProfile->getAll("is_trash=0 and status_id <> '"._STATUS_STAFF_OFF_ID."'", $field);
	$admin_roles = config_general_admin_roles();
	if(!empty($list_profile)){
		foreach($list_profile as $_oProfile){
			$sources['profile'][$_oProfile['profile_id']] = $_oProfile['full_name'];
			if(!in_array((int) $_oProfile['role_id'], $admin_roles, true)){
				continue;
			}
			if((int) $_oProfile['status_id'] !== (int) _STATUS_STAFF_ON_ID){
				continue;
			}
			$sources['profile_admin'][$_oProfile['profile_id']] = $_oProfile['full_name'];
		}
	}
	/** Văn phòng nằm ở bảng setting (_type='_OFFICE'), không phải danh mục phòng ban. */
	$clsSetting = new Setting();
	$list_offices = $clsSetting->getCacheItems('_OFFICE');
	if(!empty($list_offices)){
		foreach($list_offices as $_oOffice){
			$sources['office'][$_oOffice['setting_id']] = $_oOffice['title'];
		}
	}
	$clsProperty = new Property();
	$list_block_types = $clsProperty->getCacheItems('_BLOCK_TYPE');
	if(!empty($list_block_types)){
		foreach($list_block_types as $_oBlock){
			$sources['block_type'][$_oBlock['property_id']] = $_oBlock['title'];
		}
	}
	/** Vai trò và phòng ban dùng cho nhóm "Màn hình trang chủ"; danh sách phẳng, đã theo order_no. */
	$list_roles = $clsProperty->getCacheItems('_ROLE');
	if(!empty($list_roles)){
		foreach($list_roles as $_oRole){
			$sources['role'][$_oRole['property_id']] = $_oRole['title'];
		}
	}
	$list_departments = $clsProperty->getCacheItems('_DEPARTMENT');
	if(!empty($list_departments)){
		foreach($list_departments as $_oDepartment){
			$sources['department'][$_oDepartment['property_id']] = $_oDepartment['title'];
		}
	}
	$list_agencies = $clsProperty->getCacheItems('_AGENCY');
	if(!empty($list_agencies)){
		foreach($list_agencies as $_oAgency){
			$sources['agency'][$_oAgency['property_id']] = $_oAgency['title'];
		}
	}
	/** Danh sách giao diện do tầng khác nạp; không có thì nhóm Giao diện tự ẩn */
	if(!empty($GLOBALS['listAppTemplate'])){
		foreach($GLOBALS['listAppTemplate'] as $_template){
			$sources['app_template'][$_template] = $_template;
		}
	}
	return $sources;
}
function config_general_admin_roles(){
	$roles = array((int) 12370);
	if(defined('_ROLE_STAFF_LEADER_ADMIN')){
		$roles[] = (int) _ROLE_STAFF_LEADER_ADMIN;
	}
	return $roles;
}
/**
 * Gắn giá trị hiện tại vào từng field đã chuẩn hóa.
 * Mọi thứ .tpl cần đều tính sẵn ở đây: current (chuỗi), current_list (mảng đã
 * json_decode), support_rows (bảng tài khoản hỗ trợ kèm tên thành viên).
 */
function config_general_bind($configGroups, $clsConfiguration, $clsMember){
	foreach($configGroups as $_indexGroup => $_group){
		foreach($_group['fields'] as $_indexField => $_field){
			$keyword = $_field['keyword'];
			$default = isset($_field['default']) ? $_field['default'] : '';
			$_field['current'] = $clsConfiguration->getValue($keyword, $default);
			$_field['current_list'] = !empty($_field['json']) ? $clsConfiguration->getArray($keyword) : array();
			/**
			 * Map để .tpl kiểm tra option đã chọn bằng isset — không so sánh trong
			 * template. PHP 7 coi '' == 0 là đúng nên so lỏng sẽ tự chọn nhầm
			 * option có key 0 khi chưa có giá trị nào.
			 */
			$current = is_scalar($_field['current']) ? (string) $_field['current'] : '';
			if(!empty($_field['multiple'])){
				$_field['current_map'] = array_flip(array_map('strval', array_filter($_field['current_list'], 'is_scalar')));
			} else {
				$_field['current_map'] = array($current => 1);
			}
			$_field['is_checked'] = ($current === '1');
			if($_field['type'] === 'images'){
				$_field['preview_src'] = config_general_image_preview($_field, $current);
				$_field['current_width'] = config_general_image_size($clsConfiguration, $_field['width_keyword'], $_field['default_width']);
				$_field['current_height'] = config_general_image_size($clsConfiguration, $_field['height_keyword'], $_field['default_height']);
			}
			if($_field['type'] === 'select_pair'){
				$_field = config_general_pair_value($_field, $clsMember);
			}
			if($_field['type'] === 'stock_support'){
				$_field['support_rows'] = config_general_support_rows($_field, $clsConfiguration, $clsMember);
			}
			$configGroups[$_indexGroup]['fields'][$_indexField] = $_field;
		}
	}
	return $configGroups;
}
/**
 * Giá trị 2 ô của field select_pair (vd: duyệt giao dịch = đối tượng + admin).
 * Cả 2 map dựng sẵn ở đây để .tpl chỉ isset(), không so sánh chuỗi trong template.
 */
function config_general_pair_value($field, $clsMember){
	$value = is_array($field['current_list']) ? $field['current_list'] : array();
	$keywords = ConfigDeclaration::pairKeywords($field);
	$main = isset($value[$keywords[0]]) && is_scalar($value[$keywords[0]]) ? (string) $value[$keywords[0]] : '';
	$extra = isset($value[$keywords[1]]) && is_scalar($value[$keywords[1]]) ? (string) $value[$keywords[1]] : '';
	$showWhen = isset($field['pair']['show_when']) ? (string) $field['pair']['show_when'] : '';
	$field['pair']['select'] = config_general_pair_options($field, $extra, $clsMember);
	$field['sub_keyword'] = $keywords[0];
	$field['pair']['keyword'] = $keywords[1];
	$field['pair']['show_when'] = $showWhen;
	$field['current_map'] = array($main => 1);
	$field['pair']['current_map'] = array($extra => 1);
	// Ô phụ chỉ có nghĩa với đúng 1 lựa chọn của ô chính; JS mở lại khi người dùng đổi.
	$field['pair']['is_open'] = ($main !== '' && $main === $showWhen);
	return $field;
}
/**
 * Option ô phụ, có thêm chính người đang được lưu nếu họ đã rơi khỏi nguồn
 * (đổi vai trò, nghỉ việc). Không thêm thì ô về rỗng và lần lưu kế tiếp xoá
 * mất cấu hình đang chạy mà người dùng không hề biết.
 */
function config_general_pair_options($field, $current, $clsMember){
	$options = !empty($field['pair']['select']) ? $field['pair']['select'] : array();
	if($current === '' || isset($options[$current])){
		return $options;
	}
	$name = is_object($clsMember) ? $clsMember->getFullName($current) : '';
	$options[$current] = ($name !== '' && $name !== null) ? $name : '#'.$current;
	return $options;
}
/**
 * Ảnh xem trước của field ảnh: giá trị đang lưu → ảnh demo khai báo → ảnh trống.
 * Luôn trả về đường dẫn khác rỗng, vì src="" khiến trình duyệt tải lại chính trang.
 */
function config_general_image_preview($field, $current){
	if($current !== ''){
		return $current;
	}
	if(!empty($field['demo'])){
		return $field['demo'];
	}
	return URL_IMAGES.'/none_image.png';
}
/**
 * Kích thước ảnh đang lưu; chưa từng lưu thì lấy mặc định khai báo trong schema
 * để ô nhập không bao giờ trống và ảnh xem trước luôn có kích thước.
 */
function config_general_image_size($clsConfiguration, $keyword, $default){
	$value = (int) $clsConfiguration->getValue($keyword, 0);
	return $value > 0 ? $value : (int) $default;
}
/** Mỗi loại bảng hàng một dòng: tài khoản hỗ trợ chính + phụ, kèm sẵn tên hiển thị. */
function config_general_support_rows($field, $clsConfiguration, $clsMember){
	$rows = array();
	$mainValues = $clsConfiguration->getArray($field['keyword']);
	$extraValues = !empty($field['pair_keyword']) ? $clsConfiguration->getArray($field['pair_keyword']) : array();
	foreach($field['select'] as $property_id => $title){
		$main_id = isset($mainValues[$property_id]) ? $mainValues[$property_id] : '';
		$extra_id = isset($extraValues[$property_id]) ? $extraValues[$property_id] : '';
		$rows[] = array(
			'property_id' => $property_id,
			'title'       => $title,
			'main_id'     => $main_id,
			'main_name'   => $main_id !== '' ? $clsMember->getFullName($main_id) : '',
			'extra_id'    => $extra_id,
			'extra_name'  => $extra_id !== '' ? $clsMember->getFullName($extra_id) : ''
		);
	}
	return $rows;
}
function default_address(){
	global $assign_list,$core,$mod, $act,$clsConfiguration,$dbconn,$clsISO;
	$clsCountry = new Country();
	$clsCity = new City();
	$assign_list["clsCountry"] = $clsCountry;  
	$assign_list["clsCity"] = $clsCity;  
	$map_la = $clsConfiguration->getValue('map_la');
	if(!$map_la) $map_la = '21.0277644';
	$map_lo = $clsConfiguration->getValue('map_lo');
	if(!$map_lo) $map_lo = '105.8341598';
	$assign_list["map_la"] = $map_la;  
	$assign_list["map_lo"] = $map_lo; 
	/** Updated */
	if(isset($_POST['submit']) && $_POST['submit']=='Update'){
		foreach($_POST as $key=>$val){
			$tmp = explode('-',$key);
			if($tmp[0]=='iso'){
				$clsConfiguration->updateValue($tmp[1],$val);
			}
		}
		#activity log		
		$clsActivityLog = new ActivityLog();
		$log = $clsActivityLog->addActivityLog("Configuration","update",['field' =>"address"]);
		header('Location:'.PCMS_URL.'?mod=setting&act='.$act.'&message=updateSuccess');
		exit();
	}
}
function default_social(){
	global $assign_list,$core,$clsConfiguration,$dbconn;
	$clsCity = new City();
	$clsCountry = new Country();
	$assign_list["clsCity"] = $clsCity;  
	$assign_list["clsCountry"] = $clsCountry;
	/** Updated */
	if(isset($_POST['submit']) && $_POST['submit']=='Update'){
		foreach($_POST as $key=>$val){
			$tmp = explode('-',$key);
			if($tmp[0]=='iso'){
				$clsConfiguration->updateValue($tmp[1],$val);
			}
		}
		#activity log		
		$clsActivityLog = new ActivityLog();
		$log = $clsActivityLog->addActivityLog("Configuration","update",['field' =>"social"]);
		header('Location:'.PCMS_URL.'?mod=setting&act=social&message=updateSuccess');
		exit();
	}
}
function default_security(){
	global $assign_list,$mod,$act,$core,$clsConfiguration,$dbconn;
	if(isset($_POST['submit']) && $_POST['submit']=='UpdateConfiguration'){
		foreach($_POST as $key=>$val){
			$tmp = explode('-',$key);
			if($tmp[0]=='iso'){
				$clsConfiguration->updateValue($tmp[1],$val);
			}
		}
		$clsConfiguration->updateValue("captcha_type",addslashes(Input::post('captcha_type','IMG')));
		$clsConfiguration->updateValue("site_status",addslashes(Input::post('site_status','ON')));
		#activity log		
		$clsActivityLog = new ActivityLog();
		$log = $clsActivityLog->addActivityLog("Configuration","update",['field' =>"security"]);
		header('Location:'.PCMS_URL.'?mod=setting&act='.$act.'&message=updateSuccess');
		exit();
	}
}
function default_mailconfig(){
	global $assign_list,$clsISO,$core,$clsConfiguration,$dbconn;
	if(isset($_POST['submit']) && $_POST['submit']='UpdateConfiguration'){
		foreach($_POST as $key=>$val){
			$tmp = explode('-',$key);
			if($tmp[0]=='iso'){
				$clsConfiguration->updateValue($tmp[1],$val);
			}
		}
		$mail_type = Input::post('mail_type','smtp');
		if($mail_type=='sendgrid'){
			$mail_sendgrid_api_enable = Input::post('mail_sendgrid_api_enable',0);
			$mail_sendgrid_api_enable = $clsISO->toInt($mail_sendgrid_api_enable);
			$clsConfiguration->updateValue('mail_sendgrid_api_enable',$mail_sendgrid_api_enable);
			/** Update pass Sendgrid */
			$mail_sengrid_password = Input::post('mail_sengrid_password');
			if(!empty($mail_sengrid_password)){
				$clsConfiguration->updateValue('mail_sengrid_password',$mail_sengrid_password);
			}
		} else if($mail_type == 'brevo') {
			$mail_brevo_password = Input::post('mail_brevo_password');
			if(!empty($mail_brevo_password)){
				$clsConfiguration->updateValue('mail_brevo_password',$mail_brevo_password);
			}
		} else {
			$mail_smtp_authentication = Input::post('mail_smtp_authentication',0);
			$mail_smtp_authentication = $clsISO->toInt($mail_smtp_authentication);
			$clsConfiguration->updateValue('mail_smtp_authentication',$mail_smtp_authentication);
			/** Update pass SMTP */
			$mail_smtp_password = Input::post('mail_smtp_password');
			if(!empty($mail_smtp_password)){
				$clsConfiguration->updateValue('mail_smtp_password',$mail_smtp_password);
			}
		}	
		#activity log		
		$clsActivityLog = new ActivityLog();
		$log = $clsActivityLog->addActivityLog("Configuration","update",['field' =>"mailconfig"]);
		header('location:'.PCMS_URL.'?mod=setting&act=mailconfig&message=updateSuccess');
		exit();
	}
}
function default_mailconfig_active(){
	global $assign_list,$core,$clsConfiguration,$dbconn;
	$mail_type = Input::post('mail_type','sendgrid');
	$clsConfiguration->updateValue('mail_type',$mail_type);
	echo(1); die();
}
function default_sendmail_test(){
	global $smarty,$assign_list,$_frontIsLoggedin_user_id,$core,$clsISO,$clsConfiguration,$clsUser;
	/** Info E-mail */
	$mail_type = Input::post('mail_type','smtp');
	$fromname  = $clsConfiguration->getValue('mail_'.$mail_type.'_fromname');
	$fromemail = $clsConfiguration->getValue('mail_'.$mail_type.'_fromemail');
	/** Config E-mail */
	$toemail = $core->_USER['email'];
	$toname = $clsUser->getFullName($core->_USER['user_id']);
	$subject = 'TacoWeb - Test Connection';
	$message = 'Test Connection with TacoWeb';
	/* End */
	if($mail_type=='smtp'){
		$msg = ''; // message after send email
		$mail_smtp_username = trim($clsConfiguration->getValue('mail_smtp_username'));
		$mail_smtp_password = trim($clsConfiguration->getValue('mail_smtp_password'));
		$mail_smtp_secure = trim($clsConfiguration->getValue('mail_smtp_secure'));
		$mail_smtp_host = trim($clsConfiguration->getValue('mail_smtp_host'));
		$mail_smtp_port = trim($clsConfiguration->getValue('mail_smtp_port'));
		$mail_smtp_authentication = $clsConfiguration->getValue('mail_smtp_authentication',0);
		require_once(DIR_INCLUDES.'/mailer/smtp/class.phpmailer.php');
		$mail = new PHPMailer(true);
		try {
			$mail->CharSet = 'utf-8';
			$mail->XMailer = $clsConfiguration->getValue("company_name");
			$mail->From = $fromemail;
			$mail->FromName = html_entity_decode($fromname,ENT_QUOTES);
			$mail->AddAddress(trim($toemail));
			// SMTP
			$mail->IsSMTP();
			$mail->Hostname = $_SERVER['SERVER_NAME'];
			if(!empty($mail_smtp_host)) $mail->Host = $mail_smtp_host;
			if(!empty($mail_smtp_port)) $mail->Port = $mail_smtp_port;
			if($mail_smtp_secure != 'none') $mail->SMTPSecure = $mail_smtp_secure;
			// Authenticate
			if($mail_smtp_authentication){
				$mail->SMTPAuth = true;
				$mail->Username = $mail_smtp_username;
				$mail->Password = $mail_smtp_password;
			}
			$mail->Sender = $mail->From;
			// Content
			$mail->isHTML(true);
			$mail->Subject = html_entity_decode($subject,ENT_QUOTES);
			$mail->Body = $message;
			// Send
			if($mail->Send()){
				$status = 'success';
				$msg = $core->get_Lang('Send test email success');
			}
			$mail->ClearAddresses();
		} catch (Exception $e) {
			$status = 'error';
			$msg = $mail->ErrorInfo;
		}
	} else if($mail_type=='sendgrid'){
		$mail_sendgrid_api = $clsISO->toInt(trim($clsConfiguration->getValue('mail_sendgrid_api_enable')));
		$mail_sendgrid_api_key = trim($clsConfiguration->getValue('mail_sendgrid_api_key'));
		$mail_sendgrid_api_url = trim($clsConfiguration->getValue('mail_sendgrid_api_url'));
		$mail_sendgrid_username = trim($clsConfiguration->getValue('mail_sendgrid_username'));
		$mail_sendgrid_password = trim($clsConfiguration->getValue('mail_sendgrid_password'));
		if($mail_sendgrid_api){
			if(!empty($mail_sendgrid_api_key)){
				$params = array(
					'personalizations' => array(
						array(
							'subject' => $subject,
							'to' => array(
								array(
									'email'	=> $toemail,
									'name' => $toname,
								),
							),
						)
					),
					'from' => array(
						"name" => $fromname,
						"email" => $fromemail
					),
					'reply_to' => array(
						"name" => $fromname,
						"email" => $fromemail
					),
					'content' => array(
						array(
							"type" => 'text/html',
							"value" => html_entity_decode($message)
						),
					)
				);
				// Generate curl request
				$ch = @curl_init($mail_sendgrid_api_url);
				// Tell curl to use HTTP TimeOut
				curl_setopt($ch,CURLOPT_ENCODING,"utf-8");
				curl_setopt($ch,CURLOPT_MAXREDIRS,30);
				curl_setopt($ch,CURLOPT_TIMEOUT,60);
				// Tell curl to use HTTP Version
				curl_setopt($ch,CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);
				// Tell curl not to return headers,but do return the response
				curl_setopt($ch,CURLOPT_HEADER,false);
				// Tell curl to 
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
				// Tell curl to use HTTP POST
				curl_setopt ($ch,CURLOPT_POST,true);
				// Tell curl that this is the body of the POST
				curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
            	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
				curl_setopt ($ch,CURLOPT_POSTFIELDS,@json_encode($params));
				curl_setopt($ch,CURLOPT_HTTPHEADER,array(
					'Content-Type: application/json',
					'Authorization: Bearer '.$mail_sendgrid_api_key
				));
				// obtain response
				$response = curl_exec($ch);
				$err = curl_error($ch);
				@curl_close($ch);
				// print everything out
				$status = 'error';
				$msg =  $core->get_Lang('Send test email error');
				if ($err) {
					$status = 'success';
					$msg = $core->get_Lang('Send test email successfully');
				}
			} else {
				//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
				$params = array(
					'api_user' => $mail_sendgrid_username,
					'api_key' => $mail_sendgrid_password,
					'to' => $toemail,
					'replyto' => $toemail,
					'subject' => $subject,
					'html' => $message,
					'from' => $fromemail,
					'fromname' => $fromname
				);
				// Generate curl request
				$ch = curl_init($mail_sendgrid_api_url);
				// Tell curl to use HTTP POST
				curl_setopt ($ch,CURLOPT_POST,true);
				// Tell curl that this is the body of the POST
				curl_setopt ($ch,CURLOPT_POSTFIELDS,$params);
				// Tell curl not to return headers,but do return the response
				curl_setopt($ch,CURLOPT_HEADER,false);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
				curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
            	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
				// obtain response
				$response = curl_exec($ch);
				curl_close($ch);
				// print everything out
				$status = 'error';
				$msg =  $core->get_Lang('Send test email error');
				if(strpos($response,'success') == true){
					$status = 'success';
					$msg = $core->get_Lang('Send test email successfully');
				}
			}
		} else {
			if(!empty($mail_sendgrid_api_key)){
				require_once(DIR_INCLUDES.'/mailer/SendGrid3.0/vendor/autoload.php');
				$email = new \SendGrid\Mail\Mail();
				$email->setFrom($fromemail,$fromname);
				$email->setSubject($subject);
				$email->addTo($toemail,"");
				$email->addContent("text/html",$message);
				$sendgrid = new \SendGrid($mail_sendgrid_api_key);
				try {
					$response = $sendgrid->send($email);
					if($response->statusCode()=='200' || $response->statusCode()=='202'){
						$status = 'success';
						$msg =  $core->get_Lang('Send test email successfully');
					}
				} catch (Exception $e) {
					$status = 'error';
					$msg = $e->getMessage();
				}
			} else {
				require_once(DIR_INCLUDES.'/mailer/SendGrid/vendor/autoload.php');
				$sendgrid = new SendGrid(
					$mail_sendgrid_username,
					$mail_sendgrid_password,
					array("turn_off_ssl_verification" => false)
				);
				// Create object
				$mail = new \SendGrid\Email();
				// Param send email
				$mail->addTo($toemail)
					  ->setFrom($fromemail)
					  ->setFromName($fromname)
					  ->setReplyTo($toemail)
					  ->setSubject($subject)
					  ->setHtml($message)
					  ->addHeader('X-Sent-Using','SendGrid-API')
					  ->addHeader('X-Transport','web');
				// obtain response
				$response = $sendgrid->send($mail);
				// Return
				$status = 'error';
				$msg =  $core->get_Lang('Send test email error');
				if(isset($response->body['message']) 
				   && $response->body['message']=='success'){
					$status = 'success';
					$msg =  $core->get_Lang('Send test email successfully');
				}
			}
		}
		/** End Sendgrid */ 
	}
	unset($oneMailbox);
	unset($more_information);
	// output
	echo $status.'|||'.$msg;
	die();
}
/**
 * Màn "Thông tin công ty" đã gộp hẳn vào Cấu hình hệ thống — nhóm brand/company/
 * social trong ConfigDeclaration, cùng key nên dữ liệu giữ nguyên.
 * Giữ lại act này chỉ để chuyển hướng: nút vào màn cũ nằm trong bảng adminbutton
 * chứ không nằm trong code, xoá hàm là link đó 404.
 */
function default_profile(){
	header('Location:'.PCMS_URL.'?mod=setting&act=general#cfg-company');
	exit();
}
function default_permission(){
	global $assign_list,$core,$clsConfiguration,$dbconn;
	if(isset($_POST['submit']) && $_POST['submit']=='SavePermission'){
		// Chỉ nhận số nguyên -> ghi file PHP an toàn (không chèn mã tùy ý)
		$supper = array();
		if(isset($_POST['perm_supper']) && is_array($_POST['perm_supper'])){
			foreach($_POST['perm_supper'] as $v){ $v=(int)$v; if($v>0){ $supper[$v]=$v; } }
		}
		$supper		= array_values($supper);
		$ceo		= isset($_POST['perm_ceo']) ? (int)$_POST['perm_ceo'] : 0;
		$tech		= isset($_POST['perm_tech']) ? (int)$_POST['perm_tech'] : 0;
		$admin		= isset($_POST['perm_admin']) ? (int)$_POST['perm_admin'] : 0;
		$userAdmin	= isset($_POST['perm_user_admin']) ? (int)$_POST['perm_user_admin'] : 0;
		$php  = "<?php if (!defined('ABSPATH')) exit('No direct script access allowed');\n";
		$php .= "/** Nhan su & phan quyen — sinh tu dong tu admin (Cau hinh). Chi chua so nguyen. */\n";
		$php .= "define('_USER_ADMIN_SUPER_ID', ".$userAdmin.");\n";
		$php .= "define('_PROFILE_CEO_ID', ".$ceo.");\n";
		$php .= "define('_PROFILE_TECH_ID', ".$tech.");\n";
		$php .= "define('_PROFILE_ADMIN_ID', ".$admin.");\n";
		$php .= "define('_PROFILE_SUPPER_ID', array(".implode(',', $supper)."));\n";
		$ok = @file_put_contents(ROOTPATH.'/configs/business.php', $php, LOCK_EX);
		$msg = ($ok===false) ? 'writeError' : 'updateSuccess';
		header('location:'.PCMS_URL.'?mod=setting&act=permission&message='.$msg);
		exit;
	}
	$clsProfile = new Profile();
	$rows = $clsProfile->getAll("is_trash=0 and status_id='"._STATUS_STAFF_ON_ID."'", "{$clsProfile->pkey},full_name,code");
	$staffList = array();
	if(!empty($rows)){ foreach($rows as $r){ $staffList[$r['profile_id']] = $r; } }
	$assign_list['staffList']		= $staffList;
	$assign_list['sel_supper']		= defined('_PROFILE_SUPPER_ID') ? array_flip((array)_PROFILE_SUPPER_ID) : array();
	$assign_list['cur_ceo']			= defined('_PROFILE_CEO_ID') ? _PROFILE_CEO_ID : 0;
	$assign_list['cur_tech']		= defined('_PROFILE_TECH_ID') ? _PROFILE_TECH_ID : 0;
	$assign_list['cur_admin']		= defined('_PROFILE_ADMIN_ID') ? _PROFILE_ADMIN_ID : 0;
	$assign_list['cur_user_admin']	= defined('_USER_ADMIN_SUPER_ID') ? _USER_ADMIN_SUPER_ID : 0;
}
function default_livechat(){
	global $assign_list,$core,$clsConfiguration,$dbconn;
	#
	if(isset($_POST['submit']) && $_POST['submit']='UpdateConfiguration'){
		foreach($_POST as $key=>$val){
			$tmp = explode('-',$key);
			if($tmp[0]=='iso'){
				$clsConfiguration->updateValue($tmp[1],$val);
			}
		}
		header('location:'.PCMS_URL.'?mod=setting&act=livechat&message=updateSuccess');
	}
}
function default_pre_order(){
	global $assign_list,$core,$clsConfiguration,$dbconn;
	#
	if(isset($_POST['submit']) && $_POST['submit']='UpdateConfiguration'){
		foreach($_POST as $key=>$val){
			$tmp = explode('-',$key);
			if($tmp[0]=='iso'){
				$clsConfiguration->updateValue($tmp[1],$val);
			}
		}
		header('location:'.PCMS_URL.'?mod=setting&act=pre_order&message=updateSuccess');
	}
}
/* Price Range */
function default_price_range(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$assign_list["clsModule"] = $clsModule;
}
function default_load_list_price_range(){
	global $dbconn,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$core,$clsModule;
	$user_id = $core->_USER['user_id'];
	$clsPagination = new Pagination();
	$clsPriceRange = new PriceRange();
	$clsISO = new ISO();
	#
	$cond="is_trash=0";
	$keyword = Input::post('keyword', "");
	if(!empty($keyword)){
		$slug = $core->replaceSpace($keyword);
		$cond.=" and (slug like '%{$slug}%' or title like '%{$keyword}%')";
	}
	#pagination
	$current_page = (int) Input::post('page',1);
	$number_per_page = (int) Input::post('number_per_page',10);
	$total_record = $clsPriceRange->countItem($cond);
	$offset = ($current_page-1)*$number_per_page;
	$cond.=" ORDER BY order_no ASC";
	$cond.=" LIMIT {$offset},{$number_per_page}";
	#
	$html = '<table class="table table-vertical table-striped m-0" cellpadding="0" cellspacing="0" style="width:100%">
	<thead><tr>
		<th class="text-center" style="width:4%">No.</th>
		<th class="text-left"><strong>Tiêu đề</strong></th>
		<th class="text-right"><strong>Giá nhỏ nhất</strong></th>
		<th class="text-right"><strong>Giá lớn nhất</strong></th>
		<th class="text-center" width="3%"><i class="icon-circle-arrow-up"></i></th>
		<th class="text-center" width="3%"><i class="icon-circle-arrow-down"></i></th>
		<th class="text-center" width="3%"><i class="icon-arrow-up"></i></th>
		<th class="text-center" width="3%"><i class="icon-arrow-up"></i></th>
		<th class="text-center" width="80px">'.$core->get_Lang('Action').'</th>
	</tr></thead>';
	$lstItem = $clsPriceRange->getAll($cond);
	if(!empty($lstItem)){ $i=0; // Init
		foreach($lstItem as $item){
			$price_range_id = $item[$clsPriceRange->pkey];
			$html.='<tr>
				<td class="text-center">'.($i+1).'</td>
				<td class="text-left">'.$clsPriceRange->getTitle($price_range_id).'</td>
				<td class="text-right">'.$clsPriceRange->getMin($price_range_id).'&nbsp;'.$clsISO->getRate().'</td>
				<td class="text-right">'.$clsPriceRange->getMax($price_range_id).'&nbsp;'.$clsISO->getRate().'</td>
				<td style="vertical-align: middle;text-align:center">
					'.($i==0?'':'<a href="#" title="Move top" class="btn_movePriceRange" direct="movetop" price_range_id="'.$price_range_id.'"><i class="icon-circle-arrow-up"></i></a>').'
				</td>
                <td style="vertical-align: middle;text-align:center">
					'.($i==count($lstItem)-1 ? '' : '<a  href="#" class="btn_movePriceRange" direct="movebottom" price_range_id="'.$price_range_id.'"><i class="icon-circle-arrow-down"></i></a>').'
				</td>
				<td style="vertical-align: middle;text-align:center">
					'.($i==0?'':'<a href="#" class="btn_movePriceRange" direct="moveup" price_range_id="'.$price_range_id.'"><i class="icon-arrow-up"></i></a>').'
                </td>
				<td style="vertical-align: middle;text-align:center">
					'.($i==count($lstItem)-1 ? '' : '<a href="#" title="Move down" class="btn_movePriceRange" direct="movedown" price_range_id="'.$price_range_id.'" ><i class="icon-arrow-down"></i></a>').'
				</td>
				<td class="text-center">
					<div class="btn-group">
						<a href="#" title="Sửa" class="btn btn-xs btn-default btn_editPriceRange" price_range_id="'.$price_range_id.'"><i class="icon-edit"></i></a>
						<a href="#" title="Xóa" class="btn btn-xs btn-default btn_deletePriceRange" price_range_id="'.$price_range_id.'"><i class="icon-remove"></i></a>
					</div>
				</td>
			</tr>';
			++$i;
		}
	}else{
		$html.='<tr><td style="text-align:center" colspan="7">Not any data</td></tr>';
	}
	$html .= '</table>';
	if($total_record >0){
		$html .= '<div class="easyui-pagination" id="pager_price_range" pageNumber="'.$current_page.'" pageList="[5,10,15]"></div>';
	}
	// Return
	echo json_encode(array(
		'html' => $html,
		'total_record' => $total_record,
		'number_per_page' => $number_per_page
	)); die();
};
function default_open_price_range(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$clsISO;
	$user_id = $core->_USER['user_id'];
	$clsPriceRange = new PriceRange();
	$oneItem = array('title' =>"",'min_rate' => 0,'max_rate' => 0);
	$price_range_id = (int) Input::post('price_range_id',0);
	if($price_range_id > 0){
		$oneItem = $clsPriceRange->getOne($price_range_id);
	}
	$html = '<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header"> 
				<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
				<h3 class="modal-title"><strong>Thêm mới khoảng giá</strong></h3>
			</div>
			<form method="post" action="" enctype="multipart/form-data">
				<div class="modal-body">
					<div class="form-group">
						<label for="" class="col-form-label text-right col-md-3">Tiêu đề</label>
						<div class="col-md-9">
							<input class="form-control required" name="title" value="'.$oneItem['title'].'" type="text">
						</div>
					</div>
					<div class="form-group">
						<label for="" class="col-form-label text-right col-md-3">Nhỏ nhất</label>
						<div class="col-md-9">
							<div class="input-group">
								<input class="form-control numberonly required price-In" value="'.$oneItem['min_rate'].'" name="min_rate" type="text">
								<span class="input-group-addon">'.$clsISO->getRate().'</span>
							</div>
							<em class="help-block">Ex:40.000 '.$clsISO->getRate().'</em>
						</div>
					</div>
					<div class="form-group">
						<label for="" class="col-form-label text-right col-md-3">Lớn nhất</label>
						<div class="col-md-9">
							<div class="input-group">
								<input class="form-control numberonly required price-In" value="'.$oneItem['max_rate'].'" name="max_rate" type="text">
								<span class="input-group-addon">'.$clsISO->getRate().'</span>
							</div>
							<em class="help-block">Ex:60.000 '.$clsISO->getRate().'</em>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">'.$core->get_Lang('Close').'</button>
					<button type="button" class="btn btn-primary savePriceRange" price_range_id="'.$price_range_id.'">
						'.$core->makeIcon('check',$core->get_Lang('Save')).'
					</button>
				</div>
			</form>
		</div>
	</div>';
	echo($html);die();
}
function default_save_price_range(){
	global $assign_list,$_CONFIG,$_LANG_ID,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$clsISO;
	$user_id = $core->_USER['user_id'];
	$clsPriceRange = new PriceRange();
	#
	$price_range_id = (int) Input::post('price_range_id',0);
	$title = Input::post('title');
	$slug = $core->replaceSpace($title);
	$min_rate = addslashes(Input::post('min_rate'));
	$max_rate = addslashes(Input::post('max_rate'));
	$msg = '_error';
	if($price_range_id == 0){
		$field="title,slug,min_rate,max_rate,order_no";
		$value="'{$title}','{$slug}','".$clsISO->processSmartNumber($min_rate)."','".$clsISO->processSmartNumber($max_rate)."'";
		$value.=",'".$clsPriceRange->getMaxOrderNo()."'";
		if($clsPriceRange->insertOne($field,$value)){
			$msg = '_success';	
		}
	}else{
		$set="title='{$title}',slug='{$slug}',min_rate='".$clsISO->processSmartNumber($min_rate)."',max_rate='".$clsISO->processSmartNumber($max_rate)."'";
		if($clsPriceRange->updateOne($price_range_id,$set)){
			$msg = '_success';
		}
	}
	// Return
	echo $msg; die();
}
function default_delete_price_range(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$clsPriceRange = new PriceRange();
	#
	$price_range_id = (int) Input::post('price_range_id', 0);
	$clsPriceRange->deleteOne($price_range_id);
	echo(1); die();
}
function default_move_price_range(){
	global $dbconn,$assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$core,$clsModule;
	$clsPriceRange = new PriceRange();
	$direct = Input::post('direct');
	$price_range_id = (int) Input::post('price_range_id', 0);
	$oneItem = $clsPriceRange->getOne($price_range_id);
	$order_no = $oneItem['order_no'];
	$where = "is_trash=0";
	if($direct=='moveup'){
		$lst = $clsPriceRange->getAll("{$where} and order_no<'{$order_no}' order by order_no DESC limit 0,1");
		$clsPriceRange->updateOne($price_range_id,"order_no='".$lst[0]['order_no']."'");
		$clsPriceRange->updateOne($lst[0][$clsPriceRange->pkey],"order_no='".$order_no."'");
	}
	else if($direct=='movedown'){
		$lst = $clsPriceRange->getAll("{$where} and order_no>'{$order_no}' order by order_no ASC limit 0,1");
		$clsPriceRange->updateOne($price_range_id,"order_no='".$lst[0]['order_no']."'");
		$clsPriceRange->updateOne($lst[0][$clsPriceRange->pkey],"order_no='".$order_no."'");
	}
	else if($direct=='movetop'){
		$lst = $clsPriceRange->getAll("{$where} and order_no<'$order_no' order by order_no ASC");
		$clsPriceRange->updateOne($price_range_id,"order_no='".$lst[0]['order_no']."'");
		unset($lst);
		$clsPriceRange->updateByCond("{$where} and price_range_id<>'{$price_range_id}' and order_no<'{$order_no}'", "order_no=order_no+1");
	}
	else if($direct=='movebottom'){
		$lst = $clsPriceRange->getAll("{$where} and order_no>'{$order_no}' order by order_no DESC");
		$clsPriceRange->updateOne($price_range_id,"order_no='".$lst[0]['order_no']."'");
		unset($lst);
		$clsPriceRange->updateByCond("{$where} and price_range_id<>'{$price_range_id}' and order_no>'{$order_no}'", "order_no=order_no-1");
	}
	echo(1); die();
}
/* IP Manager */
function default_ip(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
	global $core,$clsModule,$clsButtonNav,$oneSetting;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];
}
function default_ajaxLoadListIP(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page;
	#
	$clsIP = new IP();
	$clsPagination = new Pagination();
	#
	$cond = "is_trash=0";
	$number_per_page = isset($_POST['number_per_page'])?$_POST['number_per_page']:10;
	$page = isset($_POST['page'])?$_POST['page']:1;
	#
	$keyword = isset($_POST['keyword'])?trim($_POST['keyword']):'';
	if($keyword!=''){
		$cond.=" and value like '%$keyword%'";
	}
	$totalRecord = $clsIP->countItem($cond);
	$pageview = $clsPagination->pagination_ajax($totalRecord,$number_per_page,$page);
	$offset = ($page-1)*$number_per_page;
	$order_by = " ORDER BY ip_id DESC";
	$limit = " LIMIT $offset,$number_per_page";
	$html='';
	$listItem= $clsIP->getAll($cond.$order_by.$limit,$clsIP->pkey.',upd_date');
	if(!empty($listItem)){
		$i=0;
		foreach($listItem as $k=>$v){
			$html.='<tr class="'.($i%2==0?'row1':'row2').'">';
			$html.='<td class="index">'.($i+1).'</td>';
			$html.='<td><a href="javascript:void();" class="btn_edit_ip" data="'.$v[$clsIP->pkey].'">
						<strong style="font-size:14px;">'.$clsIP->getOneField("value",$v[$clsIP->pkey]).'</strong></a>
					</td>';
			$html.='<td class="color_r text-right">'.date('d-m-Y h:i',$v['upd_date']).'</td>
					<td align="center" style="text-align:center;">
						<a class="btn_edit_ip" data="'.$v[$clsIP->pkey].'" href="javascript:void();">
							<i class="icon-pencil"></i>
						</a>
						<a class="btn_delete_ip" data="'.$v[$clsIP->pkey].'" href="javascript:void();">
							<i class="icon-remove"></i>
						</a>
					</td>';
			$html.='</tr>';
			++$i;
		}
	}else{
		$html='<tr>
			<td colspan="6">
				<div class="infobox"> 
					<strong>Waring.</strong>
					<br> Không có dữ liệu phù hợp. 
				</div>
			</td>
	  </tr>';
	}
	echo $html.'$$'.$pageview; die();
}
function default_ajaxFrmIP(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page;
	#
	$clsIP = new IP();
	$ip_id = isset($_POST['ip_id'])?$_POST['ip_id']:'0';
	$tp = isset($_POST['tp'])?$_POST['tp']:'';
	$html='';
	$html.='<div class="headPop">
		<a class="closeEv close_pop" href="javascript:void();" title="Đóng"></a>
		<h3>'.(intval($ip_id)==0?'Thêm mới':'Cập nhật').' IP</h3>
	</div>';
	$html.='<table width="100%" cellspacing="2" cellpadding="5" border="0" class="form">
		<tbody>
			<tr>
				<td style="width:20%; padding:10px 10px 0 0;vertical-align:top" class="fieldlabel">IP<font color="#c00000">*</font></td>
				<td class="fieldarea">
					<input tabindex="1" name="ip_address" value="'.(intval($ip_id)==0 ? $tp : $clsIP->getOneField('value',$ip_id)).'" type="text" class="text full fontLarge required span95">
					<div class="clearfix mt5"></div>
					<span class="notice-full" style="padding-left:0;">Ex:192.168.1.1</span>
				</td>
			</tr>
		</tbody>
	</table>
	<div class="modal-footer">
		<button type="button" ip_id="'.$ip_id.'" class="btn btn-primary" id="ajSaveIP">
			<i class="icon-ok icon-white"></i> <span>Cập nhật</span>
		</button>
		<button type="reset" class="btn btn-warning close_pop">
			<i class="icon-retweet icon-white"></i> <span>Đóng lại</span>
		</button>
	</div>';
	echo $html; die();
}
function default_ajaxSaveIP(){
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$clsISO;
	$user_id = $core->_USER['user_id'];
	#
	$clsIP = new IP();
	$ip_address = $_POST['ip_address'];
	$ip_id = $_POST['ip_id'];
	#
	if(intval($ip_id)==0){
		$allItem = $clsIP->getAll("is_trash=0 and value='$ip_address' limit 0,1");
		if(!empty($allItem)){
			echo 'IP_EXIST'; die();
		}else{
			$f = "value,reg_date,upd_date,is_online";
			$v = "'".addslashes($ip_address)."','".time()."','".time()."','1'";
			if($clsIP->insertOne($f,$v)){
				echo 'INSERT_SUCCESS'; die();
			}else{
				echo 'ERROR'; die();
			}
		}
	}else{
		$v = "value='".addslashes($ip_address)."',upd_date='".time()."'";
		if($clsIP->updateOne($ip_id,$v)){
			echo 'UPDATE_SUCCESS'; die();
		}else{
			echo 'ERROR'; die();
		}
	}
	echo $message; die();
}
function default_ajaxdeleteOneIP(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page;
	#
	$clsIP=new IP();
	$ip_id=$_POST['ip_id'];
	$clsIP->deleteOne($ip_id);
	echo(1); die();
}
function default_ajaxFrmCfgIPMode(){
	global $core;
	#
	$clsSetting = new Setting();
	$oneTable = $clsSetting->getOne(1);
	$html='
	<div class="headPop"> 
		<a id="clickToCloseConfigBooking" href="javascript:void();" class="closeEv close_pop">&nbsp;</a> 
		<h3>Cài đặt IP Mode</h3>
	</div> 
	<div class="formatTextStandard" style="margin-bottom:10px">Chức năng tùy chọn có hay không kiểm tra IP người truy cập.</div> 
	<form method="post" class="frmform" enctype="multipart/form-data" id="frmSettingInfo">
		<table class="form" cellpadding="3" cellspacing="3">
			<tr>
				<td class="fieldlabel" width="20%">Chế độ</td>
				<td class="fieldarea">
					<label class="fl">Cho phép <input type="radio" '.($oneTable['config_public_mode']==1?'checked="checked"':'').' name="config_public_mode" value="1" /></label>
					<label class="fl">Không cho phép <input type="radio" '.($oneTable['config_public_mode']==0?'checked="checked"':'').' name="config_public_mode" value="0" /></label>
				</td>
			</tr>
		</table>
		<div class="modal-footer"> 
			<button class="btn btn-success ajSaveCfgIPMode">'.$core->get_Lang('Save').'</button> 
			<button class="btn btn-warning clickToClose close_pop" data-dismiss="modal" aria-hidden="true">'.$core->get_Lang('Close').'</button>
		</div>
	</form>';
	echo $html; die();
}
function default_ajaxSaveCfgIPMode(){
	$clsSetting = new Setting();
	$oneTable = $clsSetting->getOne(1);
	#
	$clsSetting->updateOne(1,"config_public_mode='".$_POST['config_public_mode']."'");
	echo(1); die();
}
function default_pay(){
	global $assign_list,$core,$clsConfiguration,$clsISO;	
	$clsConfiguration= new Configuration();
	$assign_list['clsConfiguration'] = $clsConfiguration;
	if(isset($_POST['Hid_Pay1']) && $_POST['Hid_Pay1']='Hid_Pay1'){
		foreach($_POST as $key=>$val){
			$tmp = explode('-',$key);
			if($tmp[0]=='iso'){
				$clsConfiguration->updateValue($tmp[1],$val);
			}
		}
		if(isset($_POST['SitePay_CashStatus_Mode'])){
			$clsConfiguration->updateValue('SitePay_CashStatus_Mode',$_POST['SitePay_CashStatus_Mode']);
		}else{
			$clsConfiguration->updateValue('SitePay_CashStatus_Mode',0);
		}
		#-update hash onepay
		redirect(PCMS_URL.'?mod=setting&act=pay&message=updateSuccess');
	}#
	if(isset($_POST['Hid_Pay2']) && $_POST['Hid_Pay2']='Hid_Pay2'){
		foreach($_POST as $key=>$val){
			$tmp = explode('-',$key);
			if($tmp[0]=='iso'){
				$clsConfiguration->updateValue($tmp[1],$val);
			}
		}
		if(isset($_POST['SitePay_Bank_Mode'])){
			$clsConfiguration->updateValue('SitePay_Bank_Mode',$_POST['SitePay_Bank_Mode']);
		}else{
			$clsConfiguration->updateValue('SitePay_Bank_Mode',0);
		}
		#-update hash onepay
		redirect(PCMS_URL.'?mod=setting&act=pay&message=updateSuccess');
	}
	if(isset($_POST['Hid_Pay4']) && $_POST['Hid_Pay4']='Hid_Pay4'){
		foreach($_POST as $key=>$val){
			$tmp = explode('-',$key);
			if($tmp[0]=='iso'){
				$clsConfiguration->updateValue($tmp[1],$val);
			}
		}
		#ONEPAY Status Mode
		if(isset($_POST['ONEPAY_Status_Mode'])){
			$clsConfiguration->updateValue('ONEPAY_Status_Mode',$_POST['ONEPAY_Status_Mode']);
		}else{
			$clsConfiguration->updateValue('ONEPAY_Status_Mode',0);
		}
		if(isset($_POST['ONEPAY_Test_Mode'])){
			$clsConfiguration->updateValue('ONEPAY_Test_Mode',$_POST['ONEPAY_Test_Mode']);
		}else{
			$clsConfiguration->updateValue('ONEPAY_Test_Mode',0);
		}
		#-update hash onepay
		redirect(PCMS_URL.'?mod=setting&act=pay&message=updateSuccess');
	}
	if(isset($_POST['Hid_Pay5']) && $_POST['Hid_Pay5']='Hid_Pay5'){
		foreach($_POST as $key=>$val){
			$tmp = explode('-',$key);
			if($tmp[0]=='iso'){
				$clsConfiguration->updateValue($tmp[1],$val);
			}
		}
		if(isset($_POST['ONEPAY_Visa_Status_Mode'])){
			$clsConfiguration->updateValue('ONEPAY_Visa_Status_Mode',$_POST['ONEPAY_Visa_Status_Mode']);
		}else{
			$clsConfiguration->updateValue('ONEPAY_Visa_Status_Mode',0);
		}
		#ONEPAY Mode
		if(isset($_POST['ONEPAY_Visa_Test_Mode'])){
			$clsConfiguration->updateValue('ONEPAY_Visa_Test_Mode',$_POST['ONEPAY_Visa_Test_Mode']);
		}else{
			$clsConfiguration->updateValue('ONEPAY_Visa_Test_Mode',0);
		}
		#-update hash onepay
		redirect(PCMS_URL.'?mod=setting&act=pay&message=updateSuccess');
	}
}
function default_message(){
	global $assign_list,$core,$clsConfiguration,$mod,$act,$dbconn;
	#
	$listMessage = $clsConfiguration->getAll("setting like 'SiteMsg_%'");
	$assign_list["listMessage"] = $listMessage;  
	#
	if(isset($_POST['submit']) && $_POST['submit']='UpdateConfiguration'){
		foreach($_POST as $key=>$val){
			$tmp = explode('-',$key);
			if($tmp[0]=='iso'){
				//var_dump($val); die();
				$clsConfiguration->updateValue($tmp[1], $val);
			}
		}	
		#activity log		
		$clsActivityLog = new ActivityLog();
		$log = $clsActivityLog->addActivityLog("Configuration","update",['field' =>"message"]);
		header('location:'.PCMS_URL.'?mod='.$mod.'&act='.$act.'&message=updateSuccess');
		exit();
	}
}
function default_open_message(){
	global $smarty,$core,$clsConfiguration,$clsISO;
	// Return
	$smarty->assign('core',$core);
	$html = $core->build('message.open.tpl');
	echo $html; die();
}
function default_add_message(){
	global $smarty,$core,$clsConfiguration,$clsISO;
	$action = Input::post('action','add');
	$setting = Input::post('setting');
	if($action=='delete'){
		$clsConfiguration->deleteByCond("setting='{$setting}'");
	} else {
		$clsConfiguration->updateValue("SiteMsg_{$setting}","");
	}
	echo 1; die();
}
function default_oauth(){
	global $assign_list,$core,$dbconn,$mod,$act;
	$clsConfiguration = new Configuration();
	if(isset($_POST['submit']) && $_POST['submit']='UpdateConfiguration'){
		foreach($_POST as $key=>$val){
			$tmp = explode('-',$key);
			if($tmp[0]=='iso'){
				$clsConfiguration->updateValue($tmp[1],$val);
			}
		}
		header('location:'.PCMS_URL.'?mod='.$mod.'&act='.$act.'&message=updateSuccess');
	}
}
function default_handler_oauth_login(){
	global $assign_list,$core,$dbconn,$mod,$act;
	$clsConfiguration = new Configuration();
	$status = Input::post('status',1);
	$oauth_type = Input::post('oauth_type','google');
	$clsConfiguration->updateValue("{$oauth_type}_login",$status);
	// Return
	echo(1); die();
}
function default_fpoint(){
	global $assign_list,$core,$dbconn,$mod,$act,$clsISO;
	$clsProperty = new Property();
	$clsConfiguration = new Configuration();
	###
	$total_departments = 0;
	$list_departments = $arr_departments = $tmp = array();
	$tmp = $clsProperty->makeList(_ROLE_STAFF_SALE, '_ROLE', $tmp);
	if(!empty($tmp)){
		foreach( $tmp as $key => $val) {
			if($val[$clsProperty->pkey] != _ROLE_TRIAL_SALE){
				$arr_departments[] = array(
					'id'	=> $val[$clsProperty->pkey],
					'title'	=> $val['title']
				);
			}
		}
		$total_departments = count($arr_departments);
		foreach($arr_departments as $key => $val){
			$list_departments[] = $val;
			if($val['id'] == _ROLE_GD_PROJECT){
				$list_departments[] = array(
					'id' => 'manage',
					'title' => 'Dự án phụ trách'
				);
			} else if ($val['id'] == _ROLE_REGIONAL_DIRECTOR_ID){
				$list_departments[] = array(
					'id' => 'region_director',
					'title' => 'Đội ngũ khu vực'
				);
			} else if($val['id'] == _ROLE_GD_SALE){
				$list_departments[] = array(
					'id' => 'dept',
					'title' => 'Đội ngũ bán'
				);
			} else if($val['id'] == _ROLE_HEAD_SALE){
				$list_departments[] = array(
					'id' => 'team',
					'title' => 'Đội ngũ bán'
				);
			}
		}
	}
	$assign_list['total_departments'] = $total_departments;
	$assign_list['list_departments'] = $list_departments;
	###
	$field = "{$clsProperty->pkey},`title`";
	$list_BO_levels = $clsProperty->getAll("`is_trash`=0 and `property_type`='_LEVEL_STAFF_BO' order by order_no ASC", $field);
	$assign_list['list_BO_levels'] = $list_BO_levels;
	###
	$list_points = array(
		'user.fh' => array(
			'title' => 'User.FH',
			'description' => 'Cấu hình điểm số F-Point cho mỗi hoạt động của nhân sự sẽ nhận được.',
			'actions' => array(
				'login' => 'Đăng nhập',
				'consulting' => 'Tiếp khách',
				'created_post' => 'Đăng bản tin' 
			)
		) ,
		'MOC' => array(
			'title' => 'MOC',
			'description' => 'Cấu hình điểm số F-Point cho mỗi hoạt động của nhân sự sẽ nhận được.',
			'actions' => array(
				'view_stock' => 'Click popup view căn',
				'view' => 'View bảng hàng',
				'search_stock' => 'Tra cứu căn hộ',
				'top_search' => 'Tìm kiếm top search',
				'invite_user' => 'Mời người dùng'
			)
		)		
	);
	$list_props = array();
	$tmp = $clsProperty->getCacheItems('_BILLING_TYPE');
	if(!empty($tmp)){
		foreach($tmp as $key => $val){
			$list_props[] = array(
				$clsProperty->pkey => $val[$clsProperty->pkey],
				'title' => $val['title']
			);
			$list_points['user.fh']['actions'][sprintf('billing_type_%s', $val[$clsProperty->pkey])] = $val['title'];
		}
	}
	$list_props[] = array(
		$clsProperty->pkey => 'seniority',
		'title' => 'Thâm niên/tháng'
	);
	$assign_list['list_props'] = $list_props;
	#- Require library
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	#- End require
	$fpoint_configs = array();
	$cachedFile = DIR_CACHE_JSON.'/fpoint.json';
	if(@file_exists($cachedFile)){
		$decoder = new Webmozart\Json\JsonDecoder();
		$fpoint_configs = $decoder->decodeFile($cachedFile);
	} else {
		foreach($list_points as $key => $val){
			foreach($val['actions'] as $okey => $oval){
				$fpoint_configs[$okey] = 0;
			}
		}
	}
	$assign_list['list_points'] = $list_points;
	$assign_list['fpoint_configs'] = $fpoint_configs;
	// Thang điểm theo GIÁ TRỊ giao dịch (đơn vị tỷ VND) — thi đua định danh 2026. Mỗi bậc:
	// from/to (tỷ, to=0 là "trở lên") + exclusive_score (điểm Độc Quyền) + cross_score (điểm Quỹ Chéo) + note.
	// 2 field điểm = giá trị hằng _FPOINT_TRANS_DOC_QUYEN/_FPOINT_TRANS_QUY_CHEO (config.php) — engine đọc thang PHẢI dùng hằng.
	$fpoint_trans_tiers = (isset($fpoint_configs['trans_value']) && is_array($fpoint_configs['trans_value'])) ? $fpoint_configs['trans_value'] : array();
	if(empty($fpoint_trans_tiers)){
		// Seed mặc định theo thang 2026 (chưa cấu hình lần nào)
		$fpoint_trans_tiers = array(
			array('from' => 0, 'to' => 5, 'exclusive_score' => 5, 'cross_score' => 2.5, 'note' => ''),
			array('from' => 5, 'to' => 10, 'exclusive_score' => 7.5, 'cross_score' => 3.5, 'note' => ''),
			array('from' => 10, 'to' => 20, 'exclusive_score' => 10, 'cross_score' => 5, 'note' => ''),
			array('from' => 20, 'to' => 35, 'exclusive_score' => 15, 'cross_score' => 7.5, 'note' => ''),
			array('from' => 35, 'to' => 50, 'exclusive_score' => 20, 'cross_score' => 12.5, 'note' => ''),
			array('from' => 50, 'to' => 0, 'exclusive_score' => 30, 'cross_score' => 20, 'note' => '')
		);
	}
	$assign_list['fpoint_trans_tiers'] = $fpoint_trans_tiers;
	if(isset($_POST['submit']) && $_POST['submit']='UpdateConfiguration'){
		$fpoint_configs = Input::post('fpoint_configs');
		// Merge với file hiện có trước khi ghi: form chỉ render key theo role/billing-type ĐANG hoạt động,
		// nếu ghi đè thẳng thì các key cũ (loại giao dịch/role đã ẩn) sẽ mất vĩnh viễn sau 1 lần Lưu.
		$_existing_configs = array();
		if(@file_exists($cachedFile)){
			$_dec = new Webmozart\Json\JsonDecoder();
			$_existing_configs = (array) $_dec->decodeFile($cachedFile);
		}
		$fpoint_configs = array_merge($_existing_configs, (array) $fpoint_configs);
		// Chuẩn hóa thang điểm theo giá trị GD: loại dòng trống điểm, ép số, sort theo mốc "từ" tăng dần
		$_tiers_in = (isset($fpoint_configs['trans_value']) && is_array($fpoint_configs['trans_value'])) ? $fpoint_configs['trans_value'] : array();
		$_tiers = array();
		foreach($_tiers_in as $_tr){
			$_dq = isset($_tr['exclusive_score']) ? trim((string) $_tr['exclusive_score']) : '';
			$_qc = isset($_tr['cross_score']) ? trim((string) $_tr['cross_score']) : '';
			if($_dq === '' && $_qc === ''){
				continue;
			}
			$_tiers[] = array(
				'from' => (float) (isset($_tr['from']) ? $_tr['from'] : 0),
				'to' => (float) (isset($_tr['to']) ? $_tr['to'] : 0),
				'exclusive_score' => (float) $_dq,
				'cross_score' => (float) $_qc,
				'note' => isset($_tr['note']) ? trim((string) $_tr['note']) : ''
			);
		}
		usort($_tiers, function($a, $b){
			if($a['from'] == $b['from']){
				return 0;
			}
			return ($a['from'] < $b['from']) ? -1 : 1;
		});
		$fpoint_configs['trans_value'] = $_tiers;
		$encoder = new Webmozart\Json\JsonEncoder();
		$encoder->encodeFile($fpoint_configs, $cachedFile);
		header('location:'.PCMS_URL.'?mod='.$mod.'&act=fpoint&message=updateSuccess');
		exit();
	}
}
function default_get_select_worksheets(){
	global $assign_list,$core,$dbconn,$mod,$act,$clsISO;
	$html = "<option>Lựa chọn sheet</option>";
	$spreadsheetId = Input::post("spreadsheetId");
	if(!empty($spreadsheetId)){
		if($clsISO->checkContainer($spreadsheetId,"docs.google.com","")){
			@preg_match('~/d/\K[^/]+(?=/)~', $spreadsheetId, $matches);
			// $clsISO->print_pre($matches); die();
			$spreadsheetId = $matches[0];
		}
		try {
			require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
			/** Init Client */
			$client = new Google_Client();
			$client->setClientId(GOOGLE_CLIENT_ID);
			$client->setClientSecret(GOOGLE_CLIENT_SECRET);
			$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
			$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
			$service = new Google_Service_Sheets($client);
			// the spreadsheet id can be found in the url https://docs.google.com/spreadsheets/d/143xVs9lPopFSF4eJQWloDYAndMor/edit
			// $spreadsheet = $service->spreadsheets->get($spreadsheetId);
			// Web: https://www.nidup.io/blog/manipulate-google-sheets-in-php-with-api
			$spreadsheet = $service->spreadsheets->get($spreadsheetId);
			$list_worksheets = $spreadsheet->sheets;
			// $clsISO->print_pre($list_worksheets); die();
			if(!empty($list_worksheets)){
				foreach($list_worksheets as $sheet){
					// $id = $sheet->properties['sheetId'];   
					$name = $sheet->properties['title']; 
					$html.= '<option value="'.$name.'">'.$name.'</option>';
				}
			}
		} catch(Exception $ex){
			$html = "<option>Lựa chọn sheet</option>";
		}
	}
	// Return
	echo json_encode(array(
		'html' => $html,
		'spreadsheetId' => $spreadsheetId
	)); die();
}
function default_open_setting_field(){
	global $assign_list,$core,$dbconn,$mod,$act,$clsISO;
	$clsStock = new Stock();
	$clsProperty = new Property();
	$html = '';
	$uid = Input::post('uid');
	$columns = Input::post('columns');
	$sheet_name = Input::post('sheet_name');
	$stock_type = _BLOCK_TYPE_HIGHLEVEL_SALE;
	$spreadsheetId = Input::post('spreadsheetId');
	if(!empty($spreadsheetId) && !empty($sheet_name)){
		require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';
		/** Init Client */
		$client = new Google_Client();
		$client->setClientId(GOOGLE_CLIENT_ID);
		$client->setClientSecret(GOOGLE_CLIENT_SECRET);
		$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);
		$client->setScopes(Google_Service_Sheets::SPREADSHEETS);
		$service = new Google_Service_Sheets($client);
		// Web: https://www.nidup.io/blog/manipulate-google-sheets-in-php-with-api
		// the spreadsheet id can be found in the url https://docs.google.com/spreadsheets/d/143xVs9lPopFSF4eJQWloDYAndMor/edit
		// $spreadsheet = $service->spreadsheets->get($spreadsheetId);
		// get all the rows of a sheet
		$range = $sheet_name; // here we use the name of the Sheet to get all the rows
		$response = $service->spreadsheets_values->get($spreadsheetId, $range);
		$tblData = $response->getValues();
		// $clsISO->print_pre($tblData); die();
		$highestColumn = 0;
		if(!empty($tblData)){
			foreach($tblData as $oData){
				if($highestColumn < count($oData)){
					$highestColumn = count($oData);
				}
			}
			$arrs_columns = !empty($columns) ? @explode('|', $columns) : array();
			$html = '<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header"> 
					<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
					<h3 class="modal-title"><strong>Import bảng hàng</strong></h3>
				</div>
				<form method="POST">
					<div class="modal-body modal-body-scrollable">
						<table class="table table-bordered table-striped">
							<thead><tr>';
								for($col=0; $col<$highestColumn; $col++){
									$field = "";
									if(!empty($arrs_columns)){
										foreach($arrs_columns as $val){
											$tmp = explode('-', $val);
											if($tmp[0]==$col){
												$field = $tmp[1];
												break;
											}
										}
									}
									$html .= '<th style="min-width:100px" width="'.(100/$highestColumn).'%">
										<select name="columns['.$col.']" class="form-control stock_import_field">
											<option value="">Lựa chọn</option>
											'.$clsStock->renderOptionColumnField($col,$stock_type,"s_field",$field).'
										</select>
									</th>';
								}
							$html .= '</tr>
							</thead>';
						$ii = 0;
						foreach($tblData as $key => $val){
							if($ii<=10){
								$html.='<tr>';
								for($col=0; $col<=$highestColumn; $col++){
									$html.= '<td>'.$val[$col].'</td>';
								}
								$html.= '</tr>';
							}
							++$ii;
						}
						$html.= '<tr>
							<td class="text-center" colspan="'.$highestColumn.'">
								Dữ liệu mẫu...
							</td>
						</tr>';
				$html.='</table>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-success" uid="'.$uid.'" onClick="$Core.property.get_setting_field(this, event)">
							<span>Thiết lập trường dữ liệu</span>
						</button>
					</div>
				</form>
			</div></div>';
		}
	} else {
		$html = '_invalid';
	}
	// Return
	echo $html; die();
}
function default_get_setting_field(){
	global $assign_list,$core,$dbconn,$mod,$act,$clsISO;
	$clsStock = new Stock();
	$clsProperty = new Property();
	###
	$columns = Input::post('columns');
	if(!empty($columns)){
		$ii = 0; $tmp = array();
		foreach($columns as $key => $val){
			if(!empty($val)){
				$tmp[] = sprintf('%s-%s', $ii, $val);
			}
			++$ii;
		}
		$html = @implode('|', $tmp);
	} else {
		$html = "_invalid";
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_open_permiss(){
	global $smarty,$core,$dbconn,$mod,$act,$clsISO;
	$clsPermiss = new Permiss();
	$clsProperty = new Property();
	#
	$for_id = Input::post('for_id', 0);
	$profile_type = Input::post('profile_type', "user.fh");
	$smarty->assign('for_id', $for_id);
	$smarty->assign('profile_type', $profile_type);
	#
	$field = "more_information";
	$oProperty = $clsProperty->getOne($for_id, $field);
	$more_information = $oProperty['more_information'];
	$more_information = !empty($more_information) 
		? json_decode(html_entity_decode($more_information), true) : array();
	$permiss_mod = isset($more_information['permiss_mod']) 
		? $more_information['permiss_mod'] : array();
	// $clsISO->print_pre($permiss_mod); die();
	$field = "{$clsPermiss->pkey},title,code";
	$list_permiss = $clsPermiss->getAll("`parent_id`=0 AND `is_active`=1 AND `profile_type`='{$profile_type}' order by `order_no` ASC", $field);
	// $clsISO->print_pre($list_permiss); die();
	if(!empty($list_permiss)){
		foreach($list_permiss as $key => $val){
			$parent_id = $val[$clsPermiss->pkey];
			$list_items = $clsPermiss->getAll("`parent_id`='{$parent_id}' AND `is_active`=1 AND `profile_type`='{$profile_type}' order by `order_no` ASC", $field);
			if(!empty($list_items)){
				foreach($list_items as $okey => $oval){
					if(in_array($oval['code'], @array_keys($permiss_mod))){
						$list_items[$okey]['checked'] = 1;
					} else {
						$list_items[$okey]['checked'] = 0;
					}
				}
			}
			$list_permiss[$key]['list_items'] = $list_items;
		}
	}
	$smarty->assign('list_permiss', $list_permiss);
	// Return
	$html = $core->build('_ajax.permiss.tpl');
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_pop_save_permiss(){
	global $smarty,$core,$dbconn,$mod,$act,$clsISO;
	$clsPermiss = new Permiss();
	$clsProperty = new Property();
	###
	$for_id = Input::post('for_id', 0);
	$profile_type = Input::post('profile_type', "user.fh");
	$permiss_mod = Input::post('permiss_mod', array());
	###
	$field = "more_information";
	$oProperty = $clsProperty->getOne($for_id, $field);
	$more_information = $oProperty['more_information'];
	$more_information = !empty($more_information) 
		? json_decode(html_entity_decode($more_information), true) : array();
	// $clsISO->print_pre($more_information); die();
	###
	$msg = "_error";
	$more_information['permiss_mod'] = $permiss_mod;
	if($clsProperty->updateOne($for_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))){
		$msg = "_success";
	}
	// Return
	echo $msg; die();
}
function default_open_ultilities(){
	global $smarty,$core,$dbconn,$mod,$act,$clsISO;
	$clsProperty = new Property();
	#
	$uid = $clsISO->getUniqid();
	$for_id = Input::post('for_id', 0);
	$_type = Input::post('_type', "_choose");
	#
	$more_information = $clsProperty->getOneField('more_information', $for_id);
	$more_information = $clsISO->to_array_json($more_information);
	$permiss_ultilities = $core->get_field($more_information, "permiss_ultilities", []);
	$field = "{$clsProperty->pkey},`title`,`more_information`";
	if($_type == "_sort") {		
		$list_ultilites = $clsProperty->getAll("`property_type`='_ULTILITIES' AND `{$clsProperty->pkey}` IN (".implode(',',$permiss_ultilities).") order by `order_no` DESC", $field);
		if(!empty($list_ultilites)){
			$order_no = count($list_ultilites);
			foreach($list_ultilites as $key => $val){
				$more_information = $val['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				if(array_search($val[$clsProperty->pkey], $permiss_ultilities) != -1) {
					$order = array_search($val[$clsProperty->pkey],$permiss_ultilities) + 1;
				}else{
					$order = $order_no;
					--$order_no;
				}
				$list_ultilites[$key]["order_no"] = $order;			
			}
		}
		$arr_sort = @array_column($list_ultilites, 'order_no');
		@array_multisort($arr_sort, SORT_ASC, $list_ultilites);
		$smarty->assign('permiss_ultilities', json_encode($permiss_ultilities));
	}else{
		$smarty->assign('permiss_ultilities', $permiss_ultilities);
		$list_ultilites = $clsProperty->getAll("`property_type`='_ULTILITIES' order by `order_no` DESC", $field);
		if(!empty($list_ultilites)){
			foreach($list_ultilites as $key => $val){
				$more_information = $val['more_information'];
				$more_information = $clsISO->to_array_json($more_information);
				$list_roles = $core->get_field($more_information, "role", []);
				$list_ultilites[$key]["role"] = implode(', ', $list_roles); 
			}
		}
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('for_id', $for_id);
	$smarty->assign('list_ultilites', $list_ultilites);
	// Return
	if($_type == "_sort") {
		$html = $core->build('_ajax.permiss_ultilities.tpl');
	}else{
		$html = $core->build('_ajax.open_ultilities.tpl');
	}
	echo json_encode(array(
		'html' => $html,
		'uid' => $uid,
	)); die();
}
function default_save_list_ultilities(){
	global $smarty,$core,$dbconn,$mod,$act,$clsISO;
	$clsProperty = new Property();
	#
	$msg = "_error";
	$for_id = Input::post('for_id', 0);
	$utility_id = Input::post('utility_id', []);
	$field = "{$clsProperty->pkey},`more_information`";
	$list_utilities = $clsProperty->getAll("`property_type`='_ULTILITIES' order by `order_no` DESC", $field);
	if(!empty($list_utilities) && !empty($for_id)){
		$role = $clsProperty->getRoleUtilities($for_id);
		foreach($list_utilities as $key => $val){
			$property_id = $val[$clsProperty->pkey];
			$more_utility = $val["more_information"];
			$more_utility = $clsISO->to_array_json($more_utility);
			$arr_role = $core->get_field($more_utility, "role", []);
			if($clsISO->checkItemInArray($property_id, $utility_id)) {
				if(!$clsISO->checkItemInArray($role, $arr_role)) {
					$arr_role[] = $role;
				}
			}else{
				$key = array_search($role, $arr_role);
				unset($arr_role[$key]);
				$arr_role = array_values($arr_role);
			}
			$more_utility["role"] = $arr_role;
			$clsProperty->updateOne($property_id, array(
				'more_information' => json_encode($more_utility, JSON_UNESCAPED_UNICODE)
			));			
		}
		$more_information = $clsProperty->getOneField("more_information", $for_id);
		$more_information = $clsISO->to_array_json($more_information);
		$permiss_ultilities = $more_information['permiss_ultilities'];
		$arr_permiss = array_intersect($permiss_ultilities, $utility_id);
		$permiss_ultilities = array_values($arr_permiss);
		foreach ($utility_id as $id) {
			if(!$clsISO->checkItemInArray($id, $permiss_ultilities)){
				$permiss_ultilities[] = $id;
			}
		}
		$more_information['permiss_ultilities'] = $permiss_ultilities;
		if($clsProperty->updateOne($for_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$msg = "_success";
		}
	}
	// Return
	echo json_encode(array(
		'msg' => $msg
	)); die();
}
function default_save_permiss_ultilities(){
	global $smarty,$core,$dbconn,$mod,$act,$clsISO;
	$clsProperty = new Property();
	#
	$for_id = Input::post('for_id', 0);
	$permiss_ultilities = Input::post('permiss_ultilities', array() );
	#
	$more_information = $clsProperty->getOneField('more_information', $for_id);
	$more_information = $clsISO->to_array_json($more_information);
	$permiss_ultilities_old = $core->get_field($more_information, "permiss_ultilities", []);
	//	$clsISO->print_pre($permiss_ultilities);die;
	$more_information['permiss_ultilities'] = $permiss_ultilities;
	if($clsProperty->updateOne($for_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))){
		$role = $clsProperty->getRoleUtilities($for_id);
		$lstUtilities = $clsProperty->getAll("`is_trash`='0' AND (`{$clsProperty->pkey}` IN (".implode(',',$permiss_ultilities_old).") || `{$clsProperty->pkey}` IN (".implode(',',$permiss_ultilities)."))");
		foreach ($lstUtilities as $key => $val) {
			$property_id = $val[$clsProperty->pkey];
			$more_utility = $val['more_information'];
			$more_utility = $clsISO->to_array_json($more_utility);
			$arr_role = $core->get_field($more_utility, "role", []);
			if(!$clsISO->checkItemInArray($role,$arr_role) && $clsISO->checkItemInArray($property_id,$permiss_ultilities)) {
				$arr_role[] = $role;
			}else if($clsISO->checkItemInArray($role,$arr_role) && !$clsISO->checkItemInArray($property_id,$permiss_ultilities) ) {
				$k = array_search($role,$arr_role);
				unset($arr_role[$k]);
				$arr_role = array_values($arr_role);
			}
			$more_utility["role"] = $arr_role;
			$clsProperty->updateOne($property_id, array(
				'more_information' => json_encode($more_utility, JSON_UNESCAPED_UNICODE)
			));
			unset($more_information);
		}
		$msg = "_success";
	}
	// Return
	echo json_encode(array(
		'html' => $html
	)); die();
}
function default_add_folder_price_sheets(){
	global $smarty,$core,$dbconn,$mod,$act,$clsISO;
	$clsPermiss = new Permiss();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$html = '<div class="form-group form-row group_price_sheets">
		<label class="col-md-2 text-right col-form-label required">Folder PTG</label>
		<div class="col-md-5">
			<input type="text" class="form-control" onClick="this.select();" name="folder_price_sheets['.$uid.'][title]" 
				value="" placeholder="Tên folder" maxlength="255">
		</div>
		<div class="col-md-4">
			<input type="text" class="form-control" onClick="this.select();" name="folder_price_sheets['.$uid.'][link]"
				value="" placeholder="Link folder" maxlength="255">
		</div>
		<div class="col-md-1 text-center">
			<button class="btn btn-default" type="button" title="Xóa Folder PTG" onClick="$Core.property.delete_folder_price_sheets(this,event)"><i class="fa fa-trash"></i></button>
		</div>
	</div>';
	// Return
	echo $html; die();
}
function default_add_folder_interior_ns(){
	global $smarty,$core,$dbconn,$mod,$act,$clsISO;
	$clsPermiss = new Permiss();
	$clsProperty = new Property();
	###
	$uid = $clsISO->getUniqid();
	$property_id = (int) Input::post('property_id', 0); 
	$html = '<div class="interior_ns_pa_'.$property_id.'">
		<div class="form-group form-row group_price_sheets">
			<label class="col-md-2 text-right col-form-label required">Nội thất mẫu PA</label>
			<div class="col-md-5">
				<input type="text" class="form-control" onClick="this.select();" name="folder_interior_ns['.$uid.'][title]" 
					value="" placeholder="Tên folder" maxlength="255">
			</div>
			<div class="col-md-5">
				<input type="text" class="form-control" onClick="this.select();" name="folder_interior_ns['.$uid.'][link]" 
					value="" placeholder="Link folder" maxlength="255">
			</div>
		</div>
		<div class="form-group form-row">
			<label class="col-md-2 text-right col-form-label required"></label>
			<div class="col-md-10">
				<input type="text" class="form-control required" placeholder="https://www.youtube.com/watch?v=xxx" 
				name="folder_interior_ns['.$uid.'][video]" onClick="this.select();" value="">
			</div>
		</div>
	</div>';
	// Return
	echo $html; die();
}
function default_add_group_zalo(){
	global $smarty,$core,$dbconn,$mod,$act,$clsISO;
	$clsPermiss = new Permiss();
	$clsProperty = new Property();
	$clsProject = new Project();
	###
	$uid = $clsISO->getUniqid();
	$html = '<div class="form-group form-row group_zalo">
				<label class="col-md-3 text-right col-form-label required">Nhóm zalo chúc mừng</label>
				<div class="col-md-3">
					<select class="form-control iso-select2" data-width="100%" name="group_zalo['.$uid.'][project_id]" onChange="$Core.property.select_block(this,event)" toId="block_'.$uid.'">
						<option value="0">Chọn dự án</option>
						'.$clsProject->getSelectOptions(0).'
					</select>
				</div>
				<div class="col-md-3">
					<select class="form-control iso-select2" data-width="100%" name="group_zalo['.$uid.'][block_id]" id="block_'.$uid.'">
						'.$clsProperty->getSelectByPropertyOrigin("_BLOCK",0,0,"Phân khu/Block").'
					</select>
				</div>
				<div class="col-md-3">
					<input type="text" class="form-control" name="group_zalo['.$uid.'][group_zalo_id]" value="" placeholder="Group Zalo ID" maxlength="255">
				</div>
			</div>';
	// Return
	echo $html; die();
}
function default_set_trashed(){
	global $smarty,$core,$dbconn,$mod,$act,$clsISO;
	$clsPermiss = new Permiss();
	$clsProperty = new Property();
	###
	$property_id = (int) Input::post('property_id', 0);
	$is_trash = (int) Input::post('is_trash', 0);
	###
	$msg = "_error";
	if($clsProperty->updateOne($property_id, array(
		'is_trash' => $is_trash
	))){
		$msg = "_success";
	}
	// Return
	echo $msg; die();
}
function default_set_show_calendar(){
	global $smarty,$core,$dbconn,$mod,$act,$clsISO;
	$clsPermiss = new Permiss();
	$clsProperty = new Property();
	###
	$property_id = (int) Input::post('property_id', 0);
	$more_information = $clsProperty->getOneField('more_information', $property_id);
	$more_information = $clsISO->to_array_json($more_information);
	// $clsISO->print_pre($more_information); die();
	$is_calendar = (int) Input::post('is_calendar', 0);
	###
	$msg = "_error";
	$more_information['is_calendar'] = $is_calendar;
	if($clsProperty->updateOne($property_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))){
		$msg = "_success";
	}
	// Return
	echo $msg; die();
}
function default_set_status_moc_point(){
	global $smarty,$core,$dbconn,$mod,$act,$clsISO;
	$clsPermiss = new Permiss();
	$clsProperty = new Property();
	###
	$property_id = (int) Input::post('property_id', 0);
	$more_information = $clsProperty->getOneField('more_information', $property_id);
	$more_information = $clsISO->to_array_json($more_information);
	// $clsISO->print_pre($more_information); die();
	$status_moc_point = (int) Input::post('status_moc_point', 0);
	###
	$msg = "_error";
	$more_information['status_moc_point'] = $status_moc_point;
	if($clsProperty->updateOne($property_id, array(
		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
	))){
		$msg = "_success";
	}
	// Return
	echo $msg; die();
}
function default_account_nanonet(){
	global $assign_list,$core,$clsConfiguration,$mod,$act,$dbconn,$clsISO;	#
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	$cachedFile = DIR_CACHE_JSON.'/account/account.json';
	if(@file_exists($cachedFile)){
		$decoder = new Webmozart\Json\JsonDecoder();
		$lst_account = $decoder->decodeFile($cachedFile);
		$assign_list["lst_account"] = $lst_account;
	}
	#
	if(isset($_POST['submit']) && $_POST['submit'] == "Update") {
		$data = Input::post("data", array());
		$encoder = new Webmozart\Json\JsonEncoder();
		$encoder->encodeFile($data, $cachedFile);
		header('location:'.PCMS_URL.'?mod='.$mod.'&act='.$act.'&message=updateSuccess');
	}
}
function default_open_account(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;	
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	$cachedFile = DIR_CACHE_JSON.'/account/account.json';
	if(@file_exists($cachedFile)){
		$decoder = new Webmozart\Json\JsonDecoder();
		$lst_account = $decoder->decodeFile($cachedFile);
		$assign_list["lst_account"] = $lst_account;
	}
	#
	$key = Input::post('key', "");
	$action = Input::post('action', "_add");
	$action = "_edit";
	$titlePage = "Thêm mới";
	$list_blocks = $list_buildings = $more_information = array();
	if($key != "" && !empty($lst_account[$key])){
		$action = '_edit';
		$titlePage= 'Chỉnh sửa';
		$oneItem = $lst_account[$key];
		$smarty->assign('oneItem', $oneItem);
	}
	// Return
	$smarty->assign('key', $key);
	$smarty->assign('action', $action);
	$smarty->assign('titlePage', $titlePage);
	$html = $core->build('_ajax.open_account.tpl');
	echo $html; die();
}
function default_save_account(){
	global $assign_list,$core,$dbconn,$mod,$act,$clsISO;
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	$cachedFile = DIR_CACHE_JSON.'/account/account.json';
	$lst_account = array();
	if(@file_exists($cachedFile)){
		$decoder = new Webmozart\Json\JsonDecoder();
		$lst_account = $decoder->decodeFile($cachedFile);
	}
	$key = Input::post("key","");
	$api_key = Input::post("api_key","");
	$model_id = Input::post("model_id","");
	$number = (int)Input::post("number",0);
	$date = Input::post("date","");
	$date = ($date != "") ? strtotime(str_replace("/","-",$date)) : strtotime(date("d-m-Y"));
	if(!empty($lst_account) && $api_key != "") {
		foreach ($lst_account as $k => $value) {
			if($api_key == $value['api_key'] && $key != $k) {
				$data_res = [
					"result"	=>	false,
					"msg"		=>	"API key đã tồn tại"
				];
				echo json_encode($data_res);die;
			}
		}
	}
	$upd_date = strtotime("+1 months",$date);
	if($key != "") {
		#activity log		
		$clsActivityLog = new ActivityLog();
		$log = $clsActivityLog->addActivityLog("Cache","update",['field' =>"account_nanonet"]);
	}else{
		$key = $clsISO->getUniqid();
		#activity log		
		$clsActivityLog = new ActivityLog();
		$log = $clsActivityLog->addActivityLog("Cache","insert",['field' =>"account_nanonet"]);
	}
	$modelId = [
		"key"			=>	$model_id,
		"number"		=>	$number,
		"date"			=>	$date,
		"upd_date"		=>	$upd_date,
	];
	$lst_account[$key] = [
		"api_key"	=>	$api_key,
		"model_id"	=>	$modelId,
	];
	$encoder = new Webmozart\Json\JsonEncoder();
	$encoder->encodeFile($lst_account, $cachedFile);
	echo json_encode(array(
		"result"	=>	true,
		"msg"	=>	"_success"
	));
}
function default_delete_account(){
	global $assign_list,$core,$dbconn,$mod,$act,$clsISO;
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	$cachedFile = DIR_CACHE_JSON.'/account/account.json';
	$lst_account = array();
	$data = ["msg" => "_error"];
	if(@file_exists($cachedFile)){
		$decoder = new Webmozart\Json\JsonDecoder();
		$lst_account = $decoder->decodeFile($cachedFile);
		$key = Input::post("key","");
		if($key != "" && !empty($lst_account[$key])) {
			unset($lst_account[$key]);
			$encoder = new Webmozart\Json\JsonEncoder();
			$encoder->encodeFile($lst_account, $cachedFile);
			$data = ["msg" => "_success"];	
			#activity log		
			$clsActivityLog = new ActivityLog();
			$log = $clsActivityLog->addActivityLog("Cache","delete",['field' =>"account_nanonet"]);
		}
	}
	echo json_encode($data);	
}
function default_takeleave(){
	global $assign_list,$core,$dbconn,$mod,$act,$clsISO,$clsConfiguration;
	$clsProfile = new Profile();
	$assign_list['clsProfile'] = $clsProfile;
	###
	$takeleave_configs = $clsConfiguration->getValue('takeleave_configs');
	$takeleave_configs = $clsISO->to_array_json($takeleave_configs);
	$assign_list['takeleave_configs'] = $takeleave_configs;
	###
	$list_approved_by = array(
		'director' => array(
			'title' => 'Giám đốc',
			'has_select_staff' => 1
		), 'hrad' => array(
			'title' => 'Hành chính nhân sự',
			'has_select_staff' => 1,
		), 'director_of_dep' => array(
			'title' => 'Giám đốc bộ phận',
			'has_select_staff' => 0
		), 'head_of_dep' => array(
			'title' => 'Trưởng phòng',
			'has_select_staff' => 0
		), 'curator' => array(
			'title' => 'Người quản lý',
			'has_select_staff' => 0
		)
	);
	$assign_list['list_approved_by'] = $list_approved_by;
	$field = "{$clsProfile->pkey},full_name,first_name,last_name";
	$list_staffs = $clsProfile->getAll("is_trash=0 and is_active='1' and status_id='"._STATUS_STAFF_ON_ID."'", $field);
	$assign_list['list_staffs'] = $list_staffs;
	###
	if(isset($_POST['submit']) && $_POST['submit'] == 'Update'){
		$takeleave_configs = $_POST['takeleave_configs'];
		$is_fulltime_only = (int) Input::post('is_fulltime_only', 0);
		$is_leave_carryover = (int) Input::post('is_leave_carryover', 0);
		$takeleave_configs['is_fulltime_only'] = $is_fulltime_only;
		$takeleave_configs['is_leave_carryover'] = $is_leave_carryover;
		// $clsISO->print_pre($takeleave_configs); die();
		$clsConfiguration->updateValue('takeleave_configs', json_encode($takeleave_configs, JSON_UNESCAPED_UNICODE));
		header('Location:'.PCMS_URL . '/index.php?mod='.$mod.'&act='.$act.'&message=updateSuccess');
		exit();
	}
}
function default_agency(){
	global $assign_list,$core,$dbconn,$mod,$act,$clsISO,$clsConfiguration;
	$clsProfile = new Profile();
	$assign_list['clsProfile'] = $clsProfile;
	$dev= Input::get("dev",""); $assign_list['dev'] = $dev;
	$agency_hidden_stock_FH = $clsConfiguration->getValue('agency_hidden_stock_FH');
	$agency_hidden_stock_FH = $clsISO->to_array_json($agency_hidden_stock_FH);
	$agency_hidden_stock_MOC = $clsConfiguration->getValue('agency_hidden_stock_MOC');
	$agency_hidden_stock_MOC = $clsISO->to_array_json($agency_hidden_stock_MOC);
	$assign_list['agency_hidden_stock_FH'] = $agency_hidden_stock_FH;
	$assign_list['agency_hidden_stock_MOC'] = $agency_hidden_stock_MOC;
	###
}
function default_load_list_agency(){
	global $smarty,$assign_list,$user_id,$core,$clsISO,$_LANG_ID,$clsConfiguration;
	$clsProperty = new Property();
	$smarty->assign('clsProperty',$clsProperty);
	$agency_hidden_stock_FH = $clsConfiguration->getValue('agency_hidden_stock_FH');
	$agency_hidden_stock_FH = $clsISO->to_array_json($agency_hidden_stock_FH);
	$agency_hidden_stock_MOC = $clsConfiguration->getValue('agency_hidden_stock_MOC');
	$agency_hidden_stock_MOC = $clsISO->to_array_json($agency_hidden_stock_MOC);
	$property_type = Input::post('property_type');
	$smarty->assign('property_type',$property_type);
	$smarty->assign('agency_hidden_stock_FH',$agency_hidden_stock_FH);
	$smarty->assign('agency_hidden_stock_MOC',$agency_hidden_stock_MOC);
	$action = '_list';
	$smarty->assign('action',$action);
	$cond = "is_locked=0 and parent_id='0' and property_type='{$property_type}'";
	$lstProperty = $clsProperty->getAll($cond." order by order_no ASC");
	if(!empty($lstProperty)){
		foreach($lstProperty as $key=> $val){
			$more_information = $val['more_information'];
			$more_information = !empty($more_information) ? json_decode(html_entity_decode($more_information), true) : array();
			$lstProperty[$key]['more_information'] = $more_information;
		}
	}
	$smarty->assign('lstProperty',$lstProperty);
	// Output
	$smarty->assign('core',$core);
	$html = $core->build('load_agency.tpl');
	echo $html; die();
}
function default_agency_hidden_stock(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$clsProperty = new Property();
	$assign_list['clsProperty'] = $clsProperty;
	$agency_hidden_stock_FH = $clsConfiguration->getValue('agency_hidden_stock_FH');
	$agency_hidden_stock_FH = $clsISO->to_array_json($agency_hidden_stock_FH);
	$agency_hidden_stock_MOC = $clsConfiguration->getValue('agency_hidden_stock_MOC');
	$agency_hidden_stock_MOC = $clsISO->to_array_json($agency_hidden_stock_MOC);
//	 $clsISO->print_pre($agency_hidden_stock); die();
	$assign_list['agency_hidden_stock_FH'] = $agency_hidden_stock_FH;
	$assign_list['agency_hidden_stock_MOC'] = $agency_hidden_stock_MOC;
}
function default_open_hidden_stock(){
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$clsConfiguration;	
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	$clsProperty = new Property();
	$clsProject = new Project();
	$agency_hidden_stock_id = Input::post('agency_hidden_stock_id', "");
	$type = Input::post('type', "_FH");
	$agency_hidden_stock = $clsConfiguration->getValue('agency_hidden_stock'.$type);
	$agency_hidden_stock = $clsISO->to_array_json($agency_hidden_stock);
	$oneItem = !empty($agency_hidden_stock[$agency_hidden_stock_id]) ? $agency_hidden_stock[$agency_hidden_stock_id] : array();
	$action = "_edit";
	$titlePage = "Thêm mới";
	$list_blocks = $list_buildings = $more_information = array();
	if(!empty($agency_hidden_stock[$agency_hidden_stock_id])) {
		$action = '_edit';
		$titlePage= 'Chỉnh sửa';
	}
	$project_id = !empty($oneItem['project_id']) ? $oneItem['project_id'] : _PROJECT_DEF_ID;
	$uid = $clsISO->getUniqid();
	// Return
	$smarty->assign('oneItem', $oneItem);
	$smarty->assign('uid', $uid);
	$smarty->assign('titlePage', $titlePage);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsProject', $clsProject);
	$smarty->assign('project_id', $project_id);
	$smarty->assign('agency_hidden_stock_id', $agency_hidden_stock_id);
	$html = $core->build('_ajax.open_hidden_stock.tpl');
	echo $html; die();
}
function default_save_hidden_stock(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$clsConfiguration;	
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	$uid = $clsISO->getUniqid();
	$agency_hidden_stock_id = Input::post('agency_hidden_stock_id', "");
	$agency_hidden_stock_id = !empty($agency_hidden_stock_id) ? $agency_hidden_stock_id : $uid;
	$title = Input::post('title', "");
	$code = Input::post('code', "");
	$project_id = (int)Input::post('project_id', 0);
	$block_id = (int)Input::post('block_id', 0);
	$site = Input::post('site', "_FH");
	$type = Input::post('site', "_FH");
	$is_vin = (int)Input::post('is_vin', 0);
	$action = Input::post('action', "save");
	$res = ["result"	=>	false];
	if($action == "save") {
		$agency_hidden_stock = $clsConfiguration->getValue('agency_hidden_stock'.$site);
		$agency_hidden_stock = $clsISO->to_array_json($agency_hidden_stock);
		$agency_hidden_stock[$agency_hidden_stock_id] = [
			"title"	=>	$title,
			"code"	=>	$code,
			"project_id"	=>	$project_id,
			"block_id"	=>	$block_id,
			"site"	=>	$site,
			"is_vin"	=>	$is_vin 
		];
	}elseif($action == "delete"){
		$agency_hidden_stock = $clsConfiguration->getValue('agency_hidden_stock'.$site);
		$agency_hidden_stock = $clsISO->to_array_json($agency_hidden_stock);
		unset($agency_hidden_stock[$agency_hidden_stock_id]);
	}
	if($clsConfiguration->updateValue("agency_hidden_stock".$site, json_encode($agency_hidden_stock, JSON_UNESCAPED_UNICODE))){
		$res = ["result"	=>	true];
	}
	// Return
	echo json_encode($res); die();
}
function default_field_data_center(){

	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$clsProperty = new Property();
	$assign_list['clsProperty'] = $clsProperty;
	$field_data_center = $clsConfiguration->getValue('field_data_center');
	$field_data_center = $clsISO->to_array_json($field_data_center);
//	 $clsISO->print_pre($agency_field_data_center); die();
	$assign_list['field_data_center'] = $field_data_center;
}
function default_open_field_data_center(){
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$clsConfiguration;	
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	$clsProperty = new Property();
	$clsProject = new Project();
	$field_data_center_id = Input::post('field_data_center_id', "");
	$field_data_center = $clsConfiguration->getValue('field_data_center');
	$field_data_center = $clsISO->to_array_json($field_data_center);
	$oneItem = !empty($field_data_center[$field_data_center_id]) ? $field_data_center[$field_data_center_id] : array();
	$action = "_edit";
	$titlePage = "Thêm mới";
	$list_blocks = $list_buildings = $more_information = array();
	if(!empty($field_data_center[$field_data_center_id])) {
		$action = '_edit';
		$titlePage= 'Chỉnh sửa';
	}
	$project_id = !empty($oneItem['project_id']) ? $oneItem['project_id'] : _PROJECT_DEF_ID;
	$uid = $clsISO->getUniqid();
	// Return
	$smarty->assign('oneItem', $oneItem);
	$smarty->assign('uid', $uid);
	$smarty->assign('titlePage', $titlePage);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsProject', $clsProject);
	$smarty->assign('project_id', $project_id);
	$smarty->assign('field_data_center_id', $field_data_center_id);
	$html = $core->build('_ajax.open_field_data_center.tpl');
	echo $html; die();
}
function default_save_field_data_center(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$clsConfiguration;	
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	$uid = $clsISO->getUniqid();
	$field_data_center_id = Input::post('field_data_center_id', "");
	$field_data_center_id = !empty($field_data_center_id) ? $field_data_center_id : $uid;
	$title = Input::post('title', "");
	$code = Input::post('code', "");
	$is_default = (int)Input::post('is_default', 0);
	$action = Input::post('action', "save");
	$res = ["result"	=>	false];
	if($action == "save") {
		$field_data_center = $clsConfiguration->getValue('field_data_center');
		$field_data_center = $clsISO->to_array_json($field_data_center);
		$field_data_center[$field_data_center_id] = [
			"title"	=>	$title,
			"code"	=>	$code,
			"is_default"	=>	$is_default,
		];
	}elseif($action == "delete"){
		$field_data_center = $clsConfiguration->getValue('field_data_center');
		$field_data_center = $clsISO->to_array_json($field_data_center);
		unset($field_data_center[$field_data_center_id]);
	}
	if($clsConfiguration->updateValue("field_data_center", json_encode($field_data_center, JSON_UNESCAPED_UNICODE))){
		$res = ["result"	=>	true];
	}
	// Return
	echo json_encode($res); die();
}
function default_org_chart(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$assign_list;	
	$clsProfile = new Profile(); $smarty->assign("clsProfile",$clsProfile);
	$clsProperty = new Property(); $smarty->assign("clsProperty",$clsProperty);
	$lstProfile = $clsProfile->getAll("`is_trash`='0' AND`status_id` <> '"._STATUS_STAFF_OFF_ID."'");
	$smarty->assign("lstProfile",$lstProfile);
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	$cachedFile = DIR_CACHE_JSON.'/org/org_chart.json';
	$arr_node_level = [];
	if(@file_exists($cachedFile)){
		$decoder = new Webmozart\Json\JsonDecoder();
		$data_node = $decoder->decodeFile($cachedFile);
		$arr_data_node = $clsISO->to_array_json($data_node);
		$lst_node = !empty($arr_data_node['nodes']) ? $arr_data_node['nodes'] : array();
		$lst_point = !empty($arr_data_node['points']) ? $arr_data_node['points'] : array();
		if(!empty($lst_node)) {
			foreach ($lst_node as $key => $val) {
				if(!empty($val['staff_id'])) {
					if(!isset($arr_cache_profile[$val['staff_id']])) {
						$arr_cache_profile[$val['staff_id']] = $clsProfile->getOne($val['staff_id']);
					}	
					$lst_node[$key]['oneStaff'] = $arr_cache_profile[$val['staff_id']];
				}
				if(!isset($arr_cache_property[$val['role_id']])) {
					$arr_cache_property[$val['role_id']] = $clsProperty->getOne($val['role_id']);
				}	
				$lst_node[$key]['oneRole'] = $arr_cache_property[$val['role_id']];
				$lst_node[$key]['arr_parent'] = !empty($val['commonParentIds']) ? explode(",",$val['commonParentIds']) : array();
				$arr_node_level[$val["level"]][] = $lst_node[$key];
			}
		}
		$assign_list["lst_node"] = $lst_node;
		$assign_list["lst_point"] = $lst_point;
	}
	$assign_list["arr_node_level"] = $arr_node_level;
//	$clsISO->print_pre($lst_point);die;
	#
}
function default_open_org(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;	
	$clsProfile = new Profile(); $smarty->assign("clsProfile",$clsProfile);
	$clsProperty = new Property(); $smarty->assign("clsProperty",$clsProperty);
	$lstProfile = $clsProfile->getAll("`is_trash`='0' AND`status_id` <> '"._STATUS_STAFF_OFF_ID."'");
	$smarty->assign("lstProfile",$lstProfile);
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	$cachedFile = DIR_CACHE_JSON.'/org/org_chart.json';
	if(@file_exists($cachedFile)){
		$decoder = new Webmozart\Json\JsonDecoder();
		$lst_node = $decoder->decodeFile($cachedFile);
		$lst_node = $clsISO->to_array_json($lst_node);
		$assign_list["lst_node"] = $lst_node;
	}
	#
	$level = (int)Input::post('level', "0");
	$parent_id = Input::post('parent_id', "");
	$common_parent_id = Input::post('common_parent_id', "");
	$id = Input::post('id', "");
	$id = !empty($id) ? $id : "node_".time();
	$action = "_edit";
	$titlePage = "Thêm mới";
	$oneItem = array();
	if (!empty($lst_node["nodes"])) {
		foreach ($lst_node["nodes"] as $key => $val) {
			if($val['id'] == $id) {
				$oneItem = $val;
				break;
			}
		}
	}
	if($key != "" && !empty($lst_account[$key])){
		$action = '_edit';
		$titlePage= 'Chỉnh sửa';
		$oneItem = $lst_account[$key];
		$smarty->assign('oneItem', $oneItem);
	}
//	$clsISO->print_pre($oneItem);
	// Return
	$smarty->assign('id', $id);
	$smarty->assign('level', $level);
	$smarty->assign('parent_id', $parent_id);
	$smarty->assign('common_parent_id', $common_parent_id);
	$smarty->assign('titlePage', $titlePage);
	$smarty->assign('oneItem', $oneItem);
	$html = $core->build('_ajax.open_org.tpl');
	echo $html; die();
}
function default_save_node(){
	global $assign_list,$core,$dbconn,$mod,$act,$clsISO;
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	$cachedFile = DIR_CACHE_JSON.'/org/org_chart.json';
	$data_node = Input::post("data_node",array());
	$encoder = new Webmozart\Json\JsonEncoder();
	$encoder->encodeFile($data_node, $cachedFile);
	echo json_encode(array(
		"result"	=>	true,
		"msg"	=>	"_success"
	));
}
function default_render_node(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$clsProperty = new Property();
	$clsProfile = new Profile();
	$role_id = (int)Input::post("role_id",0);
	$staff_id = (int)Input::post("staff_id",0);
	$text_name = Input::post("text_name","");
	$level = (int)Input::post("level",0);
	$id = Input::post("id","");
	$parent_id = Input::post("parent_id","");
	$common_parent_id = Input::post("common_parent_id","");
	$top = Input::post("top","");
	$left = Input::post("left","");
	$html = "";
	$oneRole = $clsProperty->getOne($role_id);
	$oneStaff = $clsProfile->getOne($staff_id);
//	$clsISO->print_pre($_POST);die;
	$style = " style='";
	if(!empty($top) && !empty($left)) {
		$style .= "top:".$top."%;left:".$left."%";
	}
	//$text = !empty($text_name) ? ($oneRole["title"].'-'.$text_name) : $oneRole["title"];
	$text = !empty($text_name) ? $text_name : $oneRole["title"];
	$style .= "'";
	if(!empty($oneRole) && !empty($oneStaff)) {
		$html = '<div id="'.$id.'" class="node" data-id="'.$id.'" data-level="'.$level.'" data-parent-id="'.$parent_id.'" data-common-parent-ids="'.$common_parent_id.'" data-role_id="'.$role_id.'" data-staff_id="'.$staff_id.'" data-text_name="'.$text_name.'" '.$style.'>
			<div class="img_node"><img class="avatar m-0" src="'.$clsProfile->getAvatar($staff_id,$oneStaff).'" onerror="this.src=\''.URL_IMAGES.'/no-avatar.svg\'"></div>
			<div class="box_content">
				<h3 class="txt_name m-0 fs-16 fw-bold">'.$clsProfile->getFullname($staff_id,$oneStaff).'</h3>
				<span class="txt_role fs-11">'.$text.'</span>
			</div>
		</div>';
	}else if(!empty($oneRole)) {
		$html = '<div id="'.$id.'" class="node" data-id="'.$id.'" data-level="'.$level.'" data-parent-id="'.$parent_id.'" data-common-parent-ids="'.$common_parent_id.'" data-role_id="'.$role_id.'" data-staff_id="'.$staff_id.'" data-text_name="'.$text_name.'" '.$style.'>
			<div class="img_node">
				<img class="avatar m-0" src="'.URL_IMAGES.'/no-avatar.svg" onerror="this.src=\''.URL_IMAGES.'/no-avatar.svg\'"></div>
			<div class="box_content">
				<span class="txt_role fs-11">'.$text.'</span>
			</div>
		</div>';
	}
	echo json_encode(array(
		"id"	=>	$id,
		"html"	=>	$html,
		"parent_id"	=>	$parent_id,
	));die;
}
//========crawl drive thấp tầng==============
function default_crawl_lowfloor(){
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$clsProperty = new Property();
	$assign_list['clsProperty'] = $clsProperty;
	$lstAgency = $clsProperty->getAll("`is_trash`=0 AND `is_locked`=0 AND `property_id`<>'"._AGENCY_FH_ID."' AND `property_type`='_AGENCY' order by `order_no` ASC");
//	var_dump($lstAgency);die;
	foreach ($lstAgency as $key => $val) {
		$more_information = $clsISO->to_array_json($val['more_information']);
		$crawl_lowfloor = !empty($more_information['crawl_lowfloor']) ? $more_information['crawl_lowfloor'] : array();
		$lstAgency[$key]['more_information'] = $more_information;
		$lstAgency[$key]['crawl_lowfloor'] = $crawl_lowfloor;
	}
	$assign_list["lstAgency"] = $lstAgency;
	$lst_project = [
		_PROJECT_VHOP2_ID	=>	"VHOP2",
		_PROJECT_VHOP3_ID	=>	"VHOP3",
		_PROJECT_VHGG_ID	=>	"Cổ Loa",
		_PROJECT_VWC_ID	=>	"Đan Phượng",
	];
	$assign_list["lst_project"] = $lst_project;
}
function default_open_agency_crawl(){
	global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	$clsProperty = new Property();
	$smarty->assign('clsProperty',$clsProperty);
	###
	$uid = $clsISO->getUniqid();
	$agency_id = Input::post('agency_id', 0);
	$stock_type = (int)Input::post('stock_type', _BLOCK_TYPE_HIGHLEVEL_SALE);
	$oneItem = $clsProperty->getOne($agency_id);
	$more_information = $clsISO->to_array_json($oneItem['more_information']);
	if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) {		
		$block_crawl = !empty($more_information['block_crawl']) ? $more_information['block_crawl'] : array();
		$lst_block = [
			_PROJECT_BLOCK_MTS_ID	=>	"MTS",
			_PROJECT_BLOCK_MLS_ID	=>	"MLS",
			_PROJECT_BLOCK_MGA_ID	=>	"MGA",
			_PROJECT_BLOCK_LSB_ID	=>	"LSB",
		];
		$smarty->assign('lst_block',$lst_block);
		$smarty->assign('block_crawl',$block_crawl);
	}else if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE) {		
		$crawl_lowfloor = !empty($more_information['crawl_lowfloor']) ? $more_information['crawl_lowfloor'] : array();
//		$clsISO->print_pre($crawl_lowfloor);die;
		$lst_project = [
			_PROJECT_VHOP2_ID	=>	"VHOP2",
			_PROJECT_VHOP3_ID	=>	"VHOP3",
			_PROJECT_VHGG_ID	=>	"Cổ Loa",
			_PROJECT_VWC_ID	=>	"Đan Phượng",
		];
		$smarty->assign('lst_project',$lst_project);
		$smarty->assign('crawl_lowfloor',$crawl_lowfloor);
	}
	###	
	// Output
	$smarty->assign('agency_id',$agency_id);
	$smarty->assign('stock_type',$stock_type);
	$smarty->assign('uid',$uid);
	$smarty->assign('oneItem',$oneItem);
	$html = $core->build('_ajax.open_agency.tpl');
	$callback  = '';
	echo json_encode(array(
		'html' => $html,
		'uid' => $uid,
	)); die();
}
function default_save_agency_crawl(){
	global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	$clsProperty = new Property();
	$smarty->assign('clsProperty',$clsProperty);
	###
	$agency_id = (int)Input::post("agency_id",0);
	$stock_type = (int)Input::post("stock_type",_BLOCK_TYPE_HIGHLEVEL_SALE);
	$block_crawl = Input::post("block_crawl",array());
	$crawl_lowfloor = Input::post("crawl_lowfloor",array());
	$res = ["result"	=>	false,"msg"	=>	"error"];
	if(!empty($agency_id)) {
		$oneItem = $clsProperty->getOne($agency_id);
		$more_information = $clsISO->to_array_json($oneItem['more_information']);
		if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) {
			$more_information['block_crawl'] = $block_crawl;
		}else if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE) {
			foreach ($crawl_lowfloor as $key => $val) {
				if(empty($val['is_color_dq'])) {
					unset($crawl_lowfloor[$key]['color_dq']);
				}
				if(empty($val['is_color_break'])) {
					unset($crawl_lowfloor[$key]['color_break']);
				}
			}
//			var_dump($crawl_lowfloor);die;
			$more_information['crawl_lowfloor'] = $crawl_lowfloor;
		}
		if($clsProperty->updateOne($agency_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$res = ["result"	=>	true,"msg"	=>	"_success"];
		}
	}
	echo json_encode($res); die();
}
function default_setStatusCrawl(){
	global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	$clsProperty = new Property();
	$smarty->assign('clsProperty',$clsProperty);
	###
	$agency_id = (int)Input::post("agency_id",0);
	$block_id = (int)Input::post("block_id",0);
	$project_id = (int)Input::post("project_id",0);
	$is_crawl = (int)Input::post("is_crawl",0);
	$stock_type = (int)Input::post("stock_type",_BLOCK_TYPE_HIGHLEVEL_SALE);
	$res = ["result"	=>	false,"msg"	=>	"error"];
	if(!empty($agency_id) && ((!empty($block_id) && $stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) || (!empty($project_id) && $stock_type == _BLOCK_TYPE_LOWFLOOR_SALE))) {
		$oneItem = $clsProperty->getOne($agency_id);
		$more_information = $clsISO->to_array_json($oneItem['more_information']);
		if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE) {
			$block_crawl = !empty($more_information['block_crawl']) ? $more_information['block_crawl'] : array();
			$block_crawl[$block_id]["is_crawl"] = $is_crawl;
			$more_information['block_crawl'] = $block_crawl;
		}else if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE) {
			$crawl_lowfloor = !empty($more_information['crawl_lowfloor']) ? $more_information['crawl_lowfloor'] : array();
			$crawl_lowfloor[$project_id]["is_crawl"] = $is_crawl;
			$more_information['crawl_lowfloor'] = $crawl_lowfloor;
		}
		if($clsProperty->updateOne($agency_id, array(
			'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
		))){
			$res = ["result"	=>	true,"msg"	=>	"_success"];
		}
	}
	echo json_encode($res); die();
}
function default_ajCrawlLowfloor(){
	ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
	ini_set('memory_limit', '5048M');
	global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	require_once (DIR_INCLUDES.'/json_master/autoload.php');				
	require_once (DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
	$clsCrawlLowfloor = new CrawlLowFloor();
	$clsProperty = new Property();
	$clsStock = new StockCrawl();
	$clsStockLog = new StockLog();
	$agency_id = (int)Input::post("agency_id",0);
	$oneAgency = $clsProperty->getByCond("`property_type`='_AGENCY' AND `property_id`='{$agency_id}' AND JSON_SEARCH(more_information, 'one', '1', NULL, '$.crawl_lowfloor.*.is_crawl') IS NOT NULL");
	if(!empty($oneAgency)) {
		$more_information = !empty($oneAgency['more_information']) ? $clsISO->to_array_json($oneAgency['more_information']) : array();
		$crawl_lowfloor = !empty($more_information["crawl_lowfloor"]) ? $more_information["crawl_lowfloor"] : array();
		$arr_crawl = array();
		foreach ($crawl_lowfloor as $key => $val) {
			if(!empty($val["is_crawl"])) {
				$project_id = $key;
				$spreadsheetId = $val['sheetID'];
				$lst_range = $val['sheet_name'];
				$color_sold = $val['color_sold'];
				if(!empty(trim($spreadsheetId)) && !empty($lst_range)) {
					$ranges = explode("|",$lst_range);
					$arr_data = array();
					$cachedName = sprintf('%s_%s.json', $oneAgency["slug"], $project_id);
					$cachedFile = DIR_CACHE_JSON.'/crawl/'.$cachedName;
					if(file_exists($cachedFile)){
						$decoder = new Webmozart\Json\JsonDecoder();
						$arr_data = $decoder->decodeFile($cachedFile);
//						 @unlink($cachedFile);
					}else{
						$arr_data = $clsCrawlLowfloor->getData($spreadsheetId,$ranges,$project_id,$agency_id,$val); 
						$encoder = new Webmozart\Json\JsonEncoder();
//						$encoder->encodeFile($arr_data, $cachedFile); 
					}
					var_dump($arr_data);die;
					$list_404_stocks = array();
					if(!empty($arr_data)) {	
						$list_stocks = $clsStock->getAll("`is_trash`=0 and `stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' 
						AND `agency_id`='{$agency_id}' AND `project_id`='{$project_id}' AND `status_id`>0 
						AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."'", $clsStock->pkey);
						if(!empty($list_stocks)){
							$tmp = $clsStockLog->getByCond("`stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' AND `project_id`='{$project_id}' 
							AND `agency_id`='{$agency_id}' and FROM_UNIXTIME(`reg_date`,'%d/%m/%Y')='".date('d/m/Y')."'");
							if(!empty($tmp)){
								$_more_information = $tmp['more_information'];
								$arr_stocks = $clsISO->to_array_json($_more_information);
								if(!empty($list_stocks)){
									foreach($list_stocks as $_oStock){
										if(!in_array($_oStock[$clsStock->pkey], $arr_stocks)){
											$arr_stocks[] = $_oStock[$clsStock->pkey];
										}
									}
									unset($list_stocks);
								}
								var_dump($arr_stocks);die;
								/*$clsStockLog->updateOne($tmp[$clsStockLog->pkey], array(
									'more_information' => json_encode($arr_stocks, JSON_UNESCAPED_UNICODE)
								));*/
							} else {
								$arr_stocks = array();
								if(!empty($list_stocks)){
									foreach($list_stocks as $okey => $oval){
										$arr_stocks[] = $oval[$clsStock->pkey];
									}
								}
								/*$clsStockLog->insert(array(
									'stock_type' => $stock_type,
									'project_id' => $project_id,
									'agency_id' => $agency_id,
									'more_information' => json_encode($arr_stocks, JSON_UNESCAPED_UNICODE),
									'reg_date' => time()
								));*/
							}
						}
						$data_update = array();
						foreach($arr_data as $k_data => $v_data){
							foreach ($v_data as $k_stock => $v_stock) {
								$ms_code = $v_stock["ms_code"];
								$ms_code = preg_replace('/\s+/', '', $ms_code);
								$field = "{$clsStock->pkey},`status_id`,`DT_TT`,`logs`,`more_information`";
								$oneStock = $clsStock->getByCond("`is_trash`=0 and stock_type='"._BLOCK_TYPE_LOWFLOOR_SALE."' 
								and `project_id`='{$project_id}' and `ms_code`='{$ms_code}'", $field);
								if(!empty($oneStock)) {
									$v_stock["more_information"] = $clsISO->to_array_json($oneStock["more_information"]);
									$v_stock["logs"] = $clsISO->to_array_json($oneStock["logs"]);
									$data_update[$oneStock[$clsStock->pkey]] = $v_stock;	
								}else{
									$list_404_stocks[] = $ms_code;
								}
							}
						}
//						var_dump(array_keys($data_update));die;
						// Update về đã bán
						$field = "{$clsStock->pkey},more_information";
						$clsStock->setDeBug(1);
						$cond_sold = "`stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' 
						AND `project_id`='{$project_id}' AND `agency_id`='{$agency_id}' AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."' AND `status_id`>0";
						if(!empty($data_update)) {
							$cond_sold .= " AND `stock_id` NOT IN(".implode(',',array_keys($data_update)).")";
						}
						$list_stocks = $clsStock->getAll($cond_sold, $field);
//						var_dump($list_stocks);die;
						if(!empty($list_stocks)){
							foreach($list_stocks as $key => $val){
								$more_information = $val['more_information'];
								$more_information = $clsISO->to_array_json($more_information);
								$more_information['status_id'] = _STOCK_STATUS_SOLD_ID;
								$clsStock->updateOne($val[$clsStock->pkey], array(
									'status_id' => _STOCK_STATUS_SOLD_ID,
									'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
								));
							}
						}
						if(!empty($data_update)) {
							foreach ($data_update as $stock_id => $v_stock) {
								$ms_code = $v_stock["ms_code"];
								$ms_code = preg_replace('/\s+/', '', $ms_code);
								$uid = $clsISO->getUniqid();
								$upd_field = array();
								$logs = $v_stock['logs'];
								$more_information = $v_stock['more_information'];
								$stock_status_id = _STOCK_STATUS_LOCK_ID;
								foreach($v_stock as $p_field => $p_value){
									if($p_field == "more_information" || $p_field == "logs") continue;
									if(!empty($p_field) && !empty($p_value)){
										if($p_field=='type_id'){ //Loại hình
											$field = "{$clsProperty->pkey}";
											$tmp = $clsProperty->getByCond("`property_type`='_TYPE_VILLA' and (`property_code`='".$p_value."' or `slug_vn`='".$core->replaceSpace($p_value)."' or `slug`='".$core->replaceSpace($p_value)."')", $field);
											$v_stock[$p_field] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
										} else if($p_field=='status_id'){ //Tình trạng
											$field = "{$clsProperty->pkey}";
											$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_STATUS' 
												and `slug`='".$core->replaceSpace($p_value)."'", $field);
											$v_stock[$p_field]  = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
										} else if($p_field=='stock_hold_id'){ //Loại quỹ
											$field = "{$clsProperty->pkey}";
											$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_STOCK_HOLD' 
												and `slug`='".$core->replaceSpace($p_value)."'", $field);
											$v_stock[$p_field] = !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
										} else if($p_field=='contract_type_id'){ //Loại hình ký HĐ
											$field = "{$clsProperty->pkey}";
											$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_CONTRACT_TYPE' 
												and `slug`='".$core->replaceSpace($p_value)."'", $field);
											if(!empty($tmp)){
												$contract_type_id = $tmp[$clsProperty->pkey];
											} else {
												$contract_type_id = $clsProperty->getMaxId();
												$clsProperty->insert(array(
													$clsProperty->pkey => $contract_type_id,
													'property_type' => '_CONTRACT_TYPE',
													'title' => $p_value,
													'slug' => $core->replaceSpace($p_value),
													'order_no' => $clsProperty->getMaxorderNo(),
													'user_id' => $core->_USER['user_id'],
													'user_id_update' => $core->_USER['user_id'],
													'reg_date' => time(),
													'upd_date' => time()
												));
											}
											$v_stock[$p_field] = $contract_type_id;
										} else if($p_field=='contract_subject_id'){ //Chủ thể ký HĐ
											$field = "{$clsProperty->pkey}";
											$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_CONTRACT_SUBJECT' 
												and `slug`='".$core->replaceSpace($p_value)."'", $field);
											if(!empty($tmp)){
												$contract_subject_id = $tmp[$clsProperty->pkey];
											} else {
												$contract_subject_id = $clsProperty->getMaxId();
												$clsProperty->insert(array(
													$clsProperty->pkey => $contract_subject_id,
													'property_type' => '_CONTRACT_SUBJECT',
													'title' => $p_value,
													'slug' => $core->replaceSpace($p_value),
													'order_no' => $clsProperty->getMaxorderNo(),
													'user_id' => $core->_USER['user_id'],
													'user_id_update' => $core->_USER['user_id'],
													'reg_date' => time(),
													'upd_date' => time()
												));
											}
											$v_stock[$p_field] = $contract_subject_id;
										} else if($p_field=='invest_fund_id'){ // quỹ đầu tư
											$field = "{$clsProperty->pkey}";
											$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_INVEST_FUND' 
												and `slug`='".$core->replaceSpace($p_value)."'", $field);
											if(!empty($tmp)){
												$invest_fund_id = $tmp[$clsProperty->pkey];
											} else {
												$invest_fund_id = $clsProperty->getMaxId();
												$clsProperty->insert(array(
													$clsProperty->pkey => $invest_fund_id,
													'property_type' => '_INVEST_FUND',
													'title' => $p_value,
													'slug' => $core->replaceSpace($p_value),
													'order_no' => $clsProperty->getMaxorderNo(),
													'user_id' => $core->_USER['user_id'],
													'user_id_update' => $core->_USER['user_id'],
													'reg_date' => time(),
													'upd_date' => time()
												));
											}
											$v_stock[$p_field] = $invest_fund_id;
										} else if($p_field=='sale_status_id'){ // tình trạng bán
											$field = "{$clsProperty->pkey}";
											$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_SALE_STATUS' 
												and `slug`='".$core->replaceSpace($p_value)."'", $field);
											if(!empty($tmp)){
												$sale_status_id = $tmp[$clsProperty->pkey];
											} else {
												$sale_status_id = $clsProperty->getMaxId();
												$clsProperty->insert(array(
													$clsProperty->pkey => $sale_status_id,
													'property_type' => '_SALE_STATUS',
													'title' => $p_value,
													'slug' => $core->replaceSpace($p_value),
													'order_no' => $clsProperty->getMaxorderNo(),
													'user_id' => $core->_USER['user_id'],
													'user_id_update' => $core->_USER['user_id'],
													'reg_date' => time(),
													'upd_date' => time()
												));
											}
											$v_stock[$p_field] = $sale_status_id;
										} else if(in_array($p_field, array('bank_second_id','bank_id'))){ // giỏ bank/thứ cấp
											$field = "{$clsProperty->pkey}";
											$tmp = $clsProperty->getByCond("`is_trash`=0 and `property_type`='_BANK' 
												and slug='".$core->replaceSpace($p_value)."'", $field);
											if(!empty($tmp)){
												$bank_id = $tmp[$clsProperty->pkey];
											} else {
												$bank_id = $clsProperty->getMaxId();
												$clsProperty->insert(array(
													$clsProperty->pkey => $bank_id,
													'property_type' => '_BANK',
													'title' => $p_value,
													'slug' => $core->replaceSpace($p_value),
													'order_no' => $clsProperty->getMaxorderNo(),
													'user_id' => $core->_USER['user_id'],
													'user_id_update' => $core->_USER['user_id'],
													'reg_date' => time(),
													'upd_date' => time()
												));
											}
											$v_stock[$p_field] = $bank_id;
										} else if($p_field=='home_direction_id'){ //hướng
											$field = "{$clsProperty->pkey}";
											$tmp = $clsProperty->getByCond("`property_type`='_DIRECTION' 
											and slug='".$core->replaceSpace($p_value)."'", $field);
											$v_stock[$p_field]= !empty($tmp) ? $tmp[$clsProperty->pkey] : 0;
										}
										if(!in_array($p_field, array('DT_Tim','DT_TT','total_price','total_price_vat','csbh','TCBG'
											,'contract_subject_id','invest_fund_id','bank_second_id','bank_id','sale_status_id'
											,'agent_lock_id','deposit_agent_id','total_price_early','total_price_progress'
											,'total_price_bank','total_price_bank_30','total_price_bank_36','total_price_bank_18'
											,'total_price_bank_12','cs_policy_ns','price_temporary_ns','stock_hold_id'
											,'contract_type_id','deposit_date','notes'))){
											$upd_field[$p_field] = $v_stock[$p_field];
											$more_information[$p_field] = $v_stock[$p_field];
										} else {
											if(in_array($p_field, array('total_price','total_price_vat','total_price_early'
											,'total_price_progress','total_price_bank','total_price_bank_30','total_price_bank_36'
											,'total_price_bank_12','total_price_bank_18'))){
												if((!isset($more_information[$p_field]) || (!empty($more_information[$p_field]) 
													&& $more_information[$p_field] != $clsStock->getPriceOriginV2($p_value)))){	//check log thay đổi
													$logs[$clsISO->getUniqid()] = array(
														'reg_date' => time(),
														'user_id' => $core->_USER['user_id'],
														'from_value' => $more_information[$p_field],
														'to_value' => $clsStock->getPriceOriginV2($p_value),
														'field' => $p_field
													);
												}
												$more_information[$p_field] = $clsStock->getPriceOriginV2($p_value);
											} else {
												$more_information[$p_field] = $p_value;
											}
										}	
									}
								}
								// End For
								if(!empty($v_stock['DT_TT'])) $upd_field['DT_TT'] = $clsISO->toNumber($v_stock['DT_TT']);
								if(!empty($v_stock['total_price_vat'])){
									$upd_field['total_price_vat'] = $clsStock->getPriceOriginV2($v_stock['total_price_vat']);
								} else {
									if(!empty($v_stock['total_price_early'])){
										$upd_field['total_price_early'] = $clsStock->getPriceOriginV2($v_stock['total_price_early']);
									}	
								}
								$more_information['status_id'] = $stock_status_id;
								$upd_field['status_id'] = $stock_status_id;
								$upd_field['upd_date'] = time();
								$upd_field['logs'] = json_encode($logs, JSON_UNESCAPED_UNICODE);
								$upd_field['more_information'] = json_encode($more_information, JSON_UNESCAPED_UNICODE);
								if($clsStock->updateOne($stock_id, $upd_field)){
									$total_updated += 1;
								}
							}
						}
						var_dump($upd_field);die;
					} else {
						$field = "{$clsStock->pkey},`status_id`,`logs`,`more_information`";
						$list_stocks = $clsStock->getAll("`is_trash`=0 and `stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' 
						AND `agency_id`='{$agency_id}' AND `project_id`='{$project_id}' AND `status_id`>0 
						AND `status_id`<>'"._STOCK_STATUS_SOLD_ID."'", $field);
						if(!empty($list_stocks)){
							$total_updated = 0;
							foreach($list_stocks as $key => $val){
								$logs = $val['logs'];
								$more_information = $val['more_information'];
								$logs = $clsISO->to_array_json($logs);
								$more_information = $clsISO->to_array_json($more_information);
								$logs[$clsISO->getUniqid()] = array(
									'reg_date' => time(), 
									'user_id' => $core->_USER['user_id'],
									'from_id' => $val['status_id'],
									'to_id' => _STOCK_STATUS_SOLD_ID,
									'field' => 'status_id'
								);
								$more_information['status_id'] = _STOCK_STATUS_SOLD_ID;
								$more_information['user_id_update_sold'] = $profile_id;
								if($clsStock->updateOne($val[$clsStock->pkey], array(
									'ms_date' => time(),
									'upd_date' => time(),
									'status_id' => _STOCK_STATUS_SOLD_ID,
									'logs' => json_encode($logs, JSON_UNESCAPED_UNICODE),
									'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)
								))) {
									$total_updated = 1;
								}
							}
						}
					}
				}					
				// Start Logs
				$clsAdminLog = new AdminLog();
				$clsAdminLog->insertLog('update_stock', _BLOCK_TYPE_LOWFLOOR_SALE, $project_id, $agency_id, array(), '_admin');
				// End Logs
				if(!empty($list_404_stocks)){
					$clsAdminLog = new AdminLog();
					$clsAdminLog->insert(array(
						'date' => time(),
						'action' => 'update_stock_import',
						'user_id' => $core->_USER['user_id'],
						'target_id' => $project_id,
						'description' => json_encode($list_404_stocks, JSON_UNESCAPED_UNICODE)
					));
				}
				/** End */
			}
		}	
	}
	$clsISO->print_pre($oneAgency);die;
}
function default_crawl_agency(){
	ini_set('memory_limit', '5048M');
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);
	global $smarty, $assign_list, $adminid, $core, $clsISO, $_LANG_ID;
	$clsCrawlLowFloor = new CrawlLowFloor();
	$clsProperty = new Property();
//	$arr_data = $clsCrawlLowfloor->getData($price_sheet_id,$ranges,_PROJECT_VHOP2_ID,318); 
//	$arr_data = $clsCrawlLowfloor->getData($price_sheet_id,$ranges,_PROJECT_VHOP3_ID,318); 
//	$arr_data = $clsCrawlLowfloor->getData($price_sheet_id,$ranges,_PROJECT_VHGG_ID,318); 
//	$arr_data = $clsCrawlLowfloor->getData($price_sheet_id,$ranges,_PROJECT_VWC_ID,318); 
	$ranges = ['ĐỘC QUYỀN OCP3']; 
	$price_sheet_id = "15OSL1ZEC0nlt93ll0Mb2xrpgVn0Awpp1";
	$arr_data = $clsCrawlLowFloor->getData($price_sheet_id,$ranges,_PROJECT_VHOP3_ID,261,"#ff0000"); 
	$clsISO->print_pre($arr_data);die;
}
function default_start_config_column(){
//		ini_set('display_errors', '1');
//		ini_set('display_startup_errors', '1');
//		error_reporting(E_ALL);
	require_once (DIR_INCLUDES.'/json_master/autoload.php');				
	require_once (DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn,$smarty;
	$clsStock = new StockCrawl();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsCrawlLowFloor = new CrawlLowFloor();
	$assign_list['clsProject'] = $clsProject;
	$assign_list['clsProperty'] = $clsProperty;
	#
	$uid = $clsISO->getUniqid();
	$agency_id = (int) Input::post('agency_id', 0);
	$block_id = (int) Input::post('block_id',0);
	$project_id = (int) Input::post('project_id',0);
	$stock_type = (int) Input::post('stock_type',_BLOCK_TYPE_HIGHLEVEL_SALE);
	$ranges = 	Input::post('sheet_name',"");
	$spreadsheetId = 	Input::post('sheetID',"");
	$arr_data = $column_data = array();
	if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE && !empty($agency_id) && !empty($project_id) && !empty($ranges) && !empty($spreadsheetId)) {
		$ranges = explode("|",$ranges); 
		$arr_data = $clsCrawlLowFloor->getDataConfigColumn($spreadsheetId,$ranges,$project_id,$agency_id); 
		$cachedFile = DIR_CACHE_JSON.'/crawl/config_column_lowfloor.json';
		if(file_exists($cachedFile)){
			$decoder = new Webmozart\Json\JsonDecoder();
			$arr_column_data = $decoder->decodeFile($cachedFile);			
		}	
		$column_data = !empty($arr_column_data[$agency_id][$project_id]) ? $arr_column_data[$agency_id][$project_id] : array();
	}
	$highestColumnIndex = 29;
	$smarty->assign("clsStock",$clsStock);
	$smarty->assign("uid",$uid);
	$smarty->assign("column_data",$column_data);
	$smarty->assign("agency_id",$agency_id);
	$smarty->assign("block_id",$block_id);
	$smarty->assign("project_id",$project_id);
	$smarty->assign("stock_type",$stock_type);
	$smarty->assign("highestColumnIndex",$highestColumnIndex);
	$smarty->assign("arr_data",$arr_data);
	$html = $core->build("_ajax.start_config_column.tpl");
	echo json_encode(array(
		'result'	=>	true,
		'uid' => $uid,
		'html' => $html
	)); die();
//	$clsISO->print_pre($arr_data);die;
}
function default_do_config_column(){
	require_once (DIR_INCLUDES.'/json_master/autoload.php');				
	require_once (DIR_INCLUDES . '/googleapiclient/vendor/autoload.php');
	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current
	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$dbconn,$smarty;
	$clsStock = new Stock();
	$clsProject = new Project();
	$clsProperty = new Property();
	$clsCrawlLowFloor = new CrawlLowFloor();
	$assign_list['clsProject'] = $clsProject;
	$assign_list['clsProperty'] = $clsProperty;
	#
	$uid = $clsISO->getUniqid();
	$agency_id = (int) Input::post('agency_id', 0);
	$block_id = (int) Input::post('block_id',0);
	$project_id = (int) Input::post('project_id',0);
	$stock_type = (int) Input::post('stock_type',_BLOCK_TYPE_HIGHLEVEL_SALE);
	$sheet_name = 	Input::post('sheet_name',"");
	$columns = 	Input::post('columns',array());
	$spreadsheetId = 	Input::post('sheetID',"");
	$column_data = array();
	if($stock_type == _BLOCK_TYPE_LOWFLOOR_SALE && !empty($agency_id) && !empty($project_id)) {
		$cachedFile = DIR_CACHE_JSON.'/crawl/config_column_lowfloor.json';
		if(file_exists($cachedFile)){
			$decoder = new Webmozart\Json\JsonDecoder();
			$column_data = $decoder->decodeFile($cachedFile);			
		}	
		$column_data[$agency_id][$project_id] = $columns;
		$encoder = new Webmozart\Json\JsonEncoder();
		$encoder->encodeFile($column_data, $cachedFile);
	}elseif($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE && !empty($agency_id) && !empty($block_id)) {
		$cachedFile = DIR_CACHE_JSON.'/crawl/config_column_highloor.json';
		if(file_exists($cachedFile)){
			$decoder = new Webmozart\Json\JsonDecoder();
			$column_data = $decoder->decodeFile($cachedFile);			
		}	
		$column_data[$agency_id][$block_id] = $columns;
		$encoder = new Webmozart\Json\JsonEncoder();
		$encoder->encodeFile($column_data, $cachedFile);
	}
	echo json_encode(array(
		'result'	=>	true,
		'uid' => $uid,
		'html' => $html
	)); die();
}
//========quản lý website======
function default_site_manager(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	###
	$list_domains = $clsConfiguration->getValue('list_domains');
	$list_domains = $clsISO->to_array_json($list_domains);
	// $clsISO->print_pre($agency_field_data_center); die();
	$assign_list['list_domains'] = $list_domains;
}
function default_open_domain(){
	// ini_set('display_errors', '1');
	// ini_set('display_startup_errors', '1');
	// error_reporting(E_ALL);
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$clsConfiguration;	
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	$clsProperty = new Property();
	$clsProject = new Project();
	$clsPage = new Page();
	###
	$uid = $clsISO->getUniqid();
	$domain_id = Input::post('domain_id', "");
	$list_domains = $clsConfiguration->getValue('list_domains');
	$list_domains = $clsISO->to_array_json($list_domains);
	$lstPage = $clsPage->getAll("`is_trash`='0' AND `is_online`='1' AND `is_about_us`='1'");
	###
	$titlePage = "Thêm mới";
	$oneItem = array(); $action = "_edit"; 
	if( !empty($domain_id) && array_key_exists($domain_id, $list_domains)) {
		$action = '_edit';
		$titlePage= 'Chỉnh sửa';
		$oneItem = $list_domains[$domain_id];
	}
	$smarty->assign('uid', $uid);
	$smarty->assign('oneItem', $oneItem);
	$smarty->assign('titlePage', $titlePage);
	$smarty->assign('domain_id', $domain_id);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('lstPage', $lstPage);
	// Return
	$html = $core->build('_ajax.open_domain.tpl');
	echo $html; die();
}
function default_save_domain(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$clsConfiguration;	
	##
	$uid = $clsISO->getUniqid();
	$domain_id = Input::post('domain_id', "");
	$domain_id = !empty($domain_id) ? $domain_id : $uid;
	$is_default = (int)Input::post('is_default', 0);
	$title = Input::post('title', "");
	$domain = Input::post('domain', "");
	$link = Input::post('link', "");
	$action = Input::post('action', "save");
	$cat_id = (int)Input::post('cat_id', "0");
	$cat_faqs_id = (int)Input::post('cat_faqs_id', "0");
	$about_us_id = (int)Input::post('about_us_id', "0");
	##
	$list_domains = $clsConfiguration->getValue('list_domains');
	$list_domains = $clsISO->to_array_json($list_domains);
	$res = ["result" =>	false];
	if($action == "save") {
		$list_domains[$domain_id] = [
			"title"	=>	$title,
			"link" =>	$link,
			"domain" =>	$domain,
			"cat_id" =>	$cat_id,
			'cat_faqs_id' => $cat_faqs_id,
			'about_us_id' => $about_us_id,
		];
	} elseif ($action == "delete"){
		unset($list_domains[$domain_id]);
	}
	if($clsConfiguration->updateValue("list_domains", json_encode($list_domains, JSON_UNESCAPED_UNICODE))){
		$res = ["result"	=>	true];
	}
	// Return
	echo json_encode($res); die();
}
//===========Cấu hình giá Min max cho dự án=============
function default_config_price(){
	global $assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
	$clsProperty = new Property();
	$clsProject = new Project();
	$field_config_price = $clsConfiguration->getValue('field_config_price');
	$field_config_price = $clsISO->to_array_json($field_config_price);
	$arr_cache_project = $arr_cache_block = [];
	foreach ($field_config_price as $key => $val) {
		if(!isset($arr_cache_project[$val["project_id"]])) {
			$arr_cache_project[$val["project_id"]] = $clsProject->getTitle($val["project_id"]);
		}
		$field_config_price[$key]["project_name"] = $arr_cache_project[$val["project_id"]];
		if(!isset($arr_cache_block[$val["block_id"]])) {
			if(!empty($val["block_id"])) {
				$arr_cache_block[$val["block_id"]] = $clsProperty->getTitle($val["block_id"]);
			}else{
				$arr_cache_block[$val["block_id"]] = "--";
			}
		}
		$field_config_price[$key]["block_name"] = $arr_cache_block[$val["block_id"]];
		$field_config_price[$key]["stock_type_name"] = ($val["stock_type"] == _BLOCK_TYPE_HIGHLEVEL_SALE) ? "Cao tâng" : "Thấp tầng";
	}
	$assign_list['field_config_price'] = $field_config_price;
	$assign_list['clsProperty'] = $clsProperty;
	$assign_list['clsProject'] = $clsProject;
}
function default_open_field_config_price(){
//	ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$clsConfiguration;	
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	$clsProperty = new Property();
	$clsProject = new Project();
	$field_config_price_id = Input::post('field_config_price_id', "");
	$field_config_price = $clsConfiguration->getValue('field_config_price');
	$field_config_price = $clsISO->to_array_json($field_config_price);
	$oneItem = !empty($field_config_price[$field_config_price_id]) ? $field_config_price[$field_config_price_id] : array();
	$action = "_edit";
	$titlePage = "Thêm mới";
	$list_blocks = $list_buildings = $more_information = array();
	if(!empty($field_config_price[$field_config_price_id])) {
		$action = '_edit';
		$titlePage= 'Chỉnh sửa';
	}
	$project_id = !empty($oneItem['project_id']) ? $oneItem['project_id'] : _PROJECT_DEF_ID;
	$uid = $clsISO->getUniqid();
	// Return
	$smarty->assign('oneItem', $oneItem);
	$smarty->assign('uid', $uid);
	$smarty->assign('titlePage', $titlePage);
	$smarty->assign('clsProperty', $clsProperty);
	$smarty->assign('clsProject', $clsProject);
	$smarty->assign('project_id', $project_id);
	$smarty->assign('field_config_price_id', $field_config_price_id);
	$html = $core->build('_ajax.open_field_config_price.tpl');
	echo $html; die();
}
function default_save_field_config_price(){
	global $smarty,$_CONFIG,$_SITE_ROOT,$mod,$act;
	global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO,$clsConfiguration;	
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	$uid = $clsISO->getUniqid();
	$clsProject = new Project();
	$clsProperty = new Property();
	$field_config_price_id = Input::post('field_config_price_id', "");
	$field_config_price_id = !empty($field_config_price_id) ? $field_config_price_id : $uid;
	$project_id = (int)Input::post('project_id', 0);
	$block_id = (int)Input::post('block_id', 0);
	$stock_type = (int)Input::post('stock_type', _BLOCK_TYPE_HIGHLEVEL_SALE);
	$min = Input::post('min', "");
	$min = $clsISO->processSmartNumber($min);
	$max = Input::post('max', "");
	$max = $clsISO->processSmartNumber($max);
	$action = Input::post('action', "save");
	$res = ["result"	=>	false];
	if($action == "save") {
		$field_config_price = $clsConfiguration->getValue('field_config_price');
		$field_config_price = $clsISO->to_array_json($field_config_price);
		if(!empty($field_config_price)) {
			foreach ($field_config_price as $key => $val) {
				if($val["project_id"] == $project_id && $val["block_id"] == $block_id && $field_config_price_id != $key && $stock_type == $val["stock_type"]) {
					$project_name = $clsProject->getTitle($project_id);
					if($block_id > 0) {
						$block_name = $clsProperty->getTitle($block_id);
					}
					$msg = !empty($block_name) ? "Phân khu ".$block_name. ", ".$project_name." đã được cấu hình" : $project_name." đã được cấu hình";
					$stock_type_name = ($val["stock_type"] == _BLOCK_TYPE_HIGHLEVEL_SALE) ? "Dự án cao tầng " : "Dự án thấp tầng ";
					$res = ["result"	=>	false,"msg"=>$stock_type_name.$msg];
					echo json_encode($res); die();
					break;
				}
			}		
		}
		$msg = "Thành công!";
		$field_config_price[$field_config_price_id] = [
			"project_id"	=>	$project_id,
			"block_id"	=>	$block_id,
			"stock_type"	=>	$stock_type,
			"min"	=>	$min,
			"max"	=>	$max
		];
	}elseif($action == "delete"){
		$field_config_price = $clsConfiguration->getValue('field_config_price');
		$field_config_price = $clsISO->to_array_json($field_config_price);
		unset($field_config_price[$field_config_price_id]);
		$msg = "Xóa thành công!";
	}
	if($clsConfiguration->updateValue("field_config_price", json_encode($field_config_price, JSON_UNESCAPED_UNICODE))){
		$res = ["result"	=>	true,"msg" =>$msg];
	}
	// Return
	echo json_encode($res); die();
}
function default_add_info_agency(){
	global $smarty,$core,$dbconn,$mod,$act,$clsISO;
	$clsPermiss = new Permiss();
	$clsProperty = new Property();
	###
	$tp = Input::post("tp","branch_office");
	$html = "";
	if($tp == "branch_office") {
		$html = '<div class="form-row mb-2 item">
					<label class="col-md-2 text-right col-form-label required">Văn phòng chi nhánh
						<a href="javascript:void(0);" class="btn_add_item" onClick="$Core.property.add_info_agency(this,event)" tp="branch_office" >+Thêm</a>
					</label>
					<div class="col-md-9">
						<input type="text" class="form-control" onClick="this.select();" name="info_agency[branch_office][]" value="" placeholder="Chi nhánh" maxlength="255">
					</div>
					<div class="col-md-1">
						<button class="btn btn-default" onClick="$Core.property.delete_info_agency(this,event)" tp="branch_office" type="button" ><i class="fa fa-trash"></i></button>
					</div>
				</div>';
	}else if($tp == "project") {
		$gid = $clsISO->getUniqid();
		$html = '<div class="form-row mb-2 item">
					<label class="col-md-2 text-right col-form-label required">Dự án bán
						<a href="javascript:void(0);" class="btn_add_item" onClick="$Core.property.add_info_agency(this,event)" tp="project" >+Thêm</a>
					</label>
					<div class="col-md-3">
						<input type="text" class="form-control" onClick="this.select();" name="info_agency[project]['.$gid.'][title]" value="" placeholder="Tên dự án" maxlength="255">
					</div>
					<div class="col-md-3">
						<input type="text" class="form-control" onClick="this.select();" name="info_agency[project]['.$gid.'][project_manager]" value="" placeholder="Giám đốc dự án" maxlength="255">
					</div>
					<div class="col-md-3">
						<input type="text" class="form-control" onClick="this.select();" name="info_agency[project]['.$gid.'][project_admin]" value="" placeholder="Admin dự án" maxlength="255">
					</div>
					<div class="col-md-1">
						<button class="btn btn-default" onClick="$Core.property.delete_info_agency(this,event)" tp="project" type="button" ><i class="fa fa-trash"></i></button>
					</div>
				</div>';
	}
	// Return
	echo $html; die();
}
?>