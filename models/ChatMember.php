<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/* Thành viên kênh chat. Bảng default_chat_member.
   - Kênh type1/2: dòng tạo LƯỜI khi mở kênh, chỉ giữ trạng thái đọc.
   - Kênh type4 (Nhóm): là NGUỒN-CHÂN-LÝ membership — có dòng = được vào (chat_access type4).
   role: 1=Chủ nhóm (owner), 0=Thành viên. Chưa đọc = đếm message_id > last_read_message_id (bỏ tin của chính mình). */
class ChatMember extends dbBasic{
	function __construct(){
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."chat_member";
	}
}
