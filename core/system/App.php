<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the ISOCMS                         # ||
|| # ISOCMS 6.0.0 VietISO Techical Team (vanthiembui.it@gmail.com)    # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 VietISO JSC.             # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||
|| #################################################################### ||
\*======================================================================*/
class App{
	var $_HTTP_HOST;
	var $_REMOTE_ADDR;//IP
	var $_REMOTE_PORT;
	var $_SERVER_NAME;
	var $_SERVER_PORT;
	var $_SERVER_SIGNATURE;
	var $_CONFIG 		= 	array();
	var $_USER			= 	array();
	var $_SESS			=	"";
	var $_PERMISS		=	array();
	var $_isAdmin		=	"";
	var $_isMaster		=	"";
	var $_isAjax		=	false;
	var $_copyright 	=	_copyright;
	var $_SiteTemplate 	=	'';
	var $_MsgTemplate 	= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<title>%title</title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<meta name="robots" content="noindex,nofollow" />
			<style type="text/css">
				h1{font-size:18px;font-family:Arial,sans-serif;color:#294a87;padding-bottom:10px;border-bottom:1px dashed #ccc;margin-bottom:30px}
				h2{font-size:16px;font-family:Arial,sans-serif;color:#000}
				.errorBox{margin:15px auto 0 auto;padding:10px;width:90%;border:1px solid #a89824;font-size:14px;background-color:#eee7b0;text-align:left;color:#706518}
				body{margin:50px;background-color:#f4f4f4;background-repeat:repeat-x}
				body,td,th{font-family:Tahoma,Arial,Helvetica,sans-serif;font-size:12px;color:#333}
				a,a:visited{color:#006;text-decoration:underline}
				a:hover{text-decoration:none}
				#login_container{color:#333;background-color:#fff;text-align:left;width:410px;padding:10px;margin:0 auto 10px auto;-moz-border-radius:10px;-webkit-border-radius:10px;-o-border-radius:10px;border-radius:10px;line-height:20px;}
				#login_container #login{text-align:left;margin:0;padding:20px 10px 20px 10px}
				#login_container #login_msg{background-color:#faf4b8;text-align:center;padding:10px;margin:0 0 1px 0;-moz-border-radius:10px;-webkit-border-radius:10px;-o-border-radius:10px;border-radius:10px}
				#login_container #extra_info{background-color:#d3d3d3;text-align:left;padding:10px;margin:1px 0 0 0;-moz-border-radius:10px;-webkit-border-radius:10px;-o-border-radius:10px;border-radius:10px}
			</style>
		</head>
		<body>
			<div id="login_container">
				<div id="login_msg">
					<span style="font-size: 14px;"><strong>%msg</strong></span>
				</div>
				<div id="login">
					<div class="alert alert-danger">%body</div>
				</div>
			</div>
		</body>
	</html>';
	function __construct(){
		$this->_HTTP_HOST 			= $_SERVER['HTTP_HOST'];
		$this->_REMOTE_ADDR 		= $_SERVER['REMOTE_ADDR'];
		$this->_REMOTE_PORT 		= $_SERVER['REMOTE_PORT'];
		$this->_SERVER_NAME 		= $_SERVER['SERVER_NAME'];
		$this->_SERVER_PORT 		= $_SERVER['SERVER_PORT'];
		$this->_SERVER_SIGNATURE 	= $_SERVER['SERVER_SIGNATURE'];
	}
	function _sprintf($title, $msg, $body){
		$html = $this->_MsgTemplate;
		$html = str_replace('%title', $title, $html);
		$html = str_replace('%msg', $msg, $html);
		$html = str_replace('%body', $body, $html);
		return $html;
	}
	function _check_filetime(){
		$files[] = ABSPATH . DS . 'init.php';
		$files[] = ABSPATH . DS . 'index.php';
		if(@file_exists(ABSPATH . DS . 'setting.php')){
			$files[] = ABSPATH . DS . 'setting.php';
		}
		$files[] = DIR_INCLUDES . DS . 'core.php';
		$files[] = DIR_INCLUDES . DS . 'index.php';
		getDirectory(DIR_COMMON, '/\.php/', $files);
		getDirectory(DIR_MODULES, '/\.php/', $files);
		getDirectory(DIR_CLASSES, '/\.php/', $files);
		$limit_timer = defined('core_lifetime') 
			? core_lifetime 
			: strtotime('01-01-1970');
		if(!empty($files)){
			foreach($files as $file){
				$file_time = filemtime($file);
				if($file_time > $limit_timer){
					$html = $this->_sprintf(
						'Lỗi hệ thống !',
						'Đã xảy ra lỗi trong quá trình khởi tạo',
						'<strong>Có ai đó đang cố gắng thay đổi cấu trúc hệ thống...</strong>
						<ul>
							<li>Điều này là không được phép và cần</li>
							<li>...liên hệ với nhà phát triển để khôi phục lại hệ thống</li>
						</ul>');
					print_r($html); die();
				};
			}
		}
	}
	function makeIcon($icon, $text=null, $cls='fa', $pos='left'){
		if(is_null($text)){
			return sprintf('<i class="%s %s-%s"></i>',$cls, $cls, $icon);
		}else{
			if($pos=='left')
				return sprintf('<i class="%s %s-%s"></i> <span>%s</span>',$cls, $cls, $icon, $text);
			return sprintf('%s <i class="%s %s-%s"></i>',$text, $cls, $cls, $icon);
		}
	}
	function getScript($mod='home', $act='default', $filetype='js'){
		$scriptFile = '';
		$filetype = preg_replace('/\./', '', $filetype);
		if(strtolower($filetype)=='js'){
			if(file_exists(DIR_VIEWS.DS.$mod.DS.'js'.DS.'jquery.'.$mod.'.js')){
				$scriptFile = URL_VIEWS.DS.$mod.DS.'js'.DS.'jquery.'.$mod.'.js';
				echo '<script type="text/javascript" src="'.$scriptFile.'?v='.time().'" defer></script>';
			}
			if(file_exists(DIR_VIEWS.DS.$mod.DS.'js'.DS.'jquery.'.$mod.'.'.$act.'.js')){
				$scriptFile = URL_VIEWS.DS.$mod.DS.'js'.DS.'jquery.'.$mod.'.'.$act.'.js';
				echo '<script type="text/javascript" src="'.$scriptFile.'?v='.time().'" defer></script>';
			}
		}else if(strtolower($filetype)==='css'){
			if(file_exists(DIR_VIEWS.DS.$mod.DS.'css'.DS.'stylesheet.'.$mod.'.css')){
				$scriptFile = URL_VIEWS.DS.$mod.DS.'css'.DS.'stylesheet.'.$mod.'.css';
				echo '<link rel="stylesheet" type="text/css" href="'.$scriptFile.'?v='.time().'" media="all" />';
			}
			if(file_exists(DIR_VIEWS)){
				$scriptFile = URL_VIEWS.DS.$mod.DS.'css'.DS.'stylesheet.'.$mod.'.'.$act.'.css';
				echo '<link rel="stylesheet" type="text/css" href="'.$scriptFile.'?v='.time().'" media="all" />';
			}
		}
	}
	//get Block
	function getBlock($block_name="default", $args=array()){
		global $smarty, $assign_list,$clsISO;
		if(IS_ADMIN_PAGE){
			$file_block_name = DIR_MODULES."/blocks/".$block_name.".php";
			$file_block_temp = DIR_VIEWS."/blocks/".$block_name.".tpl";
		} else {
			$file_block_name = DIR_APPLICATION."/blocks/".$block_name."/index.php";
			$file_block_temp = DIR_APPLICATION."/blocks/".$block_name."/index.tpl";
		}
		$html = "";
		if (file_exists($file_block_name)){
			if(!empty($args)){
				extract($args);
				foreach($args as $k => $v){
					$smarty->assign($k, $v);
				}
			}
			include($file_block_name);
			$html = $smarty->fetch($file_block_temp);
		}else{
			$html = 'Not find block: #'.$block_name;
		}
		return $html;
	}
	function build($template_name, $variables = array()){
		global $smarty, $mod, $act, $dbconn, $_LANG_ID, $assign_list;
		if($variables) {
            foreach($variables as $key => $value) {
                $smarty->assign($key, $value);
            }
        }
        $extension = pathinfo($template_name, PATHINFO_EXTENSION);
        if(!$extension){
            $extension = 'tpl';
        }
        if(!file_exists(DIR_VIEWS.DS.$mod.DS.$template_name)) {
            $output = 'Error 404. File '.DIR_VIEWS.DS.$mod.DS.$template_name.' does not exists !';
        }
        $output = '';
        switch($extension){
            case 'php':
                ob_start();
                extract($this->variables);
                include DIR_MODULES.DS.$mod.DS.$template_name;
                $output = ob_get_clean();
                break;
			case 'tpl':
				$smarty->debugging = SMARTY_DEBUG;
				$smarty->template_dir = DIR_TEMPLATES;
				$smarty->compile_dir = DIR_TEMPLATES_C;
				$smarty->config_dir = DIR_INCLUDES."/conf";
				$smarty->config_overwrite = true;
				$smarty->force_compile = true;
				if(defined("cache_lifetime")){
					$smarty->cache_lifetime = cache_lifetime;
				}
				//assign variables
                foreach($assign_list as $key => $val) {
                    $smarty->assign($key, $val);
                }
                $output = $smarty->fetch(DIR_VIEWS.DS.$mod.DS.$template_name, time()); 
                break;
            default:
                $output = 'Error 404. Unsupported file type!';
        }
        return $output;
    }
	function loadModule($module, $action='default', $variables = array()){
		global $smarty, $assign_list;
		$sub = isset($_GET['sub']) ? $_GET['sub'] : 'module';
		if($module=='' && isset($_GET['mod'])){
			$module = trim($_GET['mod']);
		}
		$file_module_name = DIR_MODULES."/".$module."/sub_".$sub.".php";
		$html = '';
		if (file_exists($file_module_name)){
			if($variables) {
				foreach($variables as $key => $value) {
					$smarty->assign($key, $value);
				}
			}
			require_once($file_module_name);
			$funcdef = $sub."_default";
			$func = $sub."_".$action;
			if (function_exists($func)){
				//call function sub_act()
				$func();
			}else{
				if (function_exists($funcdef)){
					//call function sub_default()
					$funcdef();
				}else{
					$html = 'Error 404. SubModule is not found!';
				}
			}
			#
			$file_module_html = DIR_VIEWS."/".$module."/mod_".$action.".tpl";
			if($this->template_exists($file_module_html)){
				foreach($assign_list as $key => $val) {
					$smarty->assign($key, $val);
				}
				$html = $smarty->fetch($file_module_html, md5($file_module_html));
			}else{
				$html = 'Error 404. Not found module template !';
			}
		}
		else{
			$html = 'Error 404. SubModule is not found!';
		}
		return $html;
	}
	
	function genLicenseModule($mod){
		$gen = md5($_SERVER['SERVER_ADDR'].'-VIETISO-LICENSE-PIE-'.DB_NAME.'-'.$mod);
		return $gen; 
	}
	function checkDefaultModule($mod){
		if($mod=='login'
		||$mod=='license'
		||$mod=='home'
		||$mod=='module'
		||$mod=='editor'
		||$mod=='user'
		||$mod=='usergroup'
		||$mod=='setting'
		||$mod=='lang'
		||$mod=='lang_front'
		||$mod=='adminbutton'
		||$mod=='ajax'){
			return 1; 
		}
		return 0;
	}
	function checkActiveModule($module_name){
		global $dbconn;
		if($this->checkDefaultModule($module_name)==1) return 1;
		$tmp = $dbconn->GetAll("select * from ".DB_PREFIX."configuration 
			where setting='ActiveModules' and value like '%|".$module_name."|%'");
		if(!empty($tmp)) 
			return 1;
		return 0;
	}
	function validateLicenseModule($mod){
		if($this->checkDefaultModule($mod)==1) return 1;
		$html = '<!DOCTYPE html> <html lang=en> <meta charset=utf-8> <title>Error!!</title> <style> *{margin:0;padding:0}html,code{font:15px/22px arial,sans-serif}html,b,i{color:#222}html{background:#fff;padding:15px}body{background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAKsAAADVCAMAAAAfHvCaAAAAGFBMVEVYn%2BH%2F%2F%2F%2Bex%2B3U5vd7s%2Bfq8%2Fs0itq72PMLUPvtAAASvklEQVR4AbXBC0JqCQxEwT5Jd7L%2FHc8FdR4g%2BEGtEr8u%2FBHxu7otdzd%2FQPyqlmRp1Pw%2B8aukDfRa1fw28ZtWy4sa89vEb7LCi0zx28RvqgkvouW3id%2FU8pbtWmv5beJXRWNrRmp%2BnfhlHXZm%2BQPi95Vk%2FoD4fZbMHxC%2FryTzB8Tva435A%2BL3rcb8AfH7VjJ%2FQPy%2BHYk%2FIH5facwfEL8iaZcrnKyn%2BAPi57K2VL2WF1hJ%2FAHxQ2tJrg6HteXVjPkD4ge6V3J1%2BF97zhx%2BnXhWb8nacKXlnYPErxNPyfqw4ZYKVuUZdfhd4hmxunY73NICgfWMOvwm8ZQ1pMvlDZdaCic98kjV4beIp8ScpLvsSvhflzqQmqVLB281v0E8pc2bdNne8EayNTPNSbt02PBj4intcKltb%2FNibY%2BLf9aSO%2FyMeMo6XMva3g0vwrWsxvyMeEoc3knZ2g53ZaXa8DzxlHa4J23Jae5aycXTxFPa4WRdXAtdsivckZXG4TniKWtOSlre6y7LG651Wxq5OzxDPGUVIKNwX6ekCv%2B0ddglVPMM8ZQ10FJ4LGVvOEuXRl7OqnmGeEor4Ck%2BtnI1ZEvjDa%2FcPEM8ZQVY4RO9VqUlN%2F84PEM8JQ50cUgXH2mrKlyq5RniOQ4vVjPLHdu86OKGi2eIr%2BgNV6JwljmYO6zlbJsbWp4hPtVrjYpLLV7UHIp7rOVkixtaniE%2BU5I2Nc2FKJytZhTuiac5rLnh4hniEzUbDjXhn3g5W0nNA1aAKm7YPEN8bMecrZYLWl70hkcyBay5YfMM8aHI4aR7xAUVHyirOdhAmRsqniE%2BtOKsRjIXtDzmmRGHVmDFDRfPEB%2BJzMmO01xScdYnVRs6vPHMFG9W4ZrMM8RHouWw43DNhlDWiSVZY3nDoWYc3qzDNZlniPe6w4uoOFjcKhPXuJNWyG6VqjSuhm7%2BiZorUfEM8U5J8nKyMw0tcZLwPxdRtTlUcUgVdGlml0uZ4pqKZ4hr5VUnpSXdUgVa4hA5vHERV1Tp9XhdJTWHksYd%2Ftdarql4hrjQiaPiYLclNSeebVYz5o0W7Ghsa9blmlFtx01rxP8yy5XIPEP8L1W7bjWHlbzhRTwjzXrCK1f3qqSEyBysLVtayKp40yqurcITxJtUgavVHNob%2FinZTWt5VVvWVKvJSttQCkRjb%2FA4vLK5thOeIN6sm9ai5cTFhYRDy%2FyTGpdU0hxkaZvWUrZluTmLims14QniVbywClqgeouT9IZXNWoupGzNqHa3y5LGVYBnipbCSVxcq1meIN54oRXsbEk26S3NmBcZ807K3gon2ZLcxF5tPMVJprlWE54g3nihtbRHm7WjkbxTHSCWwj1r2U4HSMmdQEmWwonNtah4gnhjA9ZSaohmpnpDjWRptDwS25LcQGsc2Bla5sTFtZV4gnixpWmIVWpgRuVwsiV5q7kv0JJcNVIFapydUrHTQKa5IfMEcRKrurSQ0qhsmVR4kea%2B7pIr9NqSrRltWlaxomUgVVyLxBPEYeUGygtszew2KfOBclVpVN2ctCXNidZaaKWmONhc6rKaJwi6xuGkRmWpAkRa7outF9XN%2F7LlmbJmpiCyvBxk%2FtnSqHmGWGk5i2ZcaWBLau5KKHt3Ce%2FsaLMz46VG4cTFm%2FaMOzxFUYWztjzhkNI43JPyYvPAegPxzFRpOYmWF1WywrPUag5xjRapqqxxubijvYFVaC%2Fv7YSDpzxjzlbhpKXxhqcpWshqtECk0Yys6m5utZdD1LCuCifhfyVOapqsxhyiQMmSm58QNdZheZGV5FqwueXiZBUga28DvRte1NQCpQVSUkFqPbIr%2FIxg7arwJqqEg6e5Vuas1Zytyw1ka5uT9ajKI87WbksaLT8mbkXFyWqaa2rOVuFVStUNpGrDoSTPmDfWdlby8kPiHQtoa0vLpXU4WzX%2FS5W2gWxtOHQ24U3CSUmu8BPinR2XVSFyuNAOZ9Fyae1qDu2qcF8suRKeJt7pcW1zaE9xwcVZq7nWtpeTrQ0PrEeq8CTxnsWrlbThELra5ixqbsXWNoeq6nBft6TlOeK9VnG2lfb4TKOOlpOouKPsWg4pb3Nf1uMGusP3iDtKDaTcgMuWvL1FmZOouCtlbwJs1Yb7SuN2Nd8k7mgvXV4OKWALiGkVJ14eyPqQQG9Vc0dWGnn5LnFPTW1z1gW0OdSyag5aHsvaroVs1YZL2dKMt1nzXeKulas52QLanGy3xq4a87Eu2yHZ2uZNWzPjDbDmu8R9a8m7iQNscbKyy%2BWS%2BUzWtqp7qzpA1jPj8KKK7xIPZG2NVWTTSbpKbs5cfEF6y64qV6ctqcKbdvgm8VhSlnWwJbuaV3LzRb11onFt%2BKcVvkl8one7u3bD%2FzJuXnRt%2BFTXVHOWqubQ4rvEEyI1L1Z2h8%2B0eRHLKiBqvkk8IePmxZq1lk%2B0w0nJUHKIlm8ST8ioeVEFtFwbPhA3h8gcdpZV803iCRkVL7Y42bK2w0NlDqXlpJRV803iGZYrnFRxlqwO3eEuN4dSOGlVme8Sz7C37QZqeZPekl0b3nMBreKsp1bNN4lnWIEtF1Vc6i1bVZtwxQX0NC9UrfBN4hk7zaHLNrey1kgVLljATnO2rmj5JvEMqzlrF%2B%2BFXitcsAArnFkdLd8knrFqPmFzyQq0xUm0tJZvEs8oAR0eix0u1ARSqg70NNHyTeIZUqgZ85gdLlgcMjOSRlBqvkk8wwOSp3moJlyoCYfeKkmBVvgm8YyaUJJ5zOJSTXMWSgus%2BC7xjJpA%2BMiquVATXiUcSuGbxDNqmk%2BUxtW82WmurMI3iWd4wifaHo1rNxx2miul8E3iGTXhc4nH0lQ1O80VK3yTeEYNX5SspbEnXFmFbxLPqGm%2BrsvWFFdK4ZvEM2rCt6RmzCWL7xLP2Anfs2M3Fyy%2BSzyjpvmqDoed5YrFd4ln7DRftHI19BRXSuGbxDN6wtdEqjF4lisS3yWeEYUvWlkDNeZKTfgm8ZFu7mqFr%2FKMYae4lFH4JvGBVLgraghf09uQMZdabr5JfKC2q1zV3IgarOLLPMWllptvEo%2B1e7dkq5ZrLkip%2BKqa4lLk5ZvEY15INay9XIqXVGS%2BqsdcirzclYVa7hAPbQFVnJSaC9HCapavqjGXIjXvbNmSxi7eE4%2BsA21OumwuSQUJX1ZjLsVabqR6t7tUlrThhnjEC%2FFy6AKbCy45zdftmEutKm5UcSgHspY7XBEPVAFVHLoCUXPFkr3hi2wutba44QDr5iyeqQ3%2FiAccqOLQDhAV17pG0jZfUuZS5OJaGYiWF%2B2ypOV%2F4q5UQZtDu4G2xK10aeTlC1bhUslciQpYh7PSQtau8ErcVYZ4gXYDcUXLe1lrvBU%2B0VoutFRcWQWo4qwdTlYSr8Q9caDMwc3BDgl3xZpRb%2FORnuVCJHNlla2oOYmLQ8q7Ll6Ie6pgDaQKSCl8IF3WqAgPrbgU2VxpV1kje2EdoOWGlsOJuKMd1g14OdjNp1YjNY%2B0m0s15kYgJVlaFxBVOETuAOK9eEELrDmUli%2Fo8oy94S4Xl2LzQGukEFU46RptQLy3BWWgHSBTvEp32eGRtjTjSriQBKLlShUPrSRcnK2qtIB4Zw3tQNRAbF5FB0vhoS57JFXzZmUtuLiy5gNlTTixlkgB8Y4byhAX0HJ4Y%2FcmWkjz0NrSaMNJ5EiNi3%2FSpPlIayqA3UBcIG5tQTuwBcQOJx3AsrSzxHJ4bKs9U5xoqWnK4U17%2BUzPFLQ4iQ3iRtxQC3gBK5xZJjOutcaSpeYjsUZqKFmGOLxIaflU1jI2ZzuLuLGuLe2yBlrLC1tdWg7ZmWal8KHeGtXG0gLLSdZyha%2BoKYdDl7WIGxpbI7lSicyLqFkH2rVZF%2BwUnymNXNu8WUkVLqSaB6IpIGWXF3Ft1UC6rRq3mhc7TRXgLS2lrKb5VEoz6nCSrtE2V6p4aMeQ8tJaxLU4nGU9o%2BXVTrMF%2BLBgjYqvSNkjL%2BDxhmut5tDb3CF1uwJoEdday6vMTHjVs7GA3g3QU8tXxZJc6Q23yhxWckPCtZW1nLgQ12KFF5Ed3pQ0U7yKp%2Fi6YM%2FI4dZOA3FRRdvhSmaWMxtxI3JzVlP8k9qsVFWdbVvTfENCjcytUoBW46XscE3DizLi1o6KQ4%2FDlZRsWSfBCt%2BSdHGrzGHFOtjFtUgNNJQR78Qjr%2BVwzV4I65SazPJzrQbKq6bl5kapU7bbRryXLo3c3LATYIfMEs3yc1bA44bScqumvJ21jLgrhHdSktNWkONR%2BLmULMnbpQm3pOWkZxHf0R7NKKykDr9iq3ptuexOuJQRZ5lCfE96K5Ct5iNpe118WQKxVeGCxnYDmUL8iUjb2%2BXmexIu9Di9XtgpxJ9wcehuOzwt1gJx4ynEM9K9tS5X7fLempP2dmnDczwjTlLYi%2FiCnHXSe9LWic9k3qvlRTltLU%2Bp2lE1sKUG8bm2DiNpNBpJu5vwwuEdLa%2FWy6p4JL27Dg%2B0pUBsQHxu67C1Vb2dpLlU5h3bG87aS0vNXWtJtip0bbjDhqgB8TkvH1g115qttnfDoW0oNe%2B1Rs0hlqVRc8cSmYP4XBUfUXHNlQ5tqzkpNaXmHVV4lVpq1NxjhYP43JqP2FwracOh7OZQDuXmRmu5sjMO75SWE%2FE5F4%2F09s5wI5abQ0rFoVxZNTes7e7wvy053NpwJj7n4kVCDt29teWypJHFOy0VJ6sN0CrK4dpakmv5pxQeEZ8rQ%2B9alnU2knyo2k64Ix4vh5I5sVNarqW3u8z%2F4mkeEZ8LrCxXtbfWu9t8qqQK0DKHVtEubrWm%2BZ9VPCS%2BJN1828oB4gqwalrFtUjNP3bzkPg7sdXAyhyssF4upWb5Z8c8Jv5QWmpgVRxUsGoulMw%2FPQqPiZ%2Fp8JGVOLQWKAW6%2BCcyF2qGD4gfibe2ead5lXEDpQAu0rv8r2WgtZxl1Twm%2Ftls1HxHK7HDjZV51VIgWmBlSeMKr%2BxseZYXq%2BUx8aY0MxrvVnUC4XNxgYtrJY15taMmNlAztd0lhxfW6MChC1rFY%2BLVjlwzKutVdfhE7xjKXEiX3CuHFzWG0lLycogUXnTtxuaws6DiMfFK09kZQ9K1VSvJ3oRHslIFWuGftdzQUoWzlYONinBILRdaC8TTYPO%2F3nBFnKxLG2um%2BKfXOrg6vBdLrvJSCm9SJpy0RtucrMRq1Zy1woUy0B4HbN60ex0uiEN0KLk1xZXs2paKW9FIqrJrzP%2Fs5k17tJz0GE%2FxohwulGElOUTmTRWl5oI4lKRRsTPhVpIdc6sl10IsFW9WXNpROPH0TkGAVnFpx5a63WSKN5HVXBKwc1btEffsNO8kvBObS5lZTnaUMXFYqbnUltwg75h%2FusMVATXleWW7qk1Xb8KLVfiKlsIlj9Sc1FhFtjITboSTlSp8QMCO5JU11bb1ZlQdIHL4iprmktWROclqGlaWmvsSPiRAIy3lcAhk05vsWgfbU3xFVFyRWTUvSqqa2S7zHEFmRikt7yS18kxxFj6yY67UbNu86U6qIApPEUSasZb7Ek0DqXh5LHa4lDFWc6kd4uUpgsiaKR6pKQ61uHmsZrmyk1ZxpQ1oeYoAzaG4ry1zsuXisVJxpeQdc60N2DxFgGckc1ePixdpHkjVjrnS0kpc6u5SwMtTBKxkybUJN3bUfCaulsMVTVvNP%2BmyNQVe7tjlE%2BJFb1mSLVfV9jaHHS2fiao15sqOd4pL29ArbxXvldV8TPwv6XVV6YXtGTefiiqaMRei2TFXKpzUONxKFWo%2BJt5J0ltlzQxfsCqimSpv86KmrHApBbXA2s2NuKPwMfFQvOELWgvsnEjVQMYtc2UXqjm0xI0yq%2FAx8T0JtyJz8DiekWpjxWoupRqqOamp5VJPsXJt9256wz3iW8oOt1xNaWah3NZJZK7UAg6HLo%2B5tFPgke2SreUe8R1rO9xayTpALFaa2Z3mUhyo4qQ6I67MbLlsyyfFPeI71m7ey0orw2pL256WuFILVHOI41mu1IyK3u0q28094nvCXQHLtqyF9Gq5tA7E4bAViRsrNW%2FCXeK3lDTVVoBI4ZIDVHFYpbTcyIbPiF%2FTSbPT3SUtl6qAuDl4W8UzxC%2Fz6CRciALUcijT4inil%2FV2p4pLtUDcwCol8xTxF8KlKg5VQGtb4jniz7UbWAcox%2BJJ4s%2B5OLiAVnuKJ4m%2FtuawBURbszxL%2FLF4OXgh9s7yNPHHqjisgVLLPE%2F8rXYD7UCrVsXzxN%2Bq4uAGrFj8gPhTXRzKwGprmh8Qf2rlot2AvSp%2BQvyl1nikAlprh58Qf0lqolGBarX8iPhLZWBVqnVsfkb8pTaHcru61PyM%2BEtrDq2UW8sPib%2FUChBvbIcfEn%2FKxWGrpeWnxJ9qVYDyVPgp8bfa2qRmmh8Tf21lq5qfE38uveE3%2FAdr385%2FSVd%2FMAAAAABJRU5ErkJggg%3D%3D) 100% 5px no-repeat;margin:7% auto 0;max-width:390px;min-height:180px;padding:30px 0 15px}* > body{padding-right:205px}p{color:#777;margin:22px 0 0;overflow:hidden}a.logo{background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMUAAAAxCAYAAABqO23/AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjM3QkZEN0EzNEJCODExRTBCRDk4OUY4NzUyOUI0NjFBIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjM3QkZEN0E0NEJCODExRTBCRDk4OUY4NzUyOUI0NjFBIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6MzdCRkQ3QTE0QkI4MTFFMEJEOTg5Rjg3NTI5QjQ2MUEiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6MzdCRkQ3QTI0QkI4MTFFMEJEOTg5Rjg3NTI5QjQ2MUEiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4suTVMAAALNUlEQVR42uxdO2wbyRle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%3D%3D);display:block;height:49px;margin:0 0 -7px;text-decoration:none;width:197px;}* > a{margin-left:-2px}a img{border:0;visibility:hidden}i{font-style:normal}* html a img{visibility:visible}*+html a img{visibility:visible} </style><p><b>Error:</b> Invalid Or Expired License. <p><i>Your license is expired! </i> <a style="color:#F60; text-decoration:none;" href="http://www.vietiso.com/invalid-license.html" />Click here</a>  to see more about this. ';
		$dir = PCMS_DIR."/isocms/modules";
		if(file_exists($dir."/".$mod."/mod.ini.php")){
			include_once($dir."/".$mod."/mod.ini.php");
			if(isset($_MOD_INI['Info']['License_Key'])){
				$credits = $_MOD_INI['Info']['License_Key'];
			} else{
				$credits = '';
			}
		}
		$validcredit = $this->genLicenseModule($mod);
		if($credits!=$validcredit){
			if($this->_SITE_ROOT=='ADMIN'){
				//header('location: '.PCMS_URL.'/?message=invalidlicense');
			}
		}
	}
	/** Check Permission */
	function checkAccess($mod){
		$clsUser = new User(); 
		return $this->checkPermission($this->_SESS->user_id, $mod); 
	}
	function checkPermissionUser($user_id, $mod){
		$clsUser = new User();
		return $this->checkPermission($user_id, $mod);
	}
	function checkPermission($user_id, $mod){
		$mod = strtolower($mod);
		if($mod=='home' or $mod=='ajax' or $mod=='login' or $mod=='editor'){return 1;}		
		if(!file_exists(DIR_MODULES."/$mod/mod.ini.php")){
			return 0; 
		}
		if($this->checkActiveModule($mod)==0) return 0;
		if($user_id==1){ return 1;}
		#
		$clsUser = new User();
		$permiss_mod = $clsUser->getOneField('permiss_mod',$user_id);
		$tmp = !empty($permiss_mod) ? @json_decode($permiss_mod, true) : array();
		if(!empty($permiss_mod))
			return in_array($mod,$tmp);
		return 0;
	}
	/** End Permission */
	function is_empty($arr, $field){
		if (isset($arr[$field]) && !empty($arr[$field]))
			return 1;
		return 0;
	}
	function get_field($arr, $field, $def=null){
		if (isset($arr[$field]) && !empty($arr[$field]))
			return $arr[$field];
		return $def;
	}
	function get_price_field($arr, $field, $def=0){
		global $clsISO;
		if (isset($arr[$field]) && !empty($arr[$field])){
			if(!empty($arr["min"]) || !empty($arr["max"])) {
				return $clsISO->convertPriceShortToFullUpdate(trim($arr[$field]),$arr["min"],$arr["max"]);
			}
			return $clsISO->convertPriceShortToFullUpdate(trim($arr[$field]));
		}
			
		return $def;
	}
	function get_number_field($arr, $field, $def=0){
		global $core, $clsISO;
		if (isset($arr[$field]) && !empty($arr[$field]))
			return $clsISO->toNumber(trim($arr[$field]));
		return $def;
	}
	function get_money_field($arr, $field, $def=0){
		global $core, $clsISO;
		if (isset($arr[$field]) && !empty($arr[$field]))
			return $clsISO->processSmartNumber(trim($arr[$field]));
		return $def;
	}
	/** download file trên google driver khi có được google id của file (gid) */
	function downloadFileFromGoogleDrive($fileId, $savePath) {
		$url = "https://drive.google.com/uc?id={$fileId}&export=download";

		$ch = curl_init($url);
		$fp = fopen($savePath, 'w+');
		
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);

		$success = curl_exec($ch);
		if (!$success) {
			echo 'Lỗi tải file: ' . curl_error($ch) . PHP_EOL;
		}
		curl_close($ch);
		fclose($fp);
		
		return $success;
	}
    function validateArrayFieldsLatin($arr = []) {
        foreach ($arr as $field) {
            if (!preg_match('/^[A-Za-z0-9_]+$/', $field)) {
                return false;
            }
        }
        return true; // tất cả hợp lệ
    }
}
class User extends DbBasic{
	function __construct(){
		$this->pkey = "user_id";
		$this->tbl = DB_PREFIX."user";	
	}
	function encrypt($password){
		return doEncrypt($password);
	} 
	function getEmail($user_id){
		global $core;
		if($core->_USER['user_id'] == $user_id){
			return $core->_USER['email'];
		} else {
			return $this->getOneField('email', $user_id);
		}
	}
	function getFullName($user_id){
		global $core;
		if($core->_USER['user_id'] == $user_id){
			return $core->_USER['first_name'] . " " .$core->_USER['last_name'] ;
		} else {
			$one = $this->getOne($user_id, "first_name,last_name");
			return $one["first_name"]. " ". $one["last_name"];
		}
	}
}
class UserGroup extends dbBasic{
	function __construct(){
		$this->pkey = "user_group_id";
		$this->tbl = DB_PREFIX."user_group";
	}
	function getName($user_group_id){
		global $_LANG_ID;
		$one = $this->getOne($user_group_id);
		return ucfirst($one['name']);
	}
}