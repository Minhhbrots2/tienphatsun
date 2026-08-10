<?php if(!defined('ABSPATH')) exit('No direct script access allowed');
/* Block "Chat nội bộ" — vỏ widget; dữ liệu nạp qua AJAX (mod=chat act=my|feed|checkin).
   Chỉ render khi đã đăng nhập (guard {if $profile_id} ở layout index.tpl). */
?>