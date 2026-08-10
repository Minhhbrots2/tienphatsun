<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # NewsExtractor — bóc tách bài báo 4 tầng (JSON-LD/OG/selector/heur) # ||
|| # Future Homes Group — feature crawl tin BĐS. KHÔNG lib ngoài.      # ||
|| # An toàn: SSRF guard + UTF-8 + sanitize XSS. Mọi method static.    # ||
|| #################################################################### ||
\*======================================================================*/
class NewsExtractor{

	const UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';

	/** Bóc 1 URL → mảng ['ok','blocked','title','summary','content','images','pub_date','tier_log','error']. */
	static function extract($url, $opts = array()){
		$out = array('ok'=>false,'blocked'=>false,'title'=>'','summary'=>'','content'=>'',
			'images'=>array(),'pub_date'=>0,'tier_log'=>array(),'error'=>'');
		$err = '';
		$html = self::fetch_safe($url, $err);
		if($html === false || $html === ''){ $out['error'] = $err ? $err : 'empty body'; return $out; }

		$dom = self::loadDom($html);
		if(!$dom){ $out['error'] = 'dom parse failed'; return $out; }
		$xp = new DOMXPath($dom);
		$base = self::_base($url);

		$t1 = self::_jsonld($xp);
		$t2 = self::_og($xp);
		$out['tier_log']['jsonld'] = $t1['hit'] ? 1 : 0;
		$out['tier_log']['og']     = $t2['hit'] ? 1 : 0;

		$out['title']    = $t1['title'] !== '' ? $t1['title'] : ($t2['title'] !== '' ? $t2['title'] : self::_node_text($xp, '//h1'));
		$out['summary']  = $t2['desc']  !== '' ? $t2['desc']  : $t1['desc'];
		$out['pub_date'] = $t1['date'] ? $t1['date'] : $t2['date'];

		// ----- content: tầng 3 selector → tầng 4 heuristic -----
		$node = null; $tier = '';
		$sel = isset($opts['content_selector']) ? trim((string)$opts['content_selector']) : '';
		if($sel !== ''){
			$q = self::css2xpath($sel);
			$nodes = $q !== '' ? @$xp->query($q) : null;
			if($nodes && $nodes->length){
				$cand = $nodes->item(0);
				if(mb_strlen(trim($cand->textContent)) >= 100){ $node = $cand; $tier = 'selector'; }
				else { $out['tier_log']['selector_empty'] = 1; }
			} else { $out['tier_log']['selector_empty'] = 1; }
		}
		if(!$node){ $node = self::_pick_main($xp); $tier = 'readability'; }
		$out['tier_log']['tier'] = $tier;

		$content_html = $node ? self::_inner_html($node) : ($t1['body'] !== '' ? $t1['body'] : '');

		// ảnh (og:image + trong body) — resolve + SSRF-safe
		$out['images'] = self::_images($node, $base, $xp, $t2['image'] !== '' ? $t2['image'] : $t1['image']);

		// sanitize content (chống XSS)
		$out['content'] = self::_sanitize_html($content_html);

		// phát hiện JS-render/paywall
		$words = self::_word_count(strip_tags($out['content']));
		$out['tier_log']['words'] = $words;
		if($words < 150){ $out['blocked'] = true; $out['error'] = 'js-render/paywall? words='.$words; }
		$out['ok'] = ($out['content'] !== '' && !$out['blocked']);
		return $out;
	}

