<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/**
 * Cấu hình Database — PER-KHÁCH.
 * Sửa file này khi dựng host cho khách mới (config.php require vào).
 */
/** MySQL hostname */
define('DB_HOST', '127.0.0.1');
/** Tên database */
define('DB_NAME', 'tienphatsun_db');
/** MySQL username */
define('DB_USER', 'tienphatsun_db');
/** MySQL password */
define('DB_PASS', 'pPdnzN2hNcYHzk8h');
/** Charset */
define('DB_CHARSET', 'utf8');
/** Collate */
define('DB_COLLATE', '');
/** Loại database */
define('DB_TYPE', 'mysqli');
/** Tiền tố bảng (theo schema — thường giữ nguyên) */
define('DB_PREFIX', 'default_');
