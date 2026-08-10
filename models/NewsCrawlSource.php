<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # Nguồn báo crawl (config) — bảng default_news_crawl_source        # ||
|| # Future Homes Group — feature crawl tin BĐS                      # ||
|| #################################################################### ||
\*======================================================================*/
class NewsCrawlSource extends dbBasic{
	function __construct(){
		$this->pkey = "source_id";
		$this->tbl  = DB_PREFIX."news_crawl_source";
	}
	/** Danh sách nguồn đang bật (cho cron). */
	function getActive(){
		return $this->getAll("`is_active`=1 AND `is_trash`=0 ORDER BY `source_id` ASC");
	}
}