	/* =================== FETCH (SSRF-safe, manual redirect) =================== */
	static function fetch_safe($url, &$err = ''){
		if(!class_exists('\\Curl\\Curl')){ @require_once(ROOTPATH.'/core/curl/vendor/autoload.php'); }
		$hops = 0;
		while($hops < 4){
			if(!self::_ssrf_safe($url)){ $err = 'unsafe url'; return false; }
			$curl = new \Curl\Curl();
			$curl->setUserAgent(self::UA);
			$curl->setOpt(CURLOPT_TIMEOUT, 15);
			$curl->setOpt(CURLOPT_CONNECTTIMEOUT, 8);
			$curl->setOpt(CURLOPT_FOLLOWLOCATION, false);
			$curl->setOpt(CURLOPT_MAXREDIRS, 0);
			$curl->setOpt(CURLOPT_SSL_VERIFYPEER, true);
			$curl->setOpt(CURLOPT_SSL_VERIFYHOST, 2);
			$curl->get($url);
			$code = (int)$curl->httpStatusCode;
			if($code >= 300 && $code < 400){
				$loc = isset($curl->responseHeaders['Location']) ? (string)$curl->responseHeaders['Location'] : '';
				$curl->close();
				if($loc === ''){ $err = 'redirect no location'; return false; }
				$url = self::_abs_url($loc, self::_base($url));
				$hops++; continue;
			}
			if($curl->error || $code < 200 || $code >= 300){
				$err = 'http '.$code.' '.(string)$curl->errorMessage; $curl->close(); return false;
			}
			$body = $curl->rawResponse; $curl->close();   // rawResponse = chuỗi thô (php-curl-class tự decode XML/JSON ở ->response)
			return is_string($body) ? $body : false;
		}
		$err = 'too many redirects'; return false;
	}

	/** Lấy URL bài viết từ TRANG DANH SÁCH (HTML) cho nguồn KHÔNG-RSS (is_rss=0).
	 *  Quy ước URL bài VN = {prefix}/{slug}-{id-số}; quét cả href lẫn JSON nhúng (Next.js __NEXT_DATA__). */
	static function extract_links($html, $list_url){
		$html = (string)$html;
		if($html === '') return array();
		$base = self::_base($list_url);
		$p = @parse_url($list_url);
		$prefix = isset($p['path']) ? rtrim($p['path'], '/') : '';
		if($prefix === '' || $prefix === '/') return array();
		$re = '#'.preg_quote($prefix, '#').'/[a-z0-9][a-z0-9\-]*-[0-9]{4,}#i';
		if(!preg_match_all($re, $html, $m)) return array();
		$links = array(); $seen = array();
		foreach($m[0] as $path){
			$u = self::_abs_url($path, $base);
			if($u === '' || isset($seen[$u]) || !self::_ssrf_safe($u)) continue;
			$seen[$u] = 1; $links[] = $u;
			if(count($links) >= 40) break;
		}
		return $links;
	}

