<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/* Tin nhắn trong kênh chat. Bảng default_chat_message.
   type: 1=text, 2=image, 3=system. Escape content khi render (autoescape OFF). */
class ChatMessage extends dbBasic{
	function __construct(){
		$this->pkey = "message_id";
		$this->tbl = DB_PREFIX."chat_message";
	}
}
