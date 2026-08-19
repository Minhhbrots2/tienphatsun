<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
function default_profile(){
	global $assign_list,$core,$clsISO,$title_page;
	# Trang ho so tu van vien cong khai (/profile/<slug>) - khong can dang nhap.
	# Phase 1: noi dung tinh (clone). Phase 2: doc du lieu sale theo slug.
	$slug = Input::get('slug', '');
	$assign_list['slug'] = $slug;
	/*=============Title & Description Page==================*/
	$title_page = 'Hồ sơ tư vấn viên - '.PAGE_NAME;
	$assign_list["title_page"] = $title_page;
}
?>
