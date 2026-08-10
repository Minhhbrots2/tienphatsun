<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/* Cảm xúc thả vào tin chat. Bảng default_chat_reaction.
   1 NV TỐI ĐA 1 cảm xúc/tin (UNIQUE message_id+profile_id). emoji = CODE
   ('love'|'like'|'haha'|'wow'|'sad'|'angry') — KHÔNG lưu ký tự emoji 4-byte (bảng utf8). */
class ChatReaction extends dbBasic{
	function __construct(){
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."chat_reaction";
	}
}
