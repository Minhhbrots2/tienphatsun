<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/* Kênh chat nội bộ. Bảng default_chat_channel.
   type: 1=Toàn công ty, 2=Phòng ban, 3=Check-in (đặc biệt, render từ office_checkin), 4=Nhóm (membership qua chat_member).
   image: ảnh nhóm (type4); rỗng = chưa đặt → fallback glyph ở UI. */
class ChatChannel extends dbBasic{
	function __construct(){
		$this->pkey = "channel_id";
		$this->tbl = DB_PREFIX."chat_channel";
	}
}
