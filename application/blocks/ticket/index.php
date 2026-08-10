<?php if(!defined('ABSPATH')) exit('No direct script access allowed');
/* Block "Ticket hỗ trợ" — vỏ widget nổi; dữ liệu nạp qua proxy nội bộ (mod=ticket&act=...).
   Chỉ render khi ĐÃ cấu hình khoá (configs/ticket.php) và ĐÃ đăng nhập — chưa cấu hình thì tự ẩn. */
global $smarty, $profile_id;
$ticket_enabled = defined('TICKET_LICENSE_KEY') && TICKET_LICENSE_KEY !== ''
	&& defined('TICKET_SECRET_KEY') && TICKET_SECRET_KEY !== ''
	&& (int) $profile_id > 0;
$smarty->assign('ticket_enabled', $ticket_enabled ? 1 : 0);
?>
