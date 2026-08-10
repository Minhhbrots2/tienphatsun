<?php
//	 ini_set('display_errors', '1'); ini_set('display_startup_errors', '1'); error_reporting(E_ALL);
	ini_set('memory_limit', '5048M');
	set_time_limit(0);
	date_default_timezone_set('Asia/Ho_Chi_Minh');
	define('DS', DIRECTORY_SEPARATOR);
	define("_SITE_ROOT", 'CRONJOB');
	define('ABSPATH', $_SERVER['DOCUMENT_ROOT']);
	define('ROOTPATH', $_SERVER['DOCUMENT_ROOT']);
	define("DIR_INCLUDES", ROOTPATH."/core");
	define("DIR_MODELS", ROOTPATH."/models");
	define("DIR_ADODB", DIR_INCLUDES."/adodb5");
	/** Required Config */
	require(ABSPATH.DS.'init.php');
	require(ABSPATH.DS.'setting.php');
	define("DIR_LANG", PCMS_DIR."/lang");
	if(!defined('LANG_DEFAULT')) define("LANG_DEFAULT",'vn');
	/** Debugging */
	define("SMARTY_DEBUG", 	false);
	define("COMPILE_CHECK", true);
	define("ADODB_DEBUG", 	false);
	define("STOP_APP_IF_ERROR", 1);
	/** DriverDatabase */
	require_once(DIR_ADODB."/adodb.inc.php");
	$dbconn =& ADONewConnection(DB_TYPE);
	$dbconn->debug = ADODB_DEBUG;
	$dbconn->Connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$dbconn->setFetchMode(ADODB_FETCH_ASSOC);
	$dbconn->Execute("SET time_zone='+7:00';");
	/** Core Requirement */
	require_once DIR_COMMON."/DbBasic.php";
	require_once DIR_COMMON."/App.php";
	require_once DIR_COMMON."/Core.php";
	require_once DIR_COMMON."/Module.php";
	require_once DIR_COMMON."/Download.php";
	require_once DIR_COMMON."/Upload.php";
	require_once DIR_COMMON."/Config.php";
	require_once DIR_COMMON."/Common.php";
	require_once DIR_INCLUDES."/curl/vendor/autoload.php";
	require_once DIR_INCLUDES."/carbon/vendor/autoload.php";
	/** ClassRequirement */
	if(function_exists('spl_autoload_register')){
		function autoload($model){
			if(file_exists(DIR_MODELS.DS.$model.'.php')){
				require_once(DIR_MODELS.DS.$model.'.php');
			} else if(file_exists(DIR_MODELS.'/class.'.$model.'.php')){
				require_once(DIR_MODELS.'/class.'.$model.'.php');
			}
		}
		spl_autoload_register('autoload');
	}
	/** Library Requirement */
	if (is_dir(DIR_LIB)){
		if ($dh = opendir(DIR_LIB)) {
			while (($file = readdir($dh)) !== false){
				if (substr($file, -3)=='php') require_once(DIR_LIB."/".$file);
			}
			closedir($dh);
		}
	}
	$core = new Core();
	global $profile_id; $profile_id = 0;
	$clsISO = new ISO();

	/* =========================================================================
	 *  CRON: Crawl tin BĐS từ RSS các báo → default_news_crawl (staging)
	 *  Gọi: https://ca.futurehomes.vn/cronjobs/crawl_news.php?key=CRON_SECRET
	 *  Lịch do user đặt (~2h, crontab/cPanel). Dedup url_hash. Lịch sự: UA + delay + cap.
	 * ========================================================================= */

	// --- Bảo vệ gọi public ---
	$key = isset($_GET['key']) ? $_GET['key'] : '';
	if(!defined('CRON_SECRET') || CRON_SECRET === '' || $key !== CRON_SECRET){
		header('HTTP/1.1 403 Forbidden');
		die('forbidden: thiếu/sai ?key (đặt hằng CRON_SECRET trong config.php)');
	}

	$is_cli = (php_sapi_name() === 'cli');
	$nl = $is_cli ? "\n" : "<br>\n";

	/** Chuẩn hoá URL để dedup ổn định (giữ host nguyên; bỏ tracking param; sort; bỏ fragment+trailing /). */
	function nx_normalize_url($url){
		$url = trim($url);
		if($url === '') return '';
		$p = @parse_url($url);
		if(empty($p['scheme']) || empty($p['host'])) return '';
		$scheme = strtolower($p['scheme']);
		if($scheme !== 'http' && $scheme !== 'https') return '';
		$host = strtolower($p['host']);
		$port = isset($p['port']) ? ':'.$p['port'] : '';
		$path = isset($p['path']) ? rtrim($p['path'], '/') : '';
		$q = '';
		if(!empty($p['query'])){
			parse_str($p['query'], $params);
			$drop = array('fbclid','gclid','ref','source','campaign');
			foreach(array_keys($params) as $k){
				$kl = strtolower($k);
				if(strpos($kl,'utm_')===0 || strpos($kl,'mc_')===0 || in_array($kl, $drop, true)) unset($params[$k]);
			}
			if(!empty($params)){ ksort($params); $q = '?'.http_build_query($params); }
		}
		return $scheme.'://'.$host.$port.$path.$q;
	}

	$clsSource = new NewsCrawlSource();
	$clsCrawl  = new NewsCrawl();
	$sources   = $clsSource->getActive();
	$grand = array('sources'=>0,'made'=>0,'skip'=>0,'blocked'=>0,'error'=>0);
	echo "[News-crawl] start ".date('Y-m-d H:i:s').$nl;

	foreach((array)$sources as $s){
		$sid = (int)$s['source_id'];
		$made = $skip = 0; $err = '';
		$grand['sources']++;
		try {
			$is_rss = ((int)$s['is_rss'] !== 0);   // mặc định RSS; is_rss=0 → quét TRANG DANH SÁCH (HTML)
			$limit = max(1, (int)$s['crawl_limit']);
			$entries = array();   // [ ['link'=>url, 'pub'=>int], ... ] — gom 2 kiểu nguồn về 1 vòng lặp
			$ferr = '';
			if($is_rss){
				$rss_url = trim($s['rss_url']);
				if(!NewsExtractor::_ssrf_safe($rss_url)) throw new Exception('rss url unsafe');
				$raw = NewsExtractor::fetch_safe($rss_url, $ferr);
				if($raw === false) throw new Exception('fetch rss: '.$ferr);
				$xml = @simplexml_load_string($raw, 'SimpleXMLElement', LIBXML_NOCDATA);
				if($xml === false || empty($xml->channel) || empty($xml->channel->item)) throw new Exception('rss parse failed');
				foreach($xml->channel->item as $it){
					$pub = !empty($it->pubDate) ? (int) strtotime((string)$it->pubDate) : 0;
					$entries[] = array('link'=>(string)$it->link, 'pub'=>$pub);
				}
			} else {
				$list_url = trim((string)$s['list_url']);
				if($list_url === '' || !NewsExtractor::_ssrf_safe($list_url)) throw new Exception('list url trống/không an toàn');
				$raw = NewsExtractor::fetch_safe($list_url, $ferr);
				if($raw === false) throw new Exception('fetch list: '.$ferr);
				$links = NewsExtractor::extract_links($raw, $list_url);
				if(empty($links)) throw new Exception('không bóc được link bài (trang JS hoặc URL sai mẫu?)');
				foreach($links as $lk){ $entries[] = array('link'=>$lk, 'pub'=>0); }
			}
			$n = 0;
			foreach($entries as $en){
				if($n >= $limit) break;
				$n++;
				try {
					$link = nx_normalize_url((string)$en['link']);
					if($link === '') continue;
					$hash = md5($link);
					if($clsCrawl->existsByHash($hash)){ $skip++; continue; }
					$opts = array('content_selector'=>$s['content_selector']);
					$r = NewsExtractor::extract($link, $opts);
					$status = !empty($r['blocked']) ? 'blocked' : (!empty($r['ok']) ? 'new' : '');
					if($status === ''){ $err = $r['error']; continue; }   // ssrf/empty/fail → bỏ item
					$pub = (int)$r['pub_date'];
					if($pub <= 0) $pub = (int)$en['pub'];
					$clsCrawl->insert(array(   // KHÔNG set crawl_id → AUTO_INCREMENT
						'source_id'=>$sid, 'source_code'=>$s['code'], 'url_hash'=>$hash, 'source_url'=>$link,
						'title'=>mb_substr((string)$r['title'], 0, 300), 'summary'=>$r['summary'], 'content'=>$r['content'],
						'images'=>json_encode($r['images'], JSON_UNESCAPED_UNICODE),
						'pub_date'=>$pub, 'cat_hint'=>(string)$s['cat_id'], 'status'=>$status,
						'reg_date'=>time(), 'upd_date'=>time()
					));
					$made++;
					if($status === 'blocked') $grand['blocked']++; else $grand['made']++;
					usleep(800000);   // 0.8s — lịch sự, chống ban IP
				} catch (Throwable $e) {   // UNIQUE dup (race) / lỗi item → nuốt, đi tiếp
					continue;
				}
			}
		} catch (Throwable $e) {           // nguồn hỏng / fatal → cô lập nguồn
			$err = substr($e->getMessage(), 0, 200);
			$grand['error']++;
		}
		$clsSource->updateOne($sid, array(
			'last_run'=>time(), 'last_status'=>($err ? 'error' : 'ok'), 'last_error'=>$err, 'upd_date'=>time()
		));
		echo "  [".$s['code']."] made={$made} skip={$skip} ".($err ? "ERR: ".$err : "ok").$nl;
		$grand['skip'] += $skip;

		// retention: dọn staging cũ (new/rejected) quá retention_days
		$rd  = max(1, (int)$s['retention_days']);
		$cut = time() - $rd * 86400;
		$dbconn->Execute("DELETE FROM `".$clsCrawl->tbl."` WHERE `source_id`=".$sid." AND `status` IN ('new','rejected') AND `reg_date` < ".$cut);
	}

	echo "[News-crawl] DONE ".json_encode($grand).$nl;
	die('DONE');
