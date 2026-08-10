<?php if (!defined('ABSPATH')) exit('No direct script access allowed');

if(!defined('BRAND_NAME')){
	define('BRAND_NAME', trim(Configuration::getInstance()->getValue('checkin_brand_name', '')));
}
?>
