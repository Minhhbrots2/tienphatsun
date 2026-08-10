<?
$_MOD_INI = array(
	"Info"	=>	array(
		"Name"	=>	"news",
		"Display_Name"	=>	"News Management",
		"Description"	=>	"News Management Module by VietISO",
		"Status"	=>	"Active",
		"Anonymous"	=>	"NO",
		"Author"	=>	"ISOCMS",
		"Author_Link"=>	"http://www.vietiso.com",
    	"Version" => "1.0", 
		"License_Key"	=>	"20c8b72395f5cb459d23287dbf40a0de"),
	"sqlActiveMod" =>"
		CREATE TABLE `".DB_PREFIX."news` (
		`news_id` int(5) NOT NULL AUTO_INCREMENT,
		`news_cat_id` int(8) DEFAULT NULL,
		`user_id` int(8) DEFAULT NULL,
		`user_id_update` int(8) DEFAULT NULL,
		`title` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
		`slug` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
		`intro` text CHARACTER SET utf8,
		`content` longtext CHARACTER SET utf8 NOT NULL,
		`image` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
		`order_no` int(10) NOT NULL,
		`reg_date` int(8) DEFAULT NULL,
		`upd_date` int(8) DEFAULT NULL,
		`is_trash` tinyint(1) NOT NULL,
		`is_online` tinyint(1) DEFAULT '1',
		`lang_id` varchar(2) NOT NULL,
		PRIMARY KEY (`news_id`)
		) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
		|||
		CREATE TABLE `".DB_PREFIX."newscat` (
		`newscat_id` int(11) NOT NULL AUTO_INCREMENT,
		`parent_id` int(10) unsigned NOT NULL,
		`title` varchar(255) NOT NULL DEFAULT '',
		`slug` varchar(255) NOT NULL,
		`intro` varchar(255) NOT NULL,
		`user_id` int(8) NOT NULL,
		`user_id_update` int(8) NOT NULL,
		`reg_date` int(10) unsigned NOT NULL DEFAULT '0',
		`upd_date` int(10) unsigned NOT NULL DEFAULT '0',
		`image` varchar(255) NOT NULL,
		`is_trash` tinyint(1) NOT NULL,
		`order_no` int(10) unsigned NOT NULL DEFAULT '0',
		`getNAV` text NOT NULL,
		`lang_id` varchar(2) NOT NULL,
		PRIMARY KEY (`newscat_id`)
		) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
		|||
		CREATE TABLE `".DB_PREFIX."tag_news` (
		`tag_news_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		`tag_id` int(10) NOT NULL DEFAULT '0',
		`news_id` int(10) NOT NULL DEFAULT '0',
		`val` tinyint(1) NOT NULL,
		`lang_id` varchar(2) NOT NULL,
		PRIMARY KEY (`tag_news_id`)
		) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
	",
	"sqlDeActiveMod" => "
		DROP TABLE IF EXISTS `".DB_PREFIX."news`;
		|||
		DROP TABLE IF EXISTS `".DB_PREFIX."newscat`;
		|||
		DROP TABLE IF EXISTS `".DB_PREFIX."tag_news`;
	"
);
?>