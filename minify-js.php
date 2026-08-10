<?php 
	ini_set('dispay_errors', 1);
	error_reporting(E_ALL ^ E_ERROR);
	
	define('DS', DIRECTORY_SEPARATOR);
	define('ABSPATH', dirname(__FILE__));
	define("DIR_APPLICATION", ABSPATH."/application");
	define("DIR_THEMES", DIR_APPLICATION."/themes");
	define("DIR_VENDOR", DIR_THEMES."/vendor");
	define("DIR_CSS", DIR_THEMES."/css");
	define("DIR_JS", DIR_THEMES."/js");
	
	$jsFiles = [
		DIR_JS . DS . 'jquery-migrate-1.2.1.min.js',
		DIR_JS . DS . 'underscore-min.js',
		DIR_JS . DS . 'jquery-ui.1.11.0.min.js',
		DIR_JS . DS . 'freeze-table.js',
		DIR_JS . DS . 'redactor/redactor.js',
		DIR_JS . DS . 'select2.full.min.js',
		DIR_JS . DS . 'selectize.js',
		DIR_JS . DS . 'alertify/alertify.min.js',
		ABSPATH . DS . 'cropper/cropper.min.js',
		DIR_JS . DS . 'canvasjs.min.js',
		DIR_JS . DS . 'emojiPicker.js',
	];
	$minifiedJS = '';
	foreach ($jsFiles as $file) {
		if (file_exists($file)) {
			$minifiedJS .= @file_get_contents($file);
		}
	}
	function minifyJS($jsCode) {
		if(1==2){
			$url = 'https://closure-compiler.appspot.com/compile';
			// Dữ liệu gửi đến API
			$postData = [
				'js_code' => $jsCode,
				'compilation_level' => 'SIMPLE_OPTIMIZATIONS', // Có thể đổi thành ADVANCED_OPTIMIZATIONS hoặc WHITESPACE_ONLY
				'output_format' => 'text',
				'output_info' => 'compiled_code'
			];
			// Khởi tạo cURL
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			  // Debug response
			curl_setopt($ch, CURLOPT_VERBOSE, true); 
			// Gửi yêu cầu
			$response = @curl_exec($ch);
			var_dump($response); die();
			curl_close($ch);
			return $response;
		} else {
			if (trim($jsCode) === "") {
				return $jsCode;
			}
			return preg_replace(
				array(
					// Remove comment(s)
					'#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#',

					// Remove white-space(s) outside the string and regex
					'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',

					// Remove the last semicolon
					'#;+\}#',

					// Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}`
					'#([\{,])([\'])(\d+|[a-z_]\w*)\2(?=\:)#i',

					// --ibid. From `foo['bar']` to `foo.bar`
					'#([\w\)\]])\[([\'"])([a-z_]\w*)\2\]#i',

					// Replace `true` with `!0`
					'#(?<=return |[=:,\(\[])true\b#',

					// Replace `false` with `!1`
					'#(?<=return |[=:,\(\[])false\b#',

					// Clean up ...
					'#\s*(\/\*|\*\/)\s*#'
				),
				array(
					'$1',
					'$1$2',
					'}',
					'$1$3',
					'$1.$3',
					'!0',
					'!1',
					'$1'
				),
				$jsCode
			);
		}
	}
	// Minify JS
	$minifiedJS = minifyJS($minifiedJS);
	var_dump($minifiedJS); die();
	// Lưu vào file mới
	@file_put_contents(DIR_JS.DS.'minify.min.js', $minifiedJS);
	
?>