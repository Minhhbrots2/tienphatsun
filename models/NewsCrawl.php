<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # Tin đã crawl (staging) — bảng default_news_crawl                 # ||
|| # Future Homes Group — feature crawl tin BĐS                      # ||
|| #################################################################### ||
\*======================================================================*/
class NewsCrawl extends dbBasic{
	function __construct(){
		$this->pkey = "crawl_id";
		$this->tbl  = DB_PREFIX."news_crawl";
	}
	/** Dedup: đã có url_hash chưa (md5 = hex an toàn, vẫn qua countItem). */
	function existsByHash($hash){
		return (int) $this->countItem("`url_hash`='".$hash."'") > 0;
	}
}