	/** Chặn SSRF: chỉ http(s) + IP public (chặn private/reserved/loopback/link-local/metadata). */
	static function _ssrf_safe($url){
		$url = trim((string)$url);
		if($url === '') return false;
		$p = @parse_url($url);
		if(empty($p['scheme']) || !in_array(strtolower($p['scheme']), array('http','https'), true)) return false;
		if(empty($p['host'])) return false;
		$host = strtolower($p['host']);
		if($host === 'localhost' || substr($host, -6) === '.local') return false;
		$ips = array();
		if(filter_var($host, FILTER_VALIDATE_IP)){ $ips[] = $host; }
		else {
			$v4 = @gethostbynamel($host);
			if(is_array($v4)) $ips = array_merge($ips, $v4);
			$v6 = @dns_get_record($host, DNS_AAAA);
			if(is_array($v6)){ foreach($v6 as $r){ if(!empty($r['ipv6'])) $ips[] = $r['ipv6']; } }
		}
		if(empty($ips)) return false;            // không resolve được → từ chối (an toàn)
		foreach($ips as $ip){ if(self::_ip_blocked($ip)) return false; }
		return true;
	}
	static function _ip_blocked($ip){
		$flags = FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE;
		if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)){
			return !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | $flags);
		}
		if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)){
			return !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 | $flags);
		}
		return true;
	}

	/* =================== DOM (UTF-8 safe) =================== */
	static function loadDom($html){
		libxml_use_internal_errors(true);
		$dom = new DOMDocument();
		// '<?xml encoding="utf-8">' ép libxml hiểu UTF-8 (KHÔNG dùng mb_convert_encoding HTML-ENTITIES deprecated)
		$ok = $dom->loadHTML('<?xml encoding="utf-8"?>'."\n".$html);
		libxml_clear_errors();
		return $ok ? $dom : null;
	}

	/* =================== TẦNG 1: JSON-LD =================== */
	static function _jsonld($xp){
		$r = array('hit'=>false,'title'=>'','desc'=>'','date'=>0,'body'=>'','image'=>'');
		$nodes = $xp->query('//script[@type="application/ld+json"]');
		if(!$nodes) return $r;
		foreach($nodes as $s){
			$data = json_decode(trim($s->textContent), true);
			if(!is_array($data)) continue;
			$items = (isset($data['@graph']) && is_array($data['@graph'])) ? $data['@graph'] : array($data);
			foreach($items as $it){
				if(!is_array($it)) continue;
				$type = isset($it['@type']) ? $it['@type'] : '';
				$type = is_array($type) ? implode(',', $type) : (string)$type;
				if(stripos($type, 'Article') === false) continue;
				$r['hit'] = true;
				if($r['title'] === '' && !empty($it['headline']))    $r['title'] = trim($it['headline']);
				if($r['desc']  === '' && !empty($it['description']))  $r['desc']  = trim($it['description']);
				if(!$r['date'] && !empty($it['datePublished']))       $r['date']  = strtotime($it['datePublished']) ? strtotime($it['datePublished']) : 0;
				if($r['body']  === '' && !empty($it['articleBody']))  $r['body']  = '<p>'.nl2br(htmlspecialchars(trim($it['articleBody']), ENT_QUOTES, 'UTF-8')).'</p>';
				if($r['image'] === '' && !empty($it['image'])){
					$img = $it['image'];
					if(is_array($img)){
						if(isset($img['url'])) $img = $img['url'];
						else if(isset($img[0])) $img = is_array($img[0]) ? (isset($img[0]['url']) ? $img[0]['url'] : '') : $img[0];
						else $img = '';
					}
					$r['image'] = (string)$img;
				}
			}
		}
		return $r;
	}

	/* =================== TẦNG 2: OG / meta =================== */
	static function _og($xp){
		return array(
			'hit'   => (self::_meta($xp,'og:title') !== '' || self::_meta($xp,'og:image') !== ''),
			'title' => self::_meta($xp,'og:title'),
			'desc'  => self::_meta($xp,'og:description'),
			'image' => self::_meta($xp,'og:image'),
			'date'  => (int) (strtotime(self::_meta($xp,'article:published_time')) ?: 0),
		);
	}
	static function _meta($xp, $prop){
		$n = $xp->query('//meta[@property="'.$prop.'"]/@content | //meta[@name="'.$prop.'"]/@content');
		return ($n && $n->length) ? trim($n->item(0)->nodeValue) : '';
	}

	/* =================== TẦNG 4: heuristic readability (tự viết) =================== */
	static function _pick_main($xp){
		$best = null; $bestScore = -1;
		$cands = $xp->query('//article | //main | //div | //section');
		if(!$cands) return null;
		foreach($cands as $node){
			$ps = $node->getElementsByTagName('p');
			if($ps->length < 1) continue;
			$textLen = 0; $linkLen = 0;
			foreach($ps as $p){ $textLen += mb_strlen(trim($p->textContent)); }
			foreach($node->getElementsByTagName('a') as $a){ $linkLen += mb_strlen(trim($a->textContent)); }
			if($textLen < 60) continue;
			$score = $textLen + $ps->length * 25;
			$density = $textLen > 0 ? $linkLen / $textLen : 1;
			if($density > 0.4) $score *= 0.3;
			// purity: block chủ yếu là <p> được ưu tiên (chống ôm cả wrapper/sidebar)
			$total = mb_strlen(trim($node->textContent));
			$purity = $total > 0 ? $textLen / $total : 0;
			$score *= (0.3 + 0.7 * $purity);
			$cls = strtolower($node->getAttribute('class').' '.$node->getAttribute('id'));
			if(preg_match('/(nav|footer|header|sidebar|comment|menu|related|tinlienquan|banner|advert|share|widget)/', $cls)) $score *= 0.2;
			if($score > $bestScore){ $bestScore = $score; $best = $node; }
		}
		return $best;
	}

	/* =================== SANITIZE (chống XSS) =================== */
	static function _sanitize_html($html){
		$html = trim((string)$html);
		if($html === '') return '';
		$allow = array('p','h2','h3','h4','img','a','strong','em','b','i','u','ul','ol','li','blockquote','br','figure','figcaption','table','thead','tbody','tr','td','th');
		$allow_attr = array('href','src','alt','title');
		$d = self::loadDom('<div id="__nx_root">'.$html.'</div>');
		if(!$d) return '';
		$xp = new DOMXPath($d);
		$dangerous = $xp->query('//script | //style | //iframe | //object | //embed | //form | //noscript | //svg | //link | //meta');
		if($dangerous){
			$rm = array();
			foreach($dangerous as $n){ $rm[] = $n; }
			foreach($rm as $n){ if($n->parentNode) $n->parentNode->removeChild($n); }
		}
		$rootList = $xp->query('//*[@id="__nx_root"]');
		if(!$rootList || !$rootList->length) return '';
		$root = $rootList->item(0);
		self::_clean_node($root, $allow, $allow_attr);
		return self::_inner_html($root);
	}
	static function _clean_node($node, $allow, $allow_attr){
		$children = array();
		foreach($node->childNodes as $c){ $children[] = $c; }
		foreach($children as $c){
			if($c->nodeType === XML_ELEMENT_NODE){
				$tag = strtolower($c->tagName);
				if($c->hasAttributes()){
					$names = array();
					foreach($c->attributes as $a){ $names[] = $a->nodeName; }
					foreach($names as $an){
						$anl = strtolower($an);
						$keep = in_array($anl, $allow_attr, true) && substr($anl, 0, 2) !== 'on';
						if($keep && ($anl === 'href' || $anl === 'src')){
							// strip control/whitespace (chống "java\tscript:" sau khi DOM decode entity)
							$low = preg_replace('/[\x00-\x20]+/', '', strtolower($c->getAttribute($an)));
							// allowlist scheme: nếu có scheme tường minh thì CHỈ http(s); relative/anchor/protocol-relative cho qua
							if(preg_match('#^[a-z][a-z0-9+.\-]*:#', $low) && !preg_match('#^https?:#', $low)) $keep = false;
						}
						if(!$keep) $c->removeAttribute($an);
					}
				}
				self::_clean_node($c, $allow, $allow_attr);
				if(!in_array($tag, $allow, true)){
					while($c->firstChild){ $node->insertBefore($c->firstChild, $c); }
					$node->removeChild($c);
				}
			} else if($c->nodeType === XML_COMMENT_NODE){
				$node->removeChild($c);
			}
		}
	}

	/* =================== IMAGES =================== */
	static function _images($node, $base, $xp, $og_image){
		$imgs = array(); $seen = array();
		if($og_image !== ''){
			$u = self::_abs_url($og_image, $base);
			if($u !== '' && strpos($u,'data:') !== 0 && self::_ssrf_safe($u)){ $imgs[] = $u; $seen[$u] = 1; }
		}
		if($node){
			foreach($node->getElementsByTagName('img') as $img){
				$src = $img->getAttribute('data-src');
				if($src === '') $src = $img->getAttribute('data-original');
				if($src === ''){ $ss = $img->getAttribute('srcset'); if($ss !== '') $src = self::_srcset_largest($ss); }
				if($src === '') $src = $img->getAttribute('src');
				if($src === '') continue;
				$u = self::_abs_url($src, $base);
				if($u === '' || isset($seen[$u]) || strpos($u,'data:')===0 || !self::_ssrf_safe($u)) continue;
				$imgs[] = $u; $seen[$u] = 1;
				if(count($imgs) >= 8) break;
			}
		}
		return $imgs;
	}
	static function _srcset_largest($ss){
		$best = ''; $bw = -1;
		foreach(explode(',', $ss) as $part){
			$part = trim($part); if($part === '') continue;
			$seg = preg_split('/\s+/', $part);
			$url = $seg[0]; $w = 0;
			if(isset($seg[1]) && preg_match('/(\d+)w/', $seg[1], $m)) $w = (int)$m[1];
			if($w >= $bw){ $bw = $w; $best = $url; }
		}
		return $best;
	}

	/* =================== TẢI ẢNH VỀ LOCAL (curl, KHÔNG GD / allow_url_fopen) =================== */
	/** Tải 1 ảnh từ URL về server: cURL → bytes → xác thực ảnh thật → ghi file.
	 *  Thay uploadImageFromUrl (GD đọc URL từ xa cần allow_url_fopen / sai đuôi theo URL /
	 *  cURLdownload không follow-redirect + bỏ qua lỗi). Trả '/images/content/.../file.ext' hoặc ''. */
	static function download_image($url, $slug, &$err = ''){
		$err = '';
		$url = trim((string)$url);
		if($url === '' || !self::_ssrf_safe($url)){ $err = 'url rỗng/không an toàn'; return ''; }
		$slug = preg_replace('/[^A-Za-z0-9_\-]/', '', trim((string)$slug));   // chống path traversal (slug → tên thư mục)
		if($slug === '') $slug = 'news';
		$body = self::fetch_safe($url, $err);                       // curl, follow-redirect an toàn, không cần allow_url_fopen
		if($body === false || $body === ''){ if($err === '') $err = 'tải rỗng'; return ''; }
		if(strlen($body) > 12582912){ $err = 'ảnh > 12MB'; return ''; }
		$info = @getimagesizefromstring($body);                    // xác thực ảnh thật + đuôi theo NỘI DUNG (không tin URL)
		if($info === false || empty($info[0]) || empty($info[1])){ $err = 'không phải ảnh'; return ''; }
		$map = array(IMAGETYPE_JPEG=>'jpg', IMAGETYPE_PNG=>'png', IMAGETYPE_GIF=>'gif');
		if(defined('IMAGETYPE_WEBP')) $map[IMAGETYPE_WEBP] = 'webp';
		$type = isset($info[2]) ? (int)$info[2] : 0;
		if(!isset($map[$type])){ $err = 'định dạng không hỗ trợ'; return ''; }
		$ext = $map[$type];
		$reg = time();
		$dirname = '/content/'.date('Y',$reg).'/'.date('m',$reg).'/'.date('d',$reg).'/'.$slug;
		$name = $dirname.'/'.$slug.'-'.substr(md5($url), 0, 8).'.'.$ext;
		$abs_path = defined('ftp_abs_path_info') ? ftp_abs_path_info : '/images';
		$up = new UploadFile();
		if(defined('_upload_ftp') && _upload_ftp){
			$conn = @ftp_connect(ftp_host_info, 21, 20);
			if(!$conn){ $err = 'ftp connect lỗi'; return ''; }
			if(!@ftp_login($conn, ftp_usr_info, ftp_pwd_info)){ @ftp_close($conn); $err = 'ftp login lỗi'; return ''; }
			@ftp_pasv($conn, true);
			$up->makeFolder($dirname, $conn);
			$tmp = @tempnam(sys_get_temp_dir(), 'nxi');
			if($tmp === false || @file_put_contents($tmp, $body) === false){ @ftp_close($conn); $err = 'ghi temp lỗi'; return ''; }
			$ok = @ftp_put($conn, $name, $tmp, FTP_BINARY);
			@ftp_close($conn); @unlink($tmp);
			if(!$ok){ $err = 'ftp put lỗi'; return ''; }
		} else {
			$up->makeFolder($abs_path.$dirname);                   // tạo thư mục local (rmkdir đệ quy)
			$dest = ROOTPATH.$abs_path.$name;
			if(@file_put_contents($dest, $body) === false){ $err = 'ghi file local lỗi'; return ''; }
			@chmod($dest, 0644);
		}
		return $abs_path.$name;                                    // /images/content/.../file.ext
	}

	/** Tải mọi <img> trong nội dung về local (thay News::crawImage). Trả HTML đã đổi src. */
	static function localize_images($html, $slug){
		$html = (string)$html;
		if($html === '' || stripos($html, '<img') === false) return $html;
		$d = self::loadDom('<div id="__nx_root">'.$html.'</div>');
		if(!$d) return $html;
		$xp = new DOMXPath($d);
		$rootList = $xp->query('//*[@id="__nx_root"]');
		if(!$rootList || !$rootList->length) return $html;
		$root = $rootList->item(0);
		$cache = array(); $done = 0;
		foreach($d->getElementsByTagName('img') as $img){
			if($done >= 12) break;
			$src = trim($img->getAttribute('src'));
			if($src === '' || strpos($src, 'data:') === 0) continue;
			if(stripos($src, '/images/content/') !== false) continue;   // đã local rồi
			if(isset($cache[$src])){ $img->setAttribute('src', $cache[$src]); continue; }
			$e = ''; $local = self::download_image($src, $slug, $e);
			if($local !== ''){
				$full = (defined('FH_URL') ? FH_URL : '').$local;
				$cache[$src] = $full; $img->setAttribute('src', $full); $done++;
			}
		}
		return self::_inner_html($root);
	}

	/* =================== CSS → XPath (selector đơn giản) =================== */
	static function css2xpath($css){
		$css = trim((string)$css);
		if($css === '') return '';
		$css = preg_replace('/\s*>\s*/', ' > ', $css);
		$tokens = preg_split('/\s+/', $css);
		$xpath = ''; $child = false; $first = true;
		foreach($tokens as $t){
			if($t === '>'){ $child = true; continue; }
			if($t === '') continue;
			$axis = $first ? '//' : ($child ? '/' : '//');
			$xpath .= $axis . self::_css_token($t);
			$child = false; $first = false;
		}
		return $xpath;
	}
	static function _css_token($token){
		$tag = '*'; $preds = array();
		if(preg_match('/^[a-zA-Z][a-zA-Z0-9_-]*/', $token, $m)){ $tag = $m[0]; $token = substr($token, strlen($m[0])); }
		preg_match_all('/([.#\[])([^.#\[\]]*)\]?/', $token, $mm, PREG_SET_ORDER);
		foreach($mm as $x){
			$type = $x[1]; $val = $x[2];
			if($type === '.'){ $preds[] = "contains(concat(' ',normalize-space(@class),' '),' ".$val." ')"; }
			else if($type === '#'){ $preds[] = "@id='".$val."'"; }
			else if($type === '['){
				if(preg_match('/^([a-zA-Z0-9_:-]+)\s*([~^$*|]?=)?\s*["\']?([^"\']*)["\']?$/', $val, $am)){
					if(empty($am[2])){ $preds[] = '@'.$am[1]; }
					else { $preds[] = '@'.$am[1]."='".$am[3]."'"; }
				}
			}
		}
		return $tag . (count($preds) ? '['.implode(' and ', $preds).']' : '');
	}

	/* =================== Tiện ích =================== */
	static function _inner_html($node){
		$html = '';
		$doc = $node->ownerDocument;
		foreach($node->childNodes as $c){ $html .= $doc->saveHTML($c); }
		return trim($html);
	}
	static function _node_text($xp, $q){
		$n = $xp->query($q);
		return ($n && $n->length) ? trim($n->item(0)->textContent) : '';
	}
	static function _base($url){
		$p = @parse_url($url);
		$sc = !empty($p['scheme']) ? $p['scheme'] : 'https';
		$h  = !empty($p['host']) ? $p['host'] : '';
		return $sc.'://'.$h;
	}
	static function _abs_url($u, $base){
		$u = trim((string)$u);
		if($u === '') return '';
		if(strpos($u, '//') === 0){ $sc = parse_url($base, PHP_URL_SCHEME); return ($sc ? $sc : 'https').':'.$u; }
		if(preg_match('#^https?://#i', $u)) return $u;
		if(strpos($u, 'data:') === 0) return $u;
		if($u[0] === '/') return rtrim($base, '/').$u;
		return rtrim($base, '/').'/'.ltrim($u, '/');
	}
	static function _word_count($t){
		$t = trim(preg_replace('/\s+/', ' ', (string)$t));
		return $t === '' ? 0 : count(explode(' ', $t));
	}
}
