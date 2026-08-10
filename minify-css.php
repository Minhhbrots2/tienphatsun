<?php
define('DS', DIRECTORY_SEPARATOR);
define('ABSPATH', dirname(__FILE__));
define("DIR_APPLICATION", ABSPATH."/application");
define("DIR_THEMES", DIR_APPLICATION."/themes");
define("DIR_VENDOR", DIR_THEMES."/vendor");
define("DIR_CSS", DIR_THEMES."/css");
define("DIR_JS", DIR_THEMES."/js");
// Danh sách các file CSS cần gộp
$cssFiles = [
	DIR_VENDOR . DS . 'css/core.css',
	DIR_VENDOR . DS . 'css/theme-default.css',
    DIR_CSS . DS . 'owl.carousel.css',
	DIR_CSS . DS . 'font-awesome.min.css',
	DIR_CSS . DS . 'jquery-confirm.min.css',
	DIR_CSS . DS . 'jquery-ui.css',
	DIR_CSS . DS . 'filestatic.min.css',
	DIR_CSS . DS . 'pretty-checkbox.min.css',
	DIR_CSS . DS . 'jquery.webui-popover.min.css',
	DIR_CSS . DS . 'sweetalert2.min.css',
	DIR_CSS . DS . 'jquery.inputTags.css',
	DIR_CSS . DS . 'select2.min.css',
	DIR_CSS . DS . 'selectize.css',
	DIR_CSS . DS . 'images-grid.css',
	DIR_CSS . DS . 'bootstrap-multiselect.css',
	DIR_CSS . DS . 'fancybox.css',
	DIR_CSS . DS . 'datatables.bootstrap5.css',
	DIR_CSS . DS . 'responsive.bootstrap5.css',
	DIR_CSS . DS . 'buttons.bootstrap5.css',
	DIR_CSS . DS . 'slick.css',
	ABSPATH . DS . 'cropper/cropper.css',
	DIR_VENDOR . DS . 'libs/perfect-scrollbar/perfect-scrollbar.css',
	DIR_VENDOR . DS . 'libs/apex-charts/apex-charts.css',
	DIR_JS . DS . 'alertify/alertify.core.css',
	DIR_JS . DS . 'alertify/alertify.default.css',
	DIR_JS . DS . 'redactor/redactor.css',
	DIR_JS . DS . 'leaflet/leaflet.css',
	DIR_JS . DS . 'leaflet/leaflet.draw.css',
	DIR_JS . DS . 'leaflet/leaflet-routing-machine.css',
];
// Hàm để minify CSS
function minifyCSS($css) {
    // Loại bỏ comment
    $css = preg_replace('!/\*.*?\*/!s', '', $css);
    // Loại bỏ khoảng trắng thừa
    $css = preg_replace('/\s*([{};,:])\s*/', '$1', $css);
    // Loại bỏ dòng trống và khoảng trắng đầu/cuối
    $css = preg_replace('/\s+/', ' ', $css);
    return trim($css);
}
// Đọc và gộp nội dung từ các file CSS
$minifiedCSS = '';
foreach ($cssFiles as $file) {
    if (file_exists($file)) {
        $minifiedCSS .= file_get_contents($file);
    }
}
// var_dump($minifiedCSS); die();
// Minify CSS
$minifiedCSS = minifyCSS($minifiedCSS);
// Lưu vào file mới
file_put_contents(DIR_CSS.DS.'ca.min.css', $minifiedCSS);
?>